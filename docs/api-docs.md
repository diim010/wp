# REST API Documentation

## Base URL

```
https://yoursite.com/wp-json/rf/v1
```

## Authentication

### Methods

1. **WordPress Cookie** - For logged-in users
2. **API Key** - For external integrations
   - Header: `X-API-Key: your-api-key`
   - Or: `Authorization: Bearer your-api-key`

### Generate API Key

```php
$auth = new \RFPlugin\REST\Middleware\AuthMiddleware();
$apiKey = $auth->generateAPIKey($userId);
```

---

## Rate Limiting

| User Type | Limit | Window |
|-----------|-------|--------|
| Anonymous | 100 requests | 1 hour |
| Authenticated | 1000 requests | 1 hour |
| Admin | 10000 requests | 1 hour |

### Rate Limit Headers

```
X-RateLimit-Limit: 1000
X-RateLimit-Remaining: 950
X-RateLimit-Reset: 1738751234
```

---

## Services API

### List Services

```http
GET /services
```

**Query Parameters:**
- `page` (int) - Page number (default: 1)
- `per_page` (int) - Items per page (default: 20, max: 100)
- `search` (string) - Search term
- `category` (string) - Filter by category slug
- `featured` (boolean) - Filter featured services
- `orderby` (string) - Order by: date, title, modified
- `order` (string) - ASC or DESC

**Response:**

```json
{
  "success": true,
  "data": [
    {
      "id": 123,
      "title": "Premium Service",
      "excerpt": "Service description...",
      "thumbnail": "https://...",
      "duration": "2-3 weeks",
      "pricing": {
        "model": "fixed",
        "base_price": "5000",
        "note": "Custom pricing available"
      },
      "featured": true,
      "categories": ["consulting", "development"],
      "permalink": "https://..."
    }
  ],
  "pagination": {
    "total": 45,
    "count": 20,
    "per_page": 20,
    "current_page": 1,
    "total_pages": 3
  }
}
```

### Get Single Service

```http
GET /services/{id}
```

**Response:** Same as list item with full `content` field.

### Create Service

```http
POST /services
```

**Required Capability:** `edit_posts`

**Body:**

```json
{
  "title": "New Service",
  "content": "Full description...",
  "excerpt": "Short description",
  "duration": "1-2 weeks",
  "pricing_model": "fixed",
  "base_price": 3000,
  "visibility": "public",
  "featured": false
}
```

### Update Service

```http
PUT /services/{id}
```

**Required Capability:** `edit_posts`

### Delete Service

```http
DELETE /services/{id}
```

**Required Capability:** `delete_posts`

### Get Related Case Studies

```http
GET /services/{id}/case-studies
```

### Get Related Products

```http
GET /services/{id}/products
```

---

## Case Studies API

### List Case Studies

```http
GET /case-studies
```

**Query Parameters:**
- `page`, `per_page`, `search`, `orderby`, `order` - Same as Services
- `industry` (string) - Filter by industry slug
- `featured` (boolean) - Filter featured cases

**Response:**

```json
{
  "success": true,
  "data": [
    {
      "id": 456,
      "title": "Client Success Story",
      "excerpt": "How we helped...",
      "thumbnail": "https://...",
      "client": {
        "name": "ACME Corp",
        "industry": ["technology", "manufacturing"]
      },
      "featured": true,
      "permalink": "https://...",
      "date": "2024-01-15"
    }
  ]
}
```

### Get Single Case Study

```http
GET /case-studies/{id}
```

**Response includes:**

```json
{
  "success": true,
  "data": {
    "id": 456,
    "title": "Client Success Story",
    "content": "Full HTML content...",
    "project": {
      "challenge": "The problem...",
      "solution": "Our approach...",
      "results": [
        {
          "metric": "Revenue",
          "value": "150%",
          "description": "Increase in sales"
        }
      ]
    },
    "client": {
      "name": "ACME Corp",
      "website": "https://acme.com",
      "testimonial": "Excellent work!",
      "industry": ["technology"]
    },
    "media": {
      "gallery": [...],
      "video_url": "https://..."
    },
    "related_services": [...],
    "related_products": [...]
  }
}
```

### Create/Update/Delete

Same pattern as Services endpoints.

### Get Related Case Studies

```http
GET /case-studies/{id}/related
```

Returns similar case studies from the same industry.

---

## Products API

### List Products

```http
GET /products
```

**Response:**

```json
{
  "success": true,
  "data": [
    {
      "id": 789,
      "title": "Product Name",
      "sku": "PROD-123",
      "price": "99.99",
      "thumbnail": "https://...",
      "specifications": {
        "density": "35 kg/m³",
        "color": "Blue"
      },
      "materials": ["Polyurethane", "Foam"],
      "product_types": ["Wall Panel"]
    }
  ]
}
```

### Product Constructor

```http
POST /products/{id}/construct
```

Calculate custom product configuration:

**Body:**

```json
{
  "width": 1200,
  "height": 2400,
  "thickness": 50,
  "material": "polyurethane",
  "finish": "smooth"
}
```

**Response:**

```json
{
  "success": true,
  "data": {
    "product_id": 789,
    "specifications": {...},
    "calculated_price": "245.50",
    "dimensions": {
      "width": 1200,
      "height": 2400,
      "area_m2": 2.88
    },
    "weight_kg": 5.04
  }
}
```

---

## Resources API

### List Resources

```http
GET /resources
```

### Get Resource

```http
GET /resources/{id}
```

### Download Resource

```http
GET /resources/{id}/download
```

Generates secure, expiring download token.

---

## Error Responses

### Format

```json
{
  "code": "error_code",
  "message": "Human readable error message",
  "data": {
    "status": 404
  }
}
```

### Common Error Codes

| Code | Status | Description |
|------|--------|-------------|
| `rest_unauthorized` | 401 | Authentication required |
| `rest_forbidden` | 403 | Insufficient permissions |
| `rest_not_found` | 404 | Resource not found |
| `rest_rate_limit_exceeded` | 429 | Too many requests |
| `rest_invalid_param` | 400 | Invalid parameter |

---

## Caching

- **GET requests** are cached for 1 hour
- **ETag headers** enable conditional requests
- **Cache headers:** `Cache-Control: public, max-age=3600`

### Cache Invalidation

Cache is automatically cleared when:
- Content is created/updated/deleted
- Related items are modified

---

## Pagination

### Cursor-based (Recommended)

Use `page` and `per_page` parameters.

### Headers

```
X-WP-Total: 150
X-WP-TotalPages: 8
```

---

## Example Usage

### JavaScript (Fetch)

```javascript
// List services
const response = await fetch('https://yoursite.com/wp-json/rf/v1/services?per_page=10&featured=true');
const data = await response.json();

// With API key
const response = await fetch('https://yoursite.com/wp-json/rf/v1/services', {
  headers: {
    'X-API-Key': 'your-api-key-here'
  }
});
```

### cURL

```bash
# List case studies
curl "https://yoursite.com/wp-json/rf/v1/case-studies?industry=technology"

# Create service (with auth)
curl -X POST "https://yoursite.com/wp-json/rf/v1/services" \
  -H "X-API-Key: your-api-key" \
  -H "Content-Type: application/json" \
  -d '{"title":"New Service","content":"Description","pricing_model":"fixed","base_price":5000}'
```

### PHP

```php
$response = wp_remote_get('https://yoursite.com/wp-json/rf/v1/services');
$data = json_decode(wp_remote_retrieve_body($response), true);
```

---

## Postman Collection

Download our [Postman Collection](./rf-api-postman.json) for testing.
