# Техническая SEO-спецификация

## Проект: `rabbitshole.ru`

## 1. Область применения

Документ определяет:

* какие SEO-данные выводятся на публичных страницах;
* откуда берётся каждое значение;
* как используется поле `seoDescription`;
* что происходит, если `seoDescription` не заполнено;
* какие правила действуют для:

  * страниц записей;
  * страниц разделов;
  * страниц категорий;
  * страниц фильтрации.

---

## 2. SEO-поля

### 2.1. Единственное SEO-поле

Во все публичные сущности добавлено поле:

```text
seoDescription
```

Поле используется как ручное переопределение `meta description`.

---

## 3. Набор SEO-данных страницы

Для каждой публичной страницы должны формироваться следующие значения:

* `title`
* `meta description`
* `canonical`
* `robots`
* `og:type`
* `og:title`
* `og:description`
* `og:url`
* `og:image`
* `twitter:card`
* `twitter:title`
* `twitter:description`
* `twitter:image`

---

## 4. Правила формирования SEO для страниц записей

## 4.1. Область действия

Правила применяются к шаблонам записей секций:

* `movies`
* `series`
* `anime`
* `games`
* `irl`
* `reactions`

Эти секции имеют собственные URI и шаблоны записей. 

---

## 4.2. `title`

`title` формируется автоматически по `entry.section.handle` и `entry.title`.

### Правила:

```text
movies:
Hard Play смотрит {entry.title}

series:
Hard Play смотрит {entry.title}

anime:
Hard Play смотрит {entry.title}

games:
Hard Play играет в {entry.title}

irl:
Hard Play IRL — {entry.title}

reactions:
Hard Play реагирует на {entry.title}
```

---

## 4.3. `meta description`

### Приоритет:

1. `entry.seoDescription`
2. `entry.description`, очищенное от HTML и обрезанное по длине
3. fallback-строка

Поле `description` присутствует во всех контентных секциях, поэтому оно используется как автоматический резервный источник, если `seoDescription` не заполнено. 

### Правило:

```text
if seoDescription is not empty:
    description = seoDescription
else if description is not empty:
    description = stripped_and_trimmed_description_limited_to_160
else:
    description = "Запись стрима Hard Play: {entry.title}."
```

### Требования к обработке `description`:

* удалить HTML-теги;
* удалить лишние пробелы;
* обрезать до 150–160 символов;
* не оставлять пустую строку.

---

## 4.4. `canonical`

Для страницы записи:

```text
canonical = entry.url
```

У секций уже определены URI-шаблоны записей, поэтому canonical не вводится вручную.  

---

## 4.5. `robots`

Для обычной страницы записи:

```text
robots = index,follow
```

---

## 4.6. `og:type`

Для страницы записи:

```text
og:type = article
```

Допустимо использовать `video.other`, но базовое и более нейтральное правило для каталога — `article`.

---

## 4.7. `og:title`

```text
og:title = title
```

---

## 4.8. `og:description`

```text
og:description = meta description
```

---

## 4.9. `og:url`

```text
og:url = canonical
```

---

## 4.10. `og:image`

Для записи:

```text
if entry.cover exists:
    og:image = entry.cover.url
else:
    og:image = default site image
```

Поле `cover` присутствует во всех контентных секциях. 

---

## 4.11. Twitter meta

```text
twitter:card = summary_large_image
twitter:title = og:title
twitter:description = og:description
twitter:image = og:image
```

---

## 5. Правила формирования SEO для страниц разделов

## 5.1. Область действия

Правила применяются к страницам:

* `/movies`
* `/series`
* `/anime`
* `/games`
* `/irl`
* `/reactions`

---

## 5.2. `title`

```text
movies:
Кино — стримы Hard Play

series:
Сериалы — стримы Hard Play

anime:
Аниме — стримы Hard Play

games:
Игры — стримы Hard Play

irl:
IRL — стримы Hard Play

reactions:
Реакции — стримы Hard Play
```

---

## 5.3. `meta description`

```text
movies:
Каталог записей стримов Hard Play по разделу «Кино».

series:
Каталог записей стримов Hard Play по разделу «Сериалы».

anime:
Каталог записей стримов Hard Play по разделу «Аниме».

games:
Каталог записей стримов Hard Play по разделу «Игры».

irl:
Каталог записей стримов Hard Play по разделу «IRL».

reactions:
Каталог записей стримов Hard Play по разделу «Реакции».
```

---

## 5.4. `canonical`

Для раздела:

```text
canonical = current section URL
```

Примеры:

```text
/movies
/series
/anime
```

---

## 5.5. `robots`

Для обычной страницы раздела:

```text
robots = index,follow
```

---

## 5.6. Open Graph и Twitter

```text
og:type = website
og:title = title
og:description = meta description
og:url = canonical
og:image = default site image
```

Twitter:

```text
twitter:card = summary_large_image
twitter:title = og:title
twitter:description = og:description
twitter:image = og:image
```

---

## 6. Правила формирования SEO для страниц категорий

## 6.1. Область действия

Правила применяются к категориям групп:

* `genres`
* `collections`

Эти группы уже используются в проекте как отдельные связанные сущности для публичной навигации. `genres` применяются в `movies`, `series`, `anime`, `games`, а `collections` — в `movies`, `series`, `anime`, `games`, `reactions`. 

---

## 6.2. `title`

### Для `genres`

```text
{category.title} — жанр | rabbitshole.ru
```

### Для `collections`

```text
{category.title} — подборка | rabbitshole.ru
```

---

## 6.3. `meta description`

### Приоритет:

1. `category.seoDescription`
2. автогенерируемое описание

### Для `genres`

```text
Записи стримов Hard Play в жанре «{category.title}».
```

### Для `collections`

```text
Подборка стримов Hard Play: {category.title}.
```

---

## 6.4. `canonical`

```text
canonical = category.url
```

---

## 6.5. `robots`

```text
robots = index,follow
```

---

## 6.6. Open Graph и Twitter

```text
og:type = website
og:title = title
og:description = meta description
og:url = canonical
og:image = default site image
```

Twitter:

```text
twitter:card = summary_large_image
twitter:title = og:title
twitter:description = og:description
twitter:image = og:image
```

---

## 7. Правила для страниц фильтрации

## 7.1. Область действия

Правила применяются к URL с query-параметрами, например:

```text
/movies?genre=comedy
/movies?collection=top
/series?genre=drama
```

---

## 7.2. `robots`

Для страниц фильтрации:

```text
robots = noindex,follow
```

---

## 7.3. `canonical`

Для страниц фильтрации canonical должен указывать на базовую страницу раздела без query-параметров.

Примеры:

```text
/movies?genre=comedy      -> canonical /movies
/movies?collection=top    -> canonical /movies
/series?genre=drama       -> canonical /series
```

---

## 8. Правила для пагинации

## 8.1. `title`

Если номер страницы больше 1:

```text
{base title} — страница {n}
```

Примеры:

```text
Кино — стримы Hard Play — страница 2
Сериалы — стримы Hard Play — страница 3
```

---

## 8.2. `canonical`

Для страниц пагинации canonical должен указывать на текущую paginated-страницу, а не всегда на первую.

Пример:

```text
/movies?page=2 -> canonical /movies?page=2
```

---

## 8.3. `robots`

```text
robots = index,follow
```

---

## 9. Шаблон вывода SEO в `<head>`

В базовом layout должен выводиться следующий набор тегов:

```twig
<title>{{ seo.title }}</title>

<meta name="description" content="{{ seo.description }}">
<link rel="canonical" href="{{ seo.canonical }}">
<meta name="robots" content="{{ seo.robots }}">

<meta property="og:type" content="{{ seo.ogType }}">
<meta property="og:site_name" content="rabbitshole.ru">
<meta property="og:title" content="{{ seo.ogTitle }}">
<meta property="og:description" content="{{ seo.ogDescription }}">
<meta property="og:url" content="{{ seo.ogUrl }}">

{% if seo.ogImage %}
    <meta property="og:image" content="{{ seo.ogImage }}">
{% endif %}

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ seo.twitterTitle }}">
<meta name="twitter:description" content="{{ seo.twitterDescription }}">

{% if seo.twitterImage %}
    <meta name="twitter:image" content="{{ seo.twitterImage }}">
{% endif %}
```

---

## 10. Правила обработки `seoDescription`

## 10.1. Если `seoDescription` заполнено

Используется оно без автогенерации.

```text
description = seoDescription
```

---

## 10.2. Если `seoDescription` не заполнено у записи

Используется `description` записи:

1. удалить HTML;
2. удалить лишние пробелы;
3. обрезать до 160 символов;
4. если после очистки строка пустая — использовать fallback.

---

## 10.3. Если `seoDescription` не заполнено у категории

Используется шаблонное описание категории.

---

## 10.4. Если `seoDescription` не заполнено у раздела

Используется шаблонное описание раздела.

---

## 11. Технические fallback-значения

## 11.1. Для записей

### Description fallback

```text
Запись стрима Hard Play: {entry.title}.
```

### Image fallback

```text
default site image
```

---

## 11.2. Для категорий

### Description fallback

Для `genres`:

```text
Записи стримов Hard Play в жанре «{category.title}».
```

Для `collections`:

```text
Подборка стримов Hard Play: {category.title}.
```

### Image fallback

```text
default site image
```

---

## 11.3. Для разделов

### Image fallback

```text
default site image
```

---

## 12. Краткая сводка правил

### Поля

Добавлено только одно SEO-поле:

```text
seoDescription
```

### Автоматически формируется

* `title`
* `canonical`
* `robots`
* `og:*`
* `twitter:*`

### Для записей

* `title` зависит от секции
* `description` берётся из `seoDescription`, иначе из `description`, иначе из fallback
* `canonical = entry.url`
* `robots = index,follow`
* `og:image = cover`, если есть

### Для категорий

* `title` по шаблону категории
* `description` из `seoDescription`, иначе автогенерация
* `canonical = category.url`
* `robots = index,follow`

### Для фильтров

* `robots = noindex,follow`
* `canonical = URL раздела без query`