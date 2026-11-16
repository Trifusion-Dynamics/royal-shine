# API Documentation — Royal Shine Backend

**Base URL:** `http://localhost:4000/api/v1`  
**Swagger UI:** `http://localhost:4000/api/docs`  
**Content-Type:** `application/json`  
**Authentication:** `Authorization: Bearer <JWT>`

---

## Table of Contents

1. [Authentication](#1-authentication)
2. [Products](#2-products)
3. [Categories](#3-categories)
4. [Collections](#4-collections)
5. [Banners](#5-banners)
6. [Enquiries](#6-enquiries)
7. [Gallery](#7-gallery)
8. [Pages](#8-pages)
9. [Settings](#9-settings)
10. [Testimonials](#10-testimonials)
11. [Users (Admin)](#11-users-admin)
12. [Dashboard](#12-dashboard)
13. [Upload](#13-upload)

---

## 1. Authentication

### POST /auth/login

Log in and receive a JWT access token.

**Auth required:** No

**Request body:**
```json
{
  "email": "admin@royalshine.com",
  "password": "secret123"
}
```

**Response `200 OK`:**
```json
{
  "accessToken": "eyJhbGc...",
  "user": {
    "id": 1,
    "name": "Admin",
    "email": "admin@royalshine.com",
    "role": "super_admin"
  }
}
```

**Errors:** `401` invalid credentials.

---

### GET /auth/me

Get the currently authenticated user.

**Auth required:** Yes

**Response `200 OK`:**
```json
{
  "id": 1,
  "name": "Admin",
  "email": "admin@royalshine.com",
  "role": "super_admin",
  "isAdmin": true,
  "createdAt": "2024-01-01T00:00:00.000Z"
}
```

---

## 2. Products

### GET /products

List published products (public, paginated, filterable).

**Auth required:** No

**Query params:**

| Param | Type | Description |
|---|---|---|
| `page` | number | Page number (default: 1) |
| `limit` | number | Items per page (default: 20, max: 100) |
| `category` | string | Filter by category slug |
| `collection` | string | Filter by collection slug |
| `metalType` | string | `gold` \| `silver` \| `artificial` \| `other` |
| `isFeatured` | boolean | `true` to list featured only |
| `search` | string | Full-text search on name/description |
| `sort` | string | `sort_order` \| `created_at` \| `name` |
| `order` | string | `asc` \| `desc` (default: `asc`) |

**Response `200 OK`:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Gold Bangle",
      "slug": "gold-bangle",
      "sku": "GB001",
      "shortDescription": "...",
      "metalType": "gold",
      "goldPurity": "22K",
      "price": null,
      "priceOnRequest": true,
      "isFeatured": true,
      "status": "published",
      "category": { "id": 2, "name": "Bangles", "slug": "bangles" },
      "collection": null,
      "images": [
        { "id": 1, "image": "https://res.cloudinary.com/...", "isPrimary": true }
      ],
      "createdAt": "2024-01-01T00:00:00.000Z"
    }
  ],
  "meta": {
    "total": 120,
    "page": 1,
    "limit": 20,
    "totalPages": 6
  }
}
```

---

### GET /products/:slug

Get a single published product by slug.

**Auth required:** No

**Response `200 OK`:**
```json
{
  "id": 1,
  "name": "Gold Bangle",
  "slug": "gold-bangle",
  "sku": "GB001",
  "shortDescription": "...",
  "description": "<p>Full HTML description</p>",
  "metalType": "gold",
  "goldPurity": "22K",
  "weight": "12.500",
  "weightUnit": "grams",
  "stoneDetails": "...",
  "price": null,
  "priceOnRequest": true,
  "isFeatured": true,
  "status": "published",
  "seoTitle": "...",
  "seoDescription": "...",
  "category": { "id": 2, "name": "Bangles", "slug": "bangles" },
  "collection": null,
  "images": [
    { "id": 1, "image": "https://...", "altText": "...", "isPrimary": true, "sortOrder": 0 }
  ]
}
```

**Errors:** `404` if not found or not published.

---

### POST /admin/products

Create a new product.

**Auth required:** Yes (`content_manager`, `admin`, `super_admin`)

**Request body:**
```json
{
  "name": "Gold Bangle",
  "categoryId": 2,
  "collectionId": null,
  "shortDescription": "...",
  "description": "<p>...</p>",
  "metalType": "gold",
  "goldPurity": "22K",
  "weight": 12.5,
  "weightUnit": "grams",
  "stoneDetails": "...",
  "price": null,
  "priceOnRequest": true,
  "isFeatured": false,
  "status": "draft",
  "seoTitle": "...",
  "seoDescription": "...",
  "sortOrder": 0,
  "images": [
    { "image": "https://res.cloudinary.com/...", "altText": "...", "isPrimary": true, "sortOrder": 0 }
  ]
}
```

**Response `201 Created`:** Full product object (same shape as GET /products/:slug).

---

### PUT /admin/products/:id

Update a product.

**Auth required:** Yes (`content_manager`, `admin`, `super_admin`)

**Request body:** Same as POST, all fields optional (partial update).

**Response `200 OK`:** Updated product object.

---

### DELETE /admin/products/:id

Delete a product and its associated images.

**Auth required:** Yes (`admin`, `super_admin`)

**Response `200 OK`:**
```json
{ "message": "Product deleted successfully" }
```

---

## 3. Categories

### GET /categories

List all active categories.

**Auth required:** No

**Query params:** `isFeatured` (boolean)

**Response `200 OK`:**
```json
[
  {
    "id": 1,
    "name": "Bangles",
    "slug": "bangles",
    "description": "...",
    "image": "https://...",
    "isFeatured": true,
    "sortOrder": 1
  }
]
```

---

### GET /categories/:slug

Get a single category with its products.

**Auth required:** No

**Response `200 OK`:** Category object + `products` array (published, paginated via `?page=1&limit=20`).

---

### POST /admin/categories

Create a category.

**Auth required:** Yes (`content_manager+`)

**Request body:**
```json
{
  "name": "Bangles",
  "description": "...",
  "image": "https://...",
  "isFeatured": true,
  "status": "active",
  "seoTitle": "...",
  "seoDescription": "...",
  "sortOrder": 1
}
```

---

### PUT /admin/categories/:id

Update a category. Same body as POST (partial).

**Auth required:** Yes (`content_manager+`)

---

### DELETE /admin/categories/:id

**Auth required:** Yes (`admin+`)

---

## 4. Collections

Identical structure to Categories.

| Method | Endpoint | Auth |
|---|---|---|
| GET | /collections | Public |
| GET | /collections/:slug | Public |
| POST | /admin/collections | content_manager+ |
| PUT | /admin/collections/:id | content_manager+ |
| DELETE | /admin/collections/:id | admin+ |

**Request/response shapes:** Same as Categories.

---

## 5. Banners

### GET /banners

List active banners (sorted by `sortOrder`). Filters by `startsAt`/`endsAt` automatically.

**Auth required:** No

**Response `200 OK`:**
```json
[
  {
    "id": 1,
    "image": "https://...",
    "mobileImage": "https://...",
    "heading": "New Collection",
    "subheading": "Explore Gold Jewellery",
    "ctaLabel": "Shop Now",
    "ctaUrl": "/products",
    "altText": "...",
    "sortOrder": 0
  }
]
```

---

### GET /admin/banners

List all banners (including inactive).

**Auth required:** Yes (`content_manager+`)

---

### POST /admin/banners

**Auth required:** Yes (`content_manager+`)

**Request body:**
```json
{
  "image": "https://...",
  "mobileImage": "https://...",
  "heading": "New Collection",
  "subheading": "...",
  "ctaLabel": "Shop Now",
  "ctaUrl": "/products",
  "altText": "...",
  "status": "active",
  "sortOrder": 0,
  "startsAt": null,
  "endsAt": null
}
```

---

### PUT /admin/banners/:id

**Auth required:** Yes (`content_manager+`)

---

### DELETE /admin/banners/:id

**Auth required:** Yes (`admin+`)

---

## 6. Enquiries

### POST /enquiries

Submit a customer enquiry (contact form or product enquiry).

**Auth required:** No

**Request body:**
```json
{
  "name": "Priya Sharma",
  "mobile": "+919876543210",
  "email": "priya@example.com",
  "subject": "Product Enquiry",
  "enquiryType": "product",
  "productId": 5,
  "productReference": "Gold Bangle GB001",
  "message": "Is this available in 18K?"
}
```

**Response `201 Created`:**
```json
{ "message": "Enquiry submitted successfully", "id": 42 }
```

---

### GET /admin/enquiries

List all enquiries with filters.

**Auth required:** Yes (`enquiry_manager+`)

**Query params:**

| Param | Type | Description |
|---|---|---|
| `page` | number | Page number |
| `limit` | number | Items per page |
| `status` | string | `new` \| `contacted` \| `in_progress` \| `converted` \| `closed` |
| `enquiryType` | string | `product` \| `contact` |
| `search` | string | Search name, mobile, email |

**Response `200 OK`:**
```json
{
  "data": [
    {
      "id": 42,
      "name": "Priya Sharma",
      "mobile": "+919876543210",
      "email": "priya@example.com",
      "subject": "Product Enquiry",
      "enquiryType": "product",
      "status": "new",
      "product": { "id": 5, "name": "Gold Bangle", "slug": "gold-bangle" },
      "assignedAdmin": null,
      "createdAt": "2024-06-01T10:00:00.000Z"
    }
  ],
  "meta": { "total": 150, "page": 1, "limit": 20, "totalPages": 8 }
}
```

---

### PUT /admin/enquiries/:id

Update enquiry status and internal notes.

**Auth required:** Yes (`enquiry_manager+`)

**Request body:**
```json
{
  "status": "contacted",
  "internalNotes": "Called customer, interested in 22K option",
  "assignedAdminId": 2,
  "followUpDate": "2024-06-10"
}
```

---

### DELETE /admin/enquiries/:id

**Auth required:** Yes (`admin+`)

---

## 7. Gallery

### GET /gallery

List active gallery images.

**Auth required:** No

**Response `200 OK`:**
```json
[
  {
    "id": 1,
    "image": "https://...",
    "title": "Bridal Collection",
    "description": "...",
    "altText": "...",
    "sortOrder": 0
  }
]
```

---

### Admin CRUD

| Method | Endpoint | Auth |
|---|---|---|
| GET | /admin/gallery | content_manager+ |
| POST | /admin/gallery | content_manager+ |
| PUT | /admin/gallery/:id | content_manager+ |
| DELETE | /admin/gallery/:id | admin+ |

**POST body:**
```json
{
  "image": "https://...",
  "title": "...",
  "description": "...",
  "altText": "...",
  "status": "active",
  "sortOrder": 0
}
```

---

## 8. Pages

### GET /pages/:slug

Fetch a CMS page by slug (e.g. `about-us`, `privacy-policy`).

**Auth required:** No

**Response `200 OK`:**
```json
{
  "id": 1,
  "slug": "about-us",
  "title": "About Us",
  "content": "<p>...</p>",
  "status": "published",
  "seoTitle": "...",
  "seoDescription": "..."
}
```

---

### Admin CRUD

| Method | Endpoint | Auth |
|---|---|---|
| GET | /admin/pages | content_manager+ |
| POST | /admin/pages | content_manager+ |
| PUT | /admin/pages/:id | content_manager+ |
| DELETE | /admin/pages/:id | admin+ |

---

## 9. Settings

### GET /settings

Fetch all public site settings as a key-value map.

**Auth required:** No

**Response `200 OK`:**
```json
{
  "site_name": "Royal Shine",
  "site_tagline": "Timeless Jewellery",
  "contact_phone": "+91...",
  "contact_email": "info@royalshine.com",
  "instagram_url": "https://instagram.com/...",
  "whatsapp_number": "+91..."
}
```

---

### PUT /admin/settings

Bulk-update site settings.

**Auth required:** Yes (`admin+`)

**Request body:**
```json
{
  "site_name": "Royal Shine Jewellers",
  "contact_phone": "+919876543210"
}
```

**Response `200 OK`:** Updated settings map.

---

## 10. Testimonials

### GET /testimonials

List approved testimonials.

**Auth required:** No

**Response `200 OK`:**
```json
[
  {
    "id": 1,
    "customerName": "Anita Patel",
    "customerLocation": "Mumbai",
    "testimonial": "Beautiful craftsmanship!",
    "image": null,
    "rating": 5,
    "sortOrder": 0
  }
]
```

---

### Admin CRUD

| Method | Endpoint | Auth |
|---|---|---|
| GET | /admin/testimonials | content_manager+ |
| POST | /admin/testimonials | content_manager+ |
| PUT | /admin/testimonials/:id | content_manager+ |
| DELETE | /admin/testimonials/:id | admin+ |

**POST body:**
```json
{
  "customerName": "Anita Patel",
  "customerLocation": "Mumbai",
  "testimonial": "...",
  "image": null,
  "rating": 5,
  "sortOrder": 0,
  "isApproved": true
}
```

---

## 11. Users (Admin)

All user endpoints require `super_admin` role.

### GET /admin/users

List all admin users.

**Response `200 OK`:**
```json
[
  {
    "id": 1,
    "name": "Super Admin",
    "email": "admin@royalshine.com",
    "role": "super_admin",
    "isAdmin": true,
    "createdAt": "2024-01-01T00:00:00.000Z"
  }
]
```

---

### POST /admin/users

Create a new admin user.

**Request body:**
```json
{
  "name": "Content Manager",
  "email": "content@royalshine.com",
  "password": "strongpassword123",
  "role": "content_manager",
  "isAdmin": true
}
```

---

### PUT /admin/users/:id

Update user details (name, email, role, password optional).

---

### DELETE /admin/users/:id

Delete a user (cannot delete yourself).

---

## 12. Dashboard

### GET /admin/dashboard/stats

Aggregate stats for the admin dashboard.

**Auth required:** Yes (`admin+`)

**Response `200 OK`:**
```json
{
  "products": {
    "total": 245,
    "published": 198,
    "draft": 47
  },
  "enquiries": {
    "total": 1200,
    "new": 34,
    "inProgress": 12
  },
  "categories": { "total": 18 },
  "collections": { "total": 8 },
  "banners": { "active": 3 },
  "gallery": { "total": 60 },
  "testimonials": { "approved": 24 },
  "recentEnquiries": [
    {
      "id": 155,
      "name": "Priya Sharma",
      "subject": "Product Enquiry",
      "status": "new",
      "createdAt": "2024-06-01T10:00:00.000Z"
    }
  ]
}
```

---

## 13. Upload

### POST /admin/upload

Upload a single image to Cloudinary.

**Auth required:** Yes (`content_manager+`)

**Request:** `multipart/form-data`

| Field | Type | Description |
|---|---|---|
| `file` | File | Image file (JPG, PNG, WebP — max 10 MB) |
| `folder` | string | Optional subfolder in Cloudinary (e.g. `products`) |

**Response `200 OK`:**
```json
{
  "url": "https://res.cloudinary.com/dupzif1h3/image/upload/v.../royal-shine/products/abc123.jpg",
  "publicId": "royal-shine/products/abc123",
  "width": 1200,
  "height": 1200,
  "format": "jpg"
}
```

**Errors:**
- `400` — no file attached or unsupported format
- `413` — file exceeds 10 MB limit

---

## Common Error Response Format

```json
{
  "statusCode": 404,
  "message": "Product not found",
  "error": "Not Found",
  "timestamp": "2024-06-01T10:00:00.000Z",
  "path": "/api/v1/products/nonexistent-slug"
}
```

## Pagination Meta (all list endpoints)

```json
{
  "meta": {
    "total": 245,
    "page": 1,
    "limit": 20,
    "totalPages": 13
  }
}
```
