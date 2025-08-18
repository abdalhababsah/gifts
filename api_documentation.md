# Gifts API Documentation

This document provides detailed information about the Gifts API endpoints for products and categories.

## Base URL

```
http://localhost:8000/api
```

Replace `localhost:8000` with your actual domain if deployed.

## Headers

All API requests should include the following headers:

```
Accept: application/json
Accept-Language: en  # or 'ar' for Arabic responses
```

## Authentication

Most product and category endpoints are public and don't require authentication.

---

## Categories API

### Get All Categories

Retrieves a list of all active categories.

**URL**: `/categories`

**Method**: `GET`

**URL Parameters**: None

**Query Parameters**: None

**Success Response**:

```json
{
  "data": [
    {
      "id": 1,
      "name": "Electronics",
      "slug": "electronics"
    },
    {
      "id": 2,
      "name": "Clothing",
      "slug": "clothing"
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/categories?page=1",
    "last": "http://localhost:8000/api/categories?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "links": [
      {
        "url": null,
        "label": "&laquo; Previous",
        "active": false
      },
      {
        "url": "http://localhost:8000/api/categories?page=1",
        "label": "1",
        "active": true
      },
      {
        "url": null,
        "label": "Next &raquo;",
        "active": false
      }
    ],
    "path": "http://localhost:8000/api/categories",
    "per_page": 15,
    "to": 2,
    "total": 2
  }
}
```

---

## Brands API

### Get All Brands

Retrieves a list of all active brands.

**URL**: `/brands`

**Method**: `GET`

**URL Parameters**: None

**Query Parameters**: None

**Success Response**:

```json
{
  "data": [
    {
      "id": 1,
      "name": "Apple",
      "slug": "apple",
      "image": "http://localhost:8000/storage/brands/apple.png"
    },
    {
      "id": 2,
      "name": "Samsung",
      "slug": "samsung",
      "image": "http://localhost:8000/storage/brands/samsung.png"
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/brands?page=1",
    "last": "http://localhost:8000/api/brands?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "links": [
      {
        "url": null,
        "label": "&laquo; Previous",
        "active": false
      },
      {
        "url": "http://localhost:8000/api/brands?page=1",
        "label": "1",
        "active": true
      },
      {
        "url": null,
        "label": "Next &raquo;",
        "active": false
      }
    ],
    "path": "http://localhost:8000/api/brands",
    "per_page": 15,
    "to": 2,
    "total": 2
  }
}
```

---

## Products API

### Get All Products

Retrieves a paginated list of all active products.

**URL**: `/products`

**Method**: `GET`

**URL Parameters**: None

**Query Parameters**: 

- `search` (optional): Search term for product names (in both English and Arabic)
- `brand_id` (optional): Filter by brand ID
- `category_id` (optional): Filter by category ID
- `sort` (optional): Sort by price (`price_asc` or `price_desc`)
- `per_page` (optional): Number of products per page (default: 16, max: 50)

**Success Response**:

```json
{
  "data": [
    {
      "id": 1,
      "name": "iPhone 13",
      "description": "Latest iPhone model with A15 Bionic chip",
      "price": "999.00",
      "stock": 50,
      "sku": "IP-13-128",
      "is_active": true,
      "is_featured": true,
      "cover_image": "http://localhost:8000/storage/products/iphone13.jpg",
      "brand": {
        "id": 1,
        "name": "Apple",
        "slug": "apple"
      },
      "category": {
        "id": 1,
        "name": "Electronics",
        "slug": "electronics"
      },
      "images": [
        {
          "id": 1,
          "url": "http://localhost:8000/storage/products/gallery/iphone13-1.jpg"
        },
        {
          "id": 2,
          "url": "http://localhost:8000/storage/products/gallery/iphone13-2.jpg"
        }
      ]
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/products?page=1",
    "last": "http://localhost:8000/api/products?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "links": [
      {
        "url": null,
        "label": "&laquo; Previous",
        "active": false
      },
      {
        "url": "http://localhost:8000/api/products?page=1",
        "label": "1",
        "active": true
      },
      {
        "url": null,
        "label": "Next &raquo;",
        "active": false
      }
    ],
    "path": "http://localhost:8000/api/products",
    "per_page": 16,
    "to": 1,
    "total": 1
  }
}
```

### Get Featured Products

Retrieves a list of featured products (max 9).

**URL**: `/products/featured`

**Method**: `GET`

**URL Parameters**: None

**Query Parameters**: None

**Success Response**:

```json
{
  "data": [
    {
      "id": 1,
      "name": "iPhone 13",
      "description": "Latest iPhone model with A15 Bionic chip",
      "price": "999.00",
      "stock": 50,
      "sku": "IP-13-128",
      "is_active": true,
      "is_featured": true,
      "cover_image": "http://localhost:8000/storage/products/iphone13.jpg",
      "brand": {
        "id": 1,
        "name": "Apple",
        "slug": "apple"
      },
      "category": {
        "id": 1,
        "name": "Electronics",
        "slug": "electronics"
      },
      "images": [
        {
          "id": 1,
          "url": "http://localhost:8000/storage/products/gallery/iphone13-1.jpg"
        },
        {
          "id": 2,
          "url": "http://localhost:8000/storage/products/gallery/iphone13-2.jpg"
        }
      ]
    }
  ]
}
```

### Get Product by ID

Retrieves details of a specific product.

**URL**: `/products/{id}`

**Method**: `GET`

**URL Parameters**:

- `id`: The ID of the product to retrieve

**Query Parameters**: None

**Success Response**:

```json
{
  "data": {
    "id": 1,
    "name": "iPhone 13",
    "description": "Latest iPhone model with A15 Bionic chip",
    "price": "999.00",
    "stock": 50,
    "sku": "IP-13-128",
    "is_active": true,
    "is_featured": true,
    "cover_image": "http://localhost:8000/storage/products/iphone13.jpg",
    "brand": {
      "id": 1,
      "name": "Apple",
      "slug": "apple"
    },
    "category": {
      "id": 1,
      "name": "Electronics",
      "slug": "electronics"
    },
    "images": [
      {
        "id": 1,
        "url": "http://localhost:8000/storage/products/gallery/iphone13-1.jpg"
      },
      {
        "id": 2,
        "url": "http://localhost:8000/storage/products/gallery/iphone13-2.jpg"
      }
    ]
  }
}
```

**Error Response**:

```json
{
  "success": false,
  "message": "Product not found",
  "errors": {
    "id": ["Product not found"]
  }
}
```

## Error Responses

All API endpoints return standardized error responses:

### Validation Error (422 Unprocessable Entity)

```json
{
  "success": false,
  "message": "Invalid parameters provided",
  "errors": {
    "brand_id": ["The selected brand does not exist"]
  }
}
```

### Not Found Error (404 Not Found)

```json
{
  "success": false,
  "message": "Product not found"
}
```

### Server Error (500 Internal Server Error)

```json
{
  "success": false,
  "message": "An error occurred while fetching products"
}
```

## Localization

All APIs support both English and Arabic responses. To get responses in Arabic, set the `Accept-Language` header to `ar`:

```
Accept-Language: ar
```

This will return all messages and product/category names in Arabic.
