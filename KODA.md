# KODA.md — Инструкции для проекта "Кроличья нора"

## Обзор проекта

**Название:** Кроличья нора (rabbitshole.ru)  
**Тип:** Веб-сайт на Craft CMS5.x  
**Назначение:** Каталог записей стримов Hard Play — сайт для каталогизации и отображения контента: кино, сериалов, аниме, игр, IRL-стримов и реакций.

## Технологический стек

| Компонент | Версия | Описание |
|-----------|--------|----------|
| PHP |8.x | Серверный язык программирования |
| Craft CMS |5.9.0+ | Фреймворк и система управления контентом |
| Twig | — | Шаблонизатор |
| Composer | — | Менеджер зависимостей PHP |
| Tabler CSS |1.4.0 | UI-фреймворк для стилей |
| vlucas/phpdotenv | ^5.4.0 | Загрузка переменных окружения |

## Структура проекта

```
rabbitshole/
├── bootstrap.php # Общий загрузчик приложения
├── composer.json # Зависимости проекта
├── composer.json.default # Шаблон composer.json после установки
├── composer.lock # Зафиксированные версии зависимостей
├── craft # CLI-интерфейс Craft
├── config/ # Конфигурация приложения
│ ├── app.php # Основная конфигурация приложения
│ ├── general.php # Общие настройки CMS
│ ├── routes.php # Маршрутизация URL
│ ├── twig-sandbox.php # Настройки песочницы Twig
│ ├── redirects.php # Редиректы
│ └── htmlpurifier/ # Конфигурация HTMLPurifier
├── templates/ # Twig-шаблоны
│ ├── index.twig # Главная страница
│ ├── anime/ # Шаблоны раздела "Аниме"
│ ├── games/ # Шаблоны раздела "Игры"
│ ├── movies/ # Шаблоны раздела "Кино"
│ ├── series/ # Шаблоны раздела "Сериалы"
│ ├── irl/ # Шаблоны раздела "IRL"
│ ├── reactions/ # Шаблоны раздела "Реакции"
│ ├── _layouts/ # Базовые макеты
│ │ └── base.twig # Основной layout страницы
│ ├── _components/ # Переиспользуемые компоненты
│ │ └── movie-card.twig
│ └── _section/ # Шаблоны секций (жанры, коллекции)
├── vendor/ # Зависимости Composer
├── web/ # Публичная директория
│ ├── index.php # Точка входа веб-приложения
│ ├── .htaccess # Конфигурация Apache
│ └── cpresources/ # Кэшированные ресурсы CP
└── storage/ # Временные файлы Craft
```

## Секции контента

Сайт содержит следующие секции (sections) в терминологии Craft CMS:

| Handle | Название | Описание |
|--------|----------|----------|
| `movies` | Кино | Фильмы |
| `series` | Сериалы | Сериалы |
| `anime` | Аниме | Аниме-контент |
| `games` | Игры | Видеоигры |
| `irl` | IRL | IRL-стримы |
| `reactions` | Реакции | Реакции на контент |
| `quotes` | Цитаты | Цитаты со стримов |

## Конфигурация окружения

### Файлы примеров конфигурации

- `.env.example.dev` — для разработки
- `.env.example.staging` — для staging-окружения
- `.env.example.production` — для production-окружения

### Ключевые переменные окружения

| Переменная | Описание | Пример |
|------------|----------|--------|
| `CRAFT_APP_ID` | Уникальный ID приложения | `CraftCMS` |
| `CRAFT_ENVIRONMENT` | Окружение (dev, staging, production) | `dev` |
| `CRAFT_SECURITY_KEY` | Ключ безопасности | (генерируется при установке) |
| `CRAFT_DEV_MODE` | Режим разработки | `true` |
| `CRAFT_ALLOW_ADMIN_CHANGES` | Разрешить изменения в админке | `true` |
| `CRAFT_DISALLOW_ROBOTS` | Запретить индексацию | `true` |

## Сборка и запуск

### Предварительные требования

- PHP8.x
- Composer
- Веб-сервер (Apache/Nginx) или встроенный PHP-сервер

### Установка

```bash
# Установка зависимостей
composer install

# Копирование .env файла (выполняется автоматически при composer install)
cp .env.example.dev .env

# Установка Craft CMS
php craft install
```

### Запуск в режиме разработки

```bash
# Встроенный PHP-сервер
php -S localhost:8080 -t web

# Или через Craft
php craft serve
```

### Основные команды CLI

```bash
# Список всех команд
php craft

# Очистка кэша
php craft clear-caches all

# Переиндекция поиска
php craft search-index/index

# Управление пользователями
php craft users/list
php craft users/create
```

### Сборка для production

1. Установить зависимости: `composer install --no-dev`
- Настроить `.env` файл с production-параметрами
- Запустить установку: `php craft install --interactive=0`
- Настроить веб-сервер (Apache/Nginx) с document root в `web/`

## Архитектура и шаблоны

### Layout-система

Базовый шаблон: `templates/_layouts/base.twig`

Содержит:
- Header с навигацией
- Блок для breadcrumbs
- Блок для flash-сообщений
- Основной контент
- Footer

### Компоненты

Переиспользуемые компоненты в `templates/_components/`:
- `movie-card.twig` — карточка для отображения контента

### Маршрутизация

Маршруты определены в `config/routes.php`:

```php
'<sectionHandle:{slug}>/genre/<categorySlug:{slug}>' => '_section/genre'
'<sectionHandle:{slug}>/collection/<categorySlug:{slug}>' => '_section/collection'
```

Также маршруты могут быть настроены через панель администрирования Craft.

## Правила разработки

### Стиль кода

- PHP: стандарты PSR-12, используемые в Craft CMS
- Twig: отступы4 пробела, читаемые имена переменных
- Именование секций (sections): snake_case (`movies`, `anime`, `reactions`)

### Шаблоны

- Twig-шаблоны располагаются в `templates/`
- Используется наследование через `{% extends %}`
- Переиспользуемые блоки выносятся в `_components/` и `_layouts/`
- Все текстовые данные на русском языке

### Конфигурация

- Общие настройки: `config/general.php`
- Конфигурация приложения: `config/app.php`
- Переменные окружения: `.env` файл (не коммитится в Git)

### Git

- `.gitignore` исключает: `.env`, `.idea`, `vendor`, `.DS_Store`
- Основная ветка: `main` или `master`

## Развёртывание

### Staging/Production

1. Скопировать проект на сервер
2. Скопировать/настроить `.env` файл
3. Выполнить `composer install --no-dev`
4. Запустить `php craft install` (или проверить существующую БД)
5. Настроить веб-сервер

### Настройка веб-сервера

Document root должен указывать на директорию `web/`. При использовании Apache настроить `.htaccess` (уже предоставлен в проекте).

## TODO

- [ ] Уточнить наличие миграций для секций контента
- [ ] Проверить наличие кастомных полей (fields) для секций
- [ ] Добавить документацию по развёртыванию (deployment)
- [ ] Определить процесс резервного копирования БД

## Полезные ссылки

- [Craft CMS Documentation](https://craftcms.com/docs)
- [Craft CMS5.x](https://craftcms.com/docs/5.x/)
- [Twig Documentation](https://twig.symfony.com/doc/)
- [Tabler CSS](https://tabler.io/)
