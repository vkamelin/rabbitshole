# 📦 Component: `content-card.twig`

Единый компонент карточки для отображения контента в списках (плиткой) для всех сущностей:

* movies
* series
* anime
* games
* irl
* reactions

---

## 🎯 Назначение

Компонент используется для:

* каталогов (`/movies`, `/series` и т.д.)
* страниц категорий (`actors`, `directors`, `collections`)
* блоков «Похожие», «Последние» и т.д.

Цель:

* единый UI
* единая логика отображения
* отсутствие дублирования Twig-кода

---

## 📁 Расположение

```
templates/_components/content-card.twig
```

---

## 🚀 Базовое использование

```twig
<div class="row row-cards">
    {% for entryItem in entries %}
        <div class="col-6 col-md-4 col-xl-3">
            {% include "_components/content-card" with {
                entry: entryItem
            } only %}
        </div>
    {% endfor %}
</div>
```

---

## ⚙️ Параметры компонента

| Параметр            | Тип   | По умолчанию | Описание                       |
| ------------------- | ----- | ------------ | ------------------------------ |
| `entry`             | Entry | —            | **Обязательный.** Запись Craft |
| `showSectionBadge`  | bool  | `true`       | Показывать тип контента        |
| `showDescription`   | bool  | `true`       | Показывать описание            |
| `showGenres`        | bool  | `true`       | Показывать жанры               |
| `showCollections`   | bool  | `true`       | Показывать подборки            |
| `showFooterButton`  | bool  | `true`       | Кнопка "Открыть"               |
| `descriptionLength` | int   | `140`        | Длина описания                 |

---

## 🧠 Пример с настройками

```twig
{% include "_components/content-card" with {
    entry: entryItem,
    showDescription: false,
    showCollections: false
} only %}
```

---

## 🧩 Поддерживаемые поля

Компонент **не требует полного набора полей**.

Он автоматически проверяет наличие:

* `cover`
* `title`
* `year`
* `duration`
* `rating`
* `description`
* `genres`
* `collections`
* `watchLinks`

Если поле отсутствует — блок просто не выводится.

👉 Это критично, потому что разные секции имеют разный набор полей.

---

## 🖼 Обложка (ВАЖНО)

* Всегда используется контейнер с соотношением **2:3**
* Изображение:

  ```css
  object-fit: cover;
  ```

### Поведение:

* есть обложка → показывается
* нет → заглушка "Нет обложки"

---

## 🎨 Визуальная структура

Карточка состоит из:

### 1. Обложка

* 2:3
* overlay сверху:

  * тип контента
  * рейтинг
* overlay снизу:

  * название

### 2. Контент

* мета (год, длительность, ссылки)
* описание
* жанры
* подборки
* кнопка

---

## ⭐ Рейтинг

Отображается badge с цветом:

| Рейтинг | Цвет   |
| ------- | ------ |
| ≥ 8.5   | green  |
| ≥ 7.0   | lime   |
| ≥ 5.5   | yellow |
| ≥ 4.0   | orange |
| < 4.0   | red    |

---

## 🏷 Тип контента (section badge)

Определяется по `entry.section.handle`:

| Section   | Цвет   |
| --------- | ------ |
| movies    | red    |
| series    | blue   |
| anime     | pink   |
| games     | green  |
| irl       | yellow |
| reactions | orange |

---

## 🔗 Ссылки

* вся карточка кликабельна
* ведёт на `entry.url`

---

## ⚠️ Важные правила

### 1. ❌ НЕ дублировать карточку в шаблонах

Плохо:

```twig
<div class="card">...</div>
```

Правильно:

```twig
{% include "_components/content-card" %}
```

---

### 2. ❌ НЕ добавлять бизнес-логику в компонент

Компонент:

* только отображает
* не фильтрует
* не делает сложные запросы

---

### 3. ❌ НЕ менять сетку внутри компонента

Сетка задаётся **снаружи**:

```twig
<div class="col-...">
```

---

### 4. ✅ Всегда передавать `entry`

Без него компонент не работает.

---

### 5. ⚠️ Проверять поля в Craft

Если поле не выводится:

* проверь handle поля
* проверь наличие данных

---

## 📐 Адаптивность

Рекомендуемая сетка:

```twig
col-6 col-md-4 col-xl-3
```

или плотнее:

```twig
col-6 col-md-3 col-xl-2
```

---

## 🔄 Где используется

* `/movies`
* `/series`
* `/collections/_category`
* `/actors/_category`
* `/directors/_category`

---

## 🧱 Дальнейшее развитие

Возможные улучшения:

* режимы карточки:

  * compact
  * minimal
* lazy loading + blur preview
* hover-анимации
* skeleton loading

---

## 💡 Итог

Компонент решает:

* единый UI
* отсутствие дублирования
* масштабируемость проекта
* нормальную поддержку разных типов контента
