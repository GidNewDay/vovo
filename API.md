# API документация – Поиск товаров с фильтрами

## Базовый URL

```
GET /api/products
```

## Параметры запроса (query string)

| Параметр | Тип | Обязательный | Описание | Допустимые значения / пример |
|----------|-----|--------------|----------|------------------------------|
| `q` | string | нет | Поиск по подстроке в названии товара | `?q=iPhone` |
| `price_from` | numeric | нет | Минимальная цена | `?price_from=100` |
| `price_to` | numeric | нет | Максимальная цена (должна быть >= price_from) | `?price_to=500` |
| `category_id` | integer | нет | ID категории (должен существовать в БД) | `?category_id=5` |
| `in_stock` | boolean | нет | Наличие на складе | `true` / `false` |
| `rating_from` | numeric | нет | Минимальный рейтинг (0-5) | `?rating_from=4.5` |
| `sort` | string | нет | Сортировка | `price_asc`, `price_desc`, `rating_desc`, `newest` |
| `per_page` | integer | нет | Количество товаров на странице (1-100, по умолчанию 15) | `?per_page=20` |
| `page` | integer | нет | Номер страницы (по умолчанию 1) | `?page=2` |

## Примеры запросов

### 1. Поиск по слову "Samsung" с сортировкой по цене по возрастанию

```bash
GET /api/products?q=Samsung&sort=price_asc
```

### 2. Товары категории 3, в наличии, ценой от 500 до 1500

```bash
GET /api/products?category_id=3&in_stock=true&price_from=500&price_to=1500
```

### 3. Рейтинг не ниже 4.0, сортировка по новизне (самые свежие первые)

```bash
GET /api/products?rating_from=4.0&sort=newest
```

### 4. Пагинация: 2 страница по 10 товаров

```bash
GET /api/products?per_page=10&page=2
```

### Пример ответа (успех, 200 OK)

```bash
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "name": "iPhone 14 Pro",
      "price": "999.99",
      "category_id": 2,
      "in_stock": true,
      "rating": 4.8,
      "created_at": "2025-04-20T12:00:00.000000Z",
      "updated_at": "2025-04-20T12:00:00.000000Z"
    },
    {
      "id": 2,
      "name": "Samsung Galaxy S23",
      "price": "849.99",
      "category_id": 2,
      "in_stock": true,
      "rating": 4.5,
      "created_at": "2025-04-18T12:00:00.000000Z",
      "updated_at": "2025-04-18T12:00:00.000000Z"
    }
  ],
  "first_page_url": "http://localhost/api/products?page=1",
  "from": 1,
  "last_page": 10,
  "last_page_url": "http://localhost/api/products?page=10",
  "links": [
    {
      "url": null,
      "label": "&laquo; Previous",
      "active": false
    },
    {
      "url": "http://localhost/api/products?page=1",
      "label": "1",
      "active": true
    },
    {
      "url": "http://localhost/api/products?page=2",
      "label": "2",
      "active": false
    },
    {
      "url": "http://localhost/api/products?page=2",
      "label": "Next &raquo;",
      "active": false
    }
  ],
  "next_page_url": "http://localhost/api/products?page=2",
  "path": "http://localhost/api/products",
  "per_page": 15,
  "prev_page_url": null,
  "to": 15,
  "total": 200
}
```