# Invoice & Notifications System
This project is a implementation of an invoice management system built in **Domain-Driven Design**.

## Main Features
- Create invoices (`Draft` status) with or w/o product lines
- Send invoices to customers (email notification)
- Update invoice Status (`Draft -> Sending -> SentToClient`)

## Invoice Structure
- `id` - UUID (auto-generated)
- `customer_name`
- `customer_email`
- `status` - `draft|sending|sent-to-client`
- `product_lines[]`
    - `product_name`
    - `quantity`
    - `price`
    - `total_unit_price = quantity x price`

## Status Workflow
- **Draft** - newly created invoice, editable
- **Sending** - invoice is being sent
- **SentToClient** - invoice was sent via email

## Api Endpoints
`POST /invoices`

Create new invoice.

Request body: (example)
```json
{
  "customer_name": "John Doe",
  "customer_email": "john.doe@example.com",
  "product_lines": [
    {
      "product_name": "Laptop",
      "quantity": 1,
      "price": 3500
    },
    {
      "product_name": "Wireless Mouse",
      "quantity": 2,
      "price": 120
    }
  ]
}
```

`GET /invoices/{id}`
Retrieve invoice details.

Response:
```json
{
  "id": "uuid",
  "customer_name": "John Doe",
  "customer_email": "john@example.com",
  "status": "draft",
  "product_lines": [...],
  "total_price": 5200
}
```

`POST /invoices/{id}/send`
Send an invoice (email to the customer)

Response: JSON invoice (status `sent-to-client`).

## Notifications
- Notifications are sent using **NotificationFacade**:
    - DummyDriver - SES Amazon implemented
- `ResourceDeliveredEvent` may be emitted by a driver and consumed by invoice module.

## Technical Details
- Language: PHP 8.1+, Laravel 10
- Database: Eloquent ORM + migrations
- UUIDs: Generated with `Str::uuid()`
- Queues: Laravel Queue (database/redis)
- Worker: `php artisan queue:work`

## Database Schema
- invoices
    - id (uuid, PK)
    - customer_name
    - customer_email
    - status
- invoice_product_lines
    - id (uuid, PK)
    - invoice_id (FK)
    - name
    - quantity
    - price

## Setup instructions
1. Install Dependencies
```bash
composer install
```

2. Run Migrations
```bash
php artisan migrate
```
3. Configure .env's
```bash
MAIL_MAILER=smtp
MAIL_HOST=<aws smtp endpoint>
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=<aws login>
MAIL_PASSWORD=<aws password>
MAIL_FROM_ADDRESS=<from which mail emails will be sent>
MAIL_FROM_NAME="Invoices" 
```

## Summary
This project demonstrates:
- `DDD structure` within Laravel (Domain/Application/Infrastructure/Presentation)
- Invoice creation, product line handling, and price calculation
- Sending invoices and updating statuses
- Integration with a notification system through Mailer (AWS SES)
- Asynchronous processing with Laravel Queues and Events

