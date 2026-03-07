<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## О проекте

Проект доски обьявлений это домашняя работа програмистов

Вы можете поучаствовать, 
+ делая пул реквесты в код (ветка dev) , по решению вопросов из Issues, улучшению текущей кодовой базы
+ предлагая идеи что стоит реаллизовать (описание должно содержать, что делаем, что это улучшит, если возможен постепенный запуск для привыкания пользователй, его тоже опишите)

### Система Баллов (бонусная часть)

Как сформируется денежный поток, баллы постараемся оплатить небольшими, но родными доходами 

Пока проект не зарабатывает деньги, ведём учёт апрувленных (принятых) пул реквестов

## Схема сервиса

+ Каталоги 
+ Обьявления
  + название
  + тип ( продаю / хочу купить )
  + описание
  + фоточки
  + точка на карте
+ Личный кабинет авторов / администратора ( делим правами используя spatti )

используем 
+ DDD ( делим темы на отдельные блоки )
+ Solid ( слоёная структура - контроллр, сервис, dto, репозиторий )
+ на фронте livewire 4 + alpine

## Используемые слои архитектуры

В проекте используется слоистая архитектура с разделением ответственности:

+ `Presentation` (слой представления)
  + Папки: `app/Http`, `app/Livewire`, `resources/views`
  + Ответственность: входящие HTTP-запросы, UI-компоненты Livewire, валидация на границе, подготовка данных для интерфейса.

+ `Application` (слой сценариев)
  + Папка: `app/Application`
  + Ответственность: бизнес-сценарии и use-case логика, orchestration между доменом и инфраструктурой, DTO для передачи данных между слоями.

+ `Domain` (доменный слой)
  + Папка: `app/Domain`
  + Ответственность: предметная модель (сущности, правила, контракты репозиториев), чистая бизнес-логика без привязки к Laravel и БД.

+ `Infrastructure` (инфраструктурный слой)
  + Папка: `app/Infrastructure`
  + Ответственность: реализации репозиториев, работа с Eloquent/БД и другими внешними зависимостями.

Правило зависимостей: внешние слои могут зависеть от внутренних (`Presentation -> Application -> Domain`), а реализации инфраструктуры подключаются через контракты домена и application-слоя.

## Контакт для связи

по вопросам и запросам инструкции что да как делать, напишите мне 

Сергей
+ телеграм https://t.me/phpcatcom
+ vk https://vk.com/phpcatcom
