## Запуск 

`git clone https://github.com/Farlom/k-telecom-test-case.git`

`cd ./k-telecom-test-case`

`composer install`

`cp .env.example .env`

`php artisan key:generate`

`php artisan migrate`

`php artisan db:seed`

`php artisan serve`

## Postman

https://www.postman.com/research-meteorologist-45273918/workspace/public/collection/27303078-40824eb1-809c-4e90-8ddc-487ef8b2b194

## CHANGELOG

FINAL

CRUD Bulk Store

- Добавление данных (метод store) с возвратом ошибок и успехов

CRUD Destroy method

- Добавлен функицонал методу Destroy

Soft deletes

- soft delete для модели equipment

CRUD template, start API

- маршрутизация
- миграции, модели
- добавлены основные контроллеры
- ресурсы, коллекции, etc
- базовый функционал CRUD (index, show, store)

initial commit

- cтарт проекта


