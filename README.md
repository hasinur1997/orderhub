# OrderHub

OrderHub is a lightweight order management API built with Laravel. It provides product and customer management, order creation with stock validation, and basic reporting-friendly pagination. The API is intentionally minimal and designed to be extended.

**Key Features**
- Products CRUD with prefix search and pagination
- Customers CRUD with pagination
- Orders creation with stock checks and automatic totals
- Order line items stored with unit price and line totals
- SQLite-friendly tests and GitHub Actions CI

**Tech Stack**
- PHP 8.2+ / Laravel 12
- Vite + Tailwind CSS for the frontend build pipeline
- SQLite (tests) or any Laravel-supported database (development/production)

**Requirements**
- PHP 8.2+
- Composer
- Node.js 20+
- A database supported by Laravel (MySQL/Postgres/SQLite)

**Setup**
1. `composer install`
2. `cp .env.example .env`
3. `php artisan key:generate`
4. Update `.env` with your database credentials
5. `php artisan migrate`

**Seeding (Important)**
The default `DatabaseSeeder` is intentionally large. It creates:
- 3,000,000 products
- 1,000 customers
- 3,000,000 demo orders with items

If you do not want a large dataset, run a specific seeder instead of `db:seed`, or reduce the counts in the seeders before running them.

Examples:
- `php artisan db:seed --class=CustomerSeeder`
- `php artisan db:seed --class=ProductSeeder`
- `php artisan db:seed --class=DemoOrderSeeder`

**Run Locally**
- `composer dev` (runs Laravel server, queue, logs, and Vite)
- `php artisan serve` (server only)
- `npm run dev` (Vite only)

**Testing**
- `composer test`

Tests use SQLite in-memory by default (see `phpunit.xml`).

**API**
Base path: `/api`

| Resource | Method | Endpoint | Notes |
| --- | --- | --- | --- |
| Products | GET | `/products` | Paginated. Supports `?search=prefix` on `name`. |
| Products | POST | `/products` | Creates product. |
| Products | GET | `/products/{id}` | Returns a single product. |
| Products | PUT/PATCH | `/products/{id}` | Updates a product. |
| Products | DELETE | `/products/{id}` | Deletes a product. |
| Customers | GET | `/customers` | Paginated. |
| Customers | POST | `/customers` | Creates a customer. |
| Customers | GET | `/customers/{id}` | Returns a single customer. |
| Customers | PUT/PATCH | `/customers/{id}` | Updates a customer. |
| Customers | DELETE | `/customers/{id}` | Deletes a customer. |
| Orders | GET | `/orders` | Paginated with `customer` and `items.product` loaded. |
| Orders | POST | `/orders` | Creates an order and adjusts stock. |
| Orders | GET | `/orders/{id}` | Returns a single order with relations. |
| Orders | PUT/PATCH | `/orders/{id}` | Returns `501 Not Implemented`. |
| Orders | DELETE | `/orders/{id}` | Returns `405 Not Allowed`. |

**Product Fields**
- `name` (string, required)
- `sku` (string, required, unique)
- `price` (integer, required, smallest currency unit)
- `stock_qty` (integer, optional, defaults to 0)

**Customer Fields**
- `name` (string, required)
- `email` (string, optional, unique)
- `phone` (string, optional)

**Order Create Payload**
```json
{
  "customer_id": 1,
  "items": [
    { "product_id": 10, "qty": 2 },
    { "product_id": 12, "qty": 1 }
  ]
}
```

**Order Behavior**
- Validates product existence and available stock.
- Uses the current product price for line totals.
- Decrements product `stock_qty` on successful creation.
- Stores totals as unsigned integers (smallest currency unit).

**Pagination Response (Products)**
The products index endpoint returns a simple JSON structure:

```json
{
  "data": [
    {
      "id": 1,
      "name": "Example",
      "sku": "EX-001",
      "price": 100,
      "stock_qty": 5
    }
  ],
  "links": {
    "first": "...",
    "last": "...",
    "prev": null,
    "next": "..."
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 3,
    "path": "...",
    "per_page": 20,
    "to": 20,
    "total": 50
  }
}
```

**CI (GitHub Actions)**
- `CI` workflow runs tests, Pint lint, and PHPStan analysis on push and PRs.
- `Security` workflow runs Composer and NPM audits weekly and on demand.

**Notes**
- The order update and delete endpoints are placeholders.
- Authentication is not enabled for API routes by default.

## License
MIT
