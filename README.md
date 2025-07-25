# Product Management System

## Overview

Build a RESTful API for a product management system where authenticated users can:

- Create and manage their own products
- Associate products with predefined attributes
- Track and view a full version history of product updates
- Receive email notifications on updates
- Organize products using a deeply nested category tree

---

## Technical Requirements

### 1️⃣ Authentication & Authorization

- Users must only access and manage their own products and related data.
- Use Laravel Policies for enforcing access control.
- All routes should be authenticated using a method like Laravel Sanctum or Passport.

---

### 2️⃣ Product Management

Each product must include the following fields:

- `user_id` – product owner
- `title` – required, string
- `content` – optional, text
- `price` – required, decimal
- `stock` – required, integer
- `category_id` – required, foreign key to a category

#### Users must be able to:

- Create a product
- Update a product
- View product details
- List all of their products, sorted by:
    - Availability (`stock > 0`)
    - Then price (ascending)

> Product deletion is not required.

---

### 3️⃣ Product Attributes

Products can be associated with predefined attributes, using a many-to-many relationship where each attribute has a value assigned per product.

#### Requirements:

- Define an `attributes` table with exactly 5 attributes:
    - `size`, `length`, `color`, `material`, `brand`
- Users must be able to attach one or more of these to a product, with a specific value (e.g., `color: red`, `brand: Samsung`) during create and update.
- You do not need to implement APIs to manage the attributes — they are assumed to be seeded.

---

### 4️⃣ Version History System

Each time a product is updated, a version record must be created and saved.

#### Required Endpoints:

- `GET /api/products/{id}/history`  
  Returns all version records for a product:
    - Unique version ID
    - Timestamp of update
    - User who made the change

- `GET /api/products/{id}/history/{versionId}`  
  Returns details for a specific update:
    - Fields that changed
    - Old and new values
    - User
    - Timestamp

> You are free to design the versioning mechanism — focus on scalability and clarity.

---

### 5️⃣ Email Notification

When a product is updated, an email notification must be sent to the product owner.

- Use Laravel’s native mail system — no need for external services.
- Ensure the logic is triggered and includes the update details.

---

### 6️⃣ Category Management System (Tree-Based)

Products belong to categories, which are organized into a deeply nested tree structure with unlimited levels.

#### Requirements:

You must implement a category system that supports:

- Unlimited depth (via parent-child relationships)
- Efficient querying of:
    - Immediate children
    - All descendants (deep-nested)
    - All ancestors (from current to root)
- Tree restructuring (e.g., move a category to a new parent)
- Adding new categories at any level

> Your implementation will be tested against a large dataset (10,000+ categories).  
> Avoid traditional nested set models unless performance is carefully optimized.  
> Focus on query efficiency and structural flexibility.
> Using the latest SQL query structures is encouraged.

#### Required Endpoints:

- `GET /api/categories/{id}/children` – Immediate children
- `GET /api/categories/{id}/descendants` – All nested descendants (flat, sorted by depth)
- `GET /api/categories/{id}/ancestors` – All ancestors up to the root
- `POST /api/categories` – Create a category with optional `parent_id`
- `PATCH /api/categories/{id}/move` – Move a category (and subtree) to a new parent

---

## Evaluation Criteria

### ✅ Mandatory

- Clean, modular Laravel code
- Proper use of:
    - Eloquent relationships
    - Form Requests
    - Policies for access control
    - Events/Observers where appropriate
- Secure RESTful API
- Scalable and optimized category tree implementation
- Working product version history
- Triggered email notifications

---

### 💡 Bonus Points

- Clear and consistent API responses (e.g., API Resources)
- Unit or feature test coverage
- API documentation (Postman or Swagger)  
