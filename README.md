# Лабораторная работа №8: PHPUnit + Guzzle + тестовая БД

## Автор

ФИО: Лямичев Семён Яковлевич  
Группа: ПМИ2-ИП1

---

## Описание задания

1. Установить и использовать PHPUnit.
2. Писать unit-тесты для классов, использовать mock-объекты PDO.
3. Тестировать HTTP-запросы через Guzzle и через MockHandler без реальной сети.
4. Подключать переменные окружения из файла `.env.test` в тестах.
5. Изолировать окружение: отдельная тестовая MySQL в Docker, интеграционные тесты с реальной БД.

Предметная область совпадает с лабораторной работой №7 (чётный вариант — RabbitMQ в той работе): класс `Student` и таблица студентов. В данной работе очереди не используются.

Результат веб-страницы по адресу <http://localhost:8080> (минимальный `index.php` для проверки Guzzle).

---

## Как запустить проект

1. Клонировать репозиторий:  
   `git clone https://github.com/WorkNFlow/PHPUnit-Guzzle-Tests.git`  
   `cd PHPUnit-Guzzle-Tests`

2. В корне создать файл `.env.test` (он в `.gitignore`), например:

   ```
   DB_HOST=db
   DB_NAME=test_db
   DB_USER=test_user
   DB_PASSWORD=test_pass
   ```

3. Установить зависимости:  
   `composer install`

4. Запустить контейнеры:  
   `docker compose up -d --build`

5. Тесты без HTTP и без интеграции с БД (на машине разработчика):  
   `vendor/bin/phpunit`

6. HTTP-тест Guzzle (из контейнера PHP, где доступен хост `nginx`):  
   `docker compose exec php vendor/bin/phpunit --group http`

7. Интеграционные тесты с MySQL (нужен запущенный `docker compose` с сервисом `db`):  
   `docker compose exec php vendor/bin/phpunit --group integration`

8. Полная проверка в Docker (по очереди default, http, integration):  
   `docker compose exec php sh -c "vendor/bin/phpunit && vendor/bin/phpunit --group http && vendor/bin/phpunit --group integration"`

---

## Содержимое проекта

```
docker-compose.yml     — PHP-FPM, nginx, MySQL (тестовая БД)
Dockerfile             — образ PHP с Composer, pdo_mysql
nginx.conf             — проксирование PHP
phpunit.xml            — конфигурация PHPUnit, bootstrap, группы тестов
composer.json          — phpunit, guzzlehttp/guzzle
www/
  index.php            — ответ 200 для HTTP-теста
  Student.php          — сохранение и выборка студентов (PDO, переменные окружения)
tests/
  bootstrap.php        — автозагрузка и разбор .env.test
  ExampleTest.php      — первый тест
  StudentTest.php      — unit-тесты и mock PDO
  ApiTest.php          — Guzzle к nginx (группа http)
  HttpMockTest.php     — Guzzle MockHandler
  EnvTest.php          — проверка DB_HOST из окружения
  StudentIntegrationTest.php — интеграция с MySQL (группа integration)
```

---

## Результат

Настроены PHPUnit и Guzzle, написаны unit-тесты с mock PDO, HTTP-тест к nginx, тест с MockHandler, загрузка `.env.test`, интеграционные тесты с отдельной БД в Docker, проверка количества записей и сценарий ошибки при неверном пароле к БД.
