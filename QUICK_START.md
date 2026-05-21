# Quick Start Guide - Palmwave Hotel Booking System

## 5-Minute Setup

### Step 1: Copy Files (1 minute)
- Ensure all files are in `C:\xampp\htdocs\HOTEL ROOM BOOKING SYSTEM\`
- Room images should be in the same directory

### Step 2: Create Database (2 minutes)

1. Go to `http://localhost/phpmyadmin`
2. Click "New" or create a database named `hotel_booking_system`
3. Open the "SQL" tab
4. Open `database.sql` from your file explorer
5. Copy and paste all content into the SQL editor
6. Click "Go"

✅ Database is ready!

### Step 3: Start Using

**For Customers:**
```
http://localhost/HOTEL%20ROOM%20BOOKING%20SYSTEM/index.html
```

**For Admins:**
```
http://localhost/HOTEL%20ROOM%20BOOKING%20SYSTEM/admin/admin_login.php
Username: admin
Password: admin123
```

---

## Main Features Quick Links

### Browse Rooms
1. Click "Rooms" in navbar
2. Use dropdown or filter buttons to select category
3. Click "View Details & Book" on any room

### Book a Room
1. Select dates (check-in and check-out)
2. Enter number of guests
3. Fill your contact information
4. Select payment method (GCash or Maya)
5. Complete payment verification

### Admin Panel
1. **Dashboard**: Overview and statistics
2. **Manage Rooms**: Add, edit, delete rooms
3. **Manage Bookings**: Track customer reservations
4. **Customers**: View customer details
5. **Payments**: Monitor transactions

---

## What You Can Do Now

- ✅ Customers can browse and filter rooms by category
- ✅ Customers can make reservations with payment options
- ✅ Admin can manage room inventory (Add/Edit/Delete)
- ✅ Admin can track bookings and payments
- ✅ Edit room descriptions and photos
- ✅ View booking history and customer information

---

## Common Tasks

### Add a New Room
1. Login to Admin Panel
2. Go to "Manage Rooms"
3. Click "+ Add New Room"
4. Fill in details and click "Create Room"

### Update Room Information
1. Login to Admin Panel
2. Go to "Manage Rooms"
3. Click "Edit" on the room
4. Modify details (price, description, photo, amenities)
5. Click "Update Room"

### View Bookings
1. Login to Admin Panel
2. Go to "Manage Bookings"
3. See all bookings with customer and payment info

### Change Room Photo
1. Edit the room (see above)
2. Update "Photo File Name" field
3. Place the image file in main directory
4. Save changes

---

## File Organization

```
HOTEL ROOM BOOKING SYSTEM/
├── [HTML files]
│   ├── index.html
│   ├── rooms.html
│   ├── room_details.html
│   ├── payment.html
│   └── payment_success.html
│
├── [PHP Configuration]
│   ├── config.php
│   └── database.sql
│
├── [API Endpoints]
│   └── api/
│       ├── get_rooms.php
│       ├── get_categories.php
│       ├── get_room_details.php
│       ├── create_booking.php
│       ├── process_payment.php
│       └── confirm_payment.php
│
├── [Admin Panel]
│   └── admin/
│       ├── admin_login.php
│       ├── admin_dashboard.php
│       ├── manage_rooms.php
│       ├── manage_bookings.php
│       ├── manage_customers.php
│       ├── manage_payments.php
│       └── logout.php
│
├── [Documentation]
│   ├── SETUP_GUIDE.md
│   ├── QUICK_START.md (this file)
│   └── API_REFERENCE.md
│
├── [Images]
│   ├── [room photos]
│   ├── coral.png
│   ├── seabreeze.png
│   ├── Azure.png
│   └── [other images]
│
├── [Folders]
│   ├── css/
│   ├── js/
│   ├── assets/
│   └── pages/
```

---

## Default Room Categories

| Category | Rooms |
|----------|-------|
| **Standard Rooms** | Coral Breeze, Seabreeze Comfort |
| **Deluxe Rooms** | Azure Horizon, Golden Palm |
| **Suites** | Ocean Pearl, Sapphire Wave, Sunset Mirage |
| **Villas** | Palm Royale, Lagoon Crest |
| **Penthouse** | Royal Tides Oceanfront |

---

## Payment Methods Available

### GCash
- For users with GCash mobile wallet
- Merchant: 0917-123-4567
- Fast and secure transaction

### Maya
- For users with Maya digital wallet
- Merchant: payments@palmwave.com
- ACH transfer available

---

## Need Help?

1. **Database Issue?** Check if MySQL is running in XAMPP
2. **Login Problem?** Use admin/admin123 (case-sensitive)
3. **Page Not Loading?** Ensure files are in correct directory
4. **Image Not Showing?** Check filename matches exactly, files in main directory

---

**You're all set! Start booking rooms now!**

🏨 Website: `http://localhost/HOTEL%20ROOM%20BOOKING%20SYSTEM/`  
🔐 Admin: `http://localhost/HOTEL%20ROOM%20BOOKING%20SYSTEM/admin/admin_login.php`
