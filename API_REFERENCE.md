# API Reference - Palmwave Hotel Booking System

This document provides detailed information about all API endpoints used in the booking system.

## Base URL
```
http://localhost/HOTEL%20ROOM%20BOOKING%20SYSTEM/api/
```

## Endpoints

### 1. Get All Rooms

**Endpoint:** `GET /get_rooms.php`

**Description:** Fetch all available rooms, optionally filtered by category

**Parameters:**
- `category_id` (optional): Filter by room category (1-5)

**Example Requests:**
```
GET /api/get_rooms.php                    # All rooms
GET /api/get_rooms.php?category_id=1      # Standard Rooms only
GET /api/get_rooms.php?category_id=3      # Suites only
```

**Response:**
```json
[
  {
    "id": 1,
    "category_id": 1,
    "name": "Coral Breeze Room",
    "description": "Charming room with ocean breeze...",
    "price": "3500.00",
    "capacity": 2,
    "photo_url": "coral.png",
    "amenities": "Wi-Fi, Air-conditioning, TV...",
    "available": 1,
    "category_name": "Standard Rooms"
  },
  ...
]
```

---

### 2. Get Room Categories

**Endpoint:** `GET /get_categories.php`

**Description:** Fetch all room categories

**Parameters:** None

**Example Request:**
```
GET /api/get_categories.php
```

**Response:**
```json
[
  {
    "id": 1,
    "name": "Standard Rooms",
    "description": "Comfortable rooms perfect for budget-conscious travelers"
  },
  {
    "id": 2,
    "name": "Deluxe Rooms",
    "description": "Spacious rooms with premium amenities"
  },
  ...
]
```

---

### 3. Get Room Details

**Endpoint:** `GET /get_room_details.php`

**Description:** Fetch detailed information about a specific room

**Parameters:**
- `id` (required): Room ID

**Example Request:**
```
GET /api/get_room_details.php?id=1
```

**Response:**
```json
{
  "id": 1,
  "category_id": 1,
  "name": "Coral Breeze Room",
  "description": "Charming room with ocean breeze and modern amenities",
  "price": "3500.00",
  "capacity": 2,
  "photo_url": "coral.png",
  "amenities": "Wi-Fi, Air-conditioning, TV, Private Bathroom",
  "available": 1,
  "created_at": "2025-01-15 10:30:00",
  "updated_at": "2025-01-15 10:30:00",
  "category_name": "Standard Rooms"
}
```

**Error Response:**
```json
{
  "error": "Room not found"
}
```

---

### 4. Create Booking

**Endpoint:** `POST /create_booking.php`

**Description:** Create a new booking reservation

**Request Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
  "customer_name": "John Doe",
  "customer_email": "john@example.com",
  "customer_phone": "09171234567",
  "room_id": 1,
  "check_in_date": "2025-05-15",
  "check_out_date": "2025-05-18",
  "num_guests": 2,
  "payment_method": "pending"
}
```

**Response (Success):**
```json
{
  "success": true,
  "message": "Booking created successfully",
  "booking_id": 1,
  "total_price": 10500.00
}
```

**Response (Error):**
```json
{
  "success": false,
  "message": "All fields are required"
}
```

**Validation Rules:**
- Customer name: Required, non-empty
- Email: Required, valid email format
- Phone: Required, non-empty
- Room ID: Required, must exist
- Check-in date: Required, valid date format
- Check-out date: Required, must be after check-in
- Number of guests: Required, > 0, <= room capacity
- Price calculation: Per-night price × number of nights

---

### 5. Process Payment

**Endpoint:** `POST /process_payment.php`

**Description:** Process payment for a booking

**Request Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
  "booking_id": 1,
  "payment_method": "gcash",
  "reference_number": "TRX123456789"
}
```

**Response (Success):**
```json
{
  "success": true,
  "message": "Payment initiated. Please proceed to complete payment.",
  "transaction_id": "TRX-1234567890-1",
  "booking_id": 1,
  "amount": 10500.00,
  "payment_method": "gcash",
  "redirect_url": "payment_success.php?transaction_id=TRX-1234567890-1"
}
```

**Response (Error):**
```json
{
  "success": false,
  "message": "Invalid payment details"
}
```

**Payment Methods:**
- `gcash` - GCash mobile wallet
- `maya` - Maya digital wallet

---

### 6. Confirm Payment

**Endpoint:** `GET /confirm_payment.php`

**Description:** Confirm payment completion and update booking status

**Parameters:**
- `booking_id` (required): Booking ID to confirm

**Example Request:**
```
GET /api/confirm_payment.php?booking_id=1
```

**Response (Success):**
```json
{
  "success": true,
  "message": "Payment confirmed successfully",
  "booking_id": 1
}
```

**Response (Error):**
```json
{
  "success": false,
  "message": "Error confirming payment"
}
```

---

## Database Tables

### customers
```sql
CREATE TABLE customers (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  phone VARCHAR(20) NOT NULL,
  address TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### bookings
```sql
CREATE TABLE bookings (
  id INT PRIMARY KEY AUTO_INCREMENT,
  customer_id INT NOT NULL,
  room_id INT NOT NULL,
  check_in_date DATE NOT NULL,
  check_out_date DATE NOT NULL,
  number_of_guests INT NOT NULL,
  total_price DECIMAL(10, 2) NOT NULL,
  status VARCHAR(50) DEFAULT 'pending',
  payment_method VARCHAR(50),
  payment_status VARCHAR(50) DEFAULT 'unpaid',
  booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (customer_id) REFERENCES customers(id),
  FOREIGN KEY (room_id) REFERENCES rooms(id)
);
```

### payment_transactions
```sql
CREATE TABLE payment_transactions (
  id INT PRIMARY KEY AUTO_INCREMENT,
  booking_id INT NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  payment_method VARCHAR(50) NOT NULL,
  transaction_id VARCHAR(100) UNIQUE,
  status VARCHAR(50) DEFAULT 'pending',
  reference_number VARCHAR(100),
  payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (booking_id) REFERENCES bookings(id)
);
```

---

## Status Values

### Booking Status
- `pending` - Awaiting payment confirmation
- `confirmed` - Payment received, booking confirmed
- `cancelled` - Booking cancelled

### Payment Status
- `unpaid` - Payment not received
- `paid` - Payment confirmed
- `failed` - Payment failed

### Transaction Status
- `pending` - Awaiting verification
- `completed` - Transaction successful
- `failed` - Transaction unsuccessful

---

## Error Handling

All endpoints return appropriate HTTP status codes:

- `200` - Success
- `400` - Bad request (missing/invalid parameters)
- `404` - Resource not found
- `500` - Server error

**Error Response Format:**
```json
{
  "success": false,
  "message": "Error description"
}
```

---

## Rate Limiting

No rate limiting is currently implemented. For production, implement:
- IP-based rate limiting
- User-based rate limiting
- DDoS protection

---

## Authentication

### Admin Authentication
Admin endpoints use PHP sessions. Check `isAdminLoggedIn()` before accessing.

**Login Method:**
```php
require_once '../config.php';
requireAdminLogin();  // Redirects to login if not authenticated
```

### Customer API
Customer-facing API endpoints do not require authentication.

---

## Example JavaScript Fetch Calls

### Get All Rooms
```javascript
fetch('api/get_rooms.php')
  .then(response => response.json())
  .then(data => console.log(data));
```

### Get Rooms by Category
```javascript
fetch('api/get_rooms.php?category_id=1')
  .then(response => response.json())
  .then(data => console.log(data));
```

### Create Booking
```javascript
const bookingData = {
  customer_name: "John Doe",
  customer_email: "john@example.com",
  customer_phone: "09171234567",
  room_id: 1,
  check_in_date: "2025-05-15",
  check_out_date: "2025-05-18",
  num_guests: 2,
  payment_method: "pending"
};

fetch('api/create_booking.php', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify(bookingData)
})
.then(response => response.json())
.then(data => console.log(data));
```

### Process Payment
```javascript
const paymentData = {
  booking_id: 1,
  payment_method: "gcash",
  reference_number: "TRX123456789"
};

fetch('api/process_payment.php', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify(paymentData)
})
.then(response => response.json())
.then(data => console.log(data));
```

---

## CORS Headers

All API endpoints include CORS headers:
```php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
```

---

## Future API Enhancements

- [ ] Authentication tokens (JWT)
- [ ] Webhook endpoints for payment providers
- [ ] Booking cancellation endpoint
- [ ] Customer API endpoints
- [ ] Availability checking
- [ ] Favorite rooms endpoint
- [ ] Review submission endpoint

---

**API Version:** 1.0.0  
**Last Updated:** 2025  
**Status:** Active
