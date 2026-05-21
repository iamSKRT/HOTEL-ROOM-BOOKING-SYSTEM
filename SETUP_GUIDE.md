# Palmwave Resort & Suites - Hotel Room Booking System

A complete hotel room booking system with client-facing website, payment integration (GCash & Maya), and a comprehensive admin panel for managing bookings, rooms, customers, and payments.

## Features

### Client-Facing Website
- ✅ **Room Browsing**: Browse rooms by categories (Standard, Deluxe, Suites, Villas, Penthouse)
- ✅ **Room Details**: Detailed room information with description, amenities, and pricing
- ✅ **Interactive Navbar**: Dropdown menu for room categories in navigation bar
- ✅ **Booking System**: Complete booking form with date selection and guest count
- ✅ **Payment Integration**: Support for GCash and Maya payment methods
- ✅ **Booking Confirmation**: Confirmation page with booking details
- ✅ **Responsive Design**: Mobile-friendly interface

### Admin Panel
- ✅ **Dashboard**: Overview of total bookings, customers, rooms, and recent activities
- ✅ **Booking Management**: Track all customer bookings with status and payment info
- ✅ **Room Management**: Complete CRUD (Create, Read, Update, Delete) operations for rooms
- ✅ **Photo & Description Editing**: Update room photos and descriptions
- ✅ **Customer Management**: View customer information and booking history
- ✅ **Payment Tracking**: Monitor all payment transactions
- ✅ **Admin Login**: Secure authentication system

## Project Structure

```
HOTEL ROOM BOOKING SYSTEM/
├── index.html                 # Main homepage
├── rooms.html                 # Room listing page
├── room_details.html          # Individual room details & booking form
├── payment.html               # Payment page (GCash & Maya)
├── payment_success.html       # Booking confirmation page
├── config.php                 # Database configuration
├── database.sql               # Database schema and sample data
│
├── api/                       # API endpoints
│   ├── get_rooms.php          # Fetch rooms by category
│   ├── get_categories.php     # Fetch room categories
│   ├── get_room_details.php   # Fetch single room details
│   ├── create_booking.php     # Create new booking
│   ├── process_payment.php    # Process payment
│   └── confirm_payment.php    # Confirm payment completion
│
├── admin/                     # Admin panel
│   ├── admin_login.php        # Admin login page
│   ├── admin_dashboard.php    # Admin dashboard
│   ├── manage_rooms.php       # Room CRUD operations
│   ├── manage_bookings.php    # View/manage bookings
│   ├── manage_customers.php   # Customer management
│   ├── manage_payments.php    # Payment tracking
│   └── logout.php             # Logout functionality
│
├── css/                       # Custom stylesheets (optional)
├── js/                        # Custom JavaScript (optional)
└── assets/                    # Images and other assets
```

## Installation & Setup

### Step 1: Prerequisites
- **XAMPP** (or any local server with PHP and MySQL support)
- **PHP** 7.4 or higher
- **MySQL** 5.7 or higher
- **Web Browser** (Chrome, Firefox, Edge, Safari)

### Step 2: Database Setup

1. Open **phpMyAdmin** by navigating to `http://localhost/phpmyadmin`

2. Copy and paste the contents of `database.sql` into the SQL tab

3. Click "Go" to execute and create the database and tables

**Database Created:**
- `hotel_booking_system` database with the following tables:
  - `admins` - Admin user accounts
  - `room_categories` - Room type categories
  - `rooms` - Room inventory
  - `customers` - Customer information
  - `bookings` - Booking records
  - `payment_transactions` - Payment tracking

### Step 3: File Placement

1. Place all files in: `C:\xampp\htdocs\HOTEL ROOM BOOKING SYSTEM\`

2. Ensure all image files (room photos) are in the same directory as HTML files

### Step 4: Access the System

**Client Website:**
- Home: `http://localhost/HOTEL%20ROOM%20BOOKING%20SYSTEM/index.html`
- Rooms: `http://localhost/HOTEL%20ROOM%20BOOKING%20SYSTEM/rooms.html`

**Admin Panel:**
- Login: `http://localhost/HOTEL%20ROOM%20BOOKING%20SYSTEM/admin/admin_login.php`

### Step 5: Default Admin Credentials

```
Username: admin
Password: admin123
```

**⚠️ Important:** Change these credentials immediately after first login!

## Usage Guide

### For Customers

#### Booking a Room

1. **Browse Rooms**
   - Click on "Rooms" in the navbar
   - Use the dropdown menu to filter by category (Standard, Deluxe, Suites, Villas, Penthouse)
   - Or filter using the category buttons below the navbar

2. **View Room Details**
   - Click "View Details & Book" on any room card
   - See full room description, amenities, and pricing
   - Check the price per night calculation

3. **Fill Booking Form**
   - Enter your full name, email, and phone number
   - Select check-in and check-out dates
   - Specify number of guests
   - Total price automatically calculates based on number of nights

4. **Select Payment Method**
   - Choose between **GCash** or **Maya**
   - View payment instructions for selected method
   - Enter your payment reference number
   - Confirm payment

5. **Booking Confirmation**
   - Receive confirmation page with booking ID
   - Confirmation email sent to your registered email
   - Booking details saved in admin panel

### For Admins

#### Dashboard
- Overview of all bookings, customers, and rooms
- Quick statistics and recent bookings
- Access to all management features

#### Room Management (Create, Read, Update, Delete)

**Add New Room:**
1. Go to "Manage Rooms"
2. Click "+ Add New Room"
3. Fill in:
   - Category (Standard, Deluxe, Suites, Villas, Penthouse)
   - Room name
   - Description
   - Price per night
   - Guest capacity
   - Photo filename (e.g., room.png)
   - Amenities (comma-separated)
4. Click "Create Room"

**Edit Room:**
1. Go to "Manage Rooms"
2. Click "Edit" button on the room
3. Modify any information (description, price, photo, amenities)
4. Click "Update Room"

**Delete Room:**
1. Go to "Manage Rooms"
2. Click "Delete" button
3. Confirm deletion

**Update Room Photo:**
- Update the photo filename in the edit form
- Ensure the image file is in the main directory

#### Manage Bookings
- View all customer bookings with status
- Track check-in/check-out dates
- Monitor payment status (paid/unpaid)
- View booking confirmation status

#### Customer Management
- View all registered customers
- See total bookings per customer
- Track customer spending
- Access customer contact information

#### Payment Management
- Track all payment transactions
- View payment method used (GCash/Maya)
- Monitor transaction status
- View total revenue collected

## Room Categories & Pre-loaded Rooms

The system comes with these room categories:

### Standard Rooms
- Coral Breeze Room - ₱3,500/night (2 guests)
- Seabreeze Comfort Room - ₱3,500/night (2 guests)

### Deluxe Rooms
- Azure Horizon Deluxe - ₱5,500/night (3 guests)
- Golden Palm Deluxe Room - ₱5,500/night (3 guests)

### Suites
- Ocean Pearl Executive Suite - ₱8,500/night (4 guests)
- Sapphire Wave Suite - ₱8,500/night (4 guests)
- Sunset Mirage Suite - ₱8,500/night (4 guests)

### Villas
- Palm Royale Villa - ₱12,000/night (6 guests)
- Lagoon Crest Villa - ₱12,000/night (6 guests)

### Penthouse
- Royal Tides Oceanfront Penthouse - ₱20,000/night (8 guests)

## Payment Methods

### GCash Integration
- **Merchant Number**: 0917-123-4567
- **Instructions**: Users open GCash app, select "Send Money to Business", enter amount and reference number
- **Reference Number**: Booking ID for tracking

### Maya Integration
- **Merchant Account**: payments@palmwave.com
- **Instructions**: Users open Maya app, select "Send Money", enter amount
- **Transaction ID**: Saved for verification

**Note:** In production, integrate with official GCash and Maya APIs for real-time payment processing.

## Navbar Dropdown Menu

The navbar features an interactive dropdown menu:

```
Rooms ▼
├── Standard Rooms
├── Deluxe Rooms
├── Suites
├── Villas
├── Penthouse
└── View All Rooms
```

- Hover over "Rooms" to see category options
- Click any category to filter rooms
- Responsive on mobile devices

## Database Schema

### admins
```sql
id (INT), username (VARCHAR), password (VARCHAR), email (VARCHAR), created_at (TIMESTAMP)
```

### room_categories
```sql
id (INT), name (VARCHAR UNIQUE), description (TEXT), created_at (TIMESTAMP)
```

### rooms
```sql
id (INT), category_id (INT), name (VARCHAR), description (TEXT), price (DECIMAL), 
capacity (INT), photo_url (VARCHAR), amenities (TEXT), available (INT), created_at (TIMESTAMP)
```

### customers
```sql
id (INT), name (VARCHAR), email (VARCHAR UNIQUE), phone (VARCHAR), address (TEXT), created_at (TIMESTAMP)
```

### bookings
```sql
id (INT), customer_id (INT), room_id (INT), check_in_date (DATE), check_out_date (DATE), 
number_of_guests (INT), total_price (DECIMAL), status (VARCHAR), payment_method (VARCHAR), 
payment_status (VARCHAR), booking_date (TIMESTAMP)
```

### payment_transactions
```sql
id (INT), booking_id (INT), amount (DECIMAL), payment_method (VARCHAR), transaction_id (VARCHAR UNIQUE),
status (VARCHAR), reference_number (VARCHAR), payment_date (TIMESTAMP)
```

## Troubleshooting

### Database Connection Error
- Check if MySQL is running in XAMPP
- Verify database credentials in `config.php`
- Ensure `hotel_booking_system` database exists

### Admin Login Issues
- Default credentials: username = `admin`, password = `admin123`
- Check if admin user exists in database
- Clear browser cache and try again

### Page Not Loading
- Ensure PHP is enabled in XAMPP
- Check file paths match your installation directory
- Verify all files are in correct folders

### Room Images Not Displaying
- Ensure image files are in the main `HOTEL ROOM BOOKING SYSTEM` directory
- Update photo_url in database to match filename
- Verify image format (PNG, JPG, GIF)

## Security Recommendations

For production deployment:

1. **Change Default Admin Password**
   - Update admin password in database after setup

2. **Use Password Hashing**
   - Implement `password_hash()` and `password_verify()` functions

3. **HTTPS/SSL**
   - Deploy with SSL certificate for secure transactions

4. **Input Validation**
   - Implement stricter input validation and sanitization

5. **Database**
   - Use prepared statements (already implemented)
   - Add database backups
   - Restrict database access

6. **Payment Integration**
   - Integrate with real GCash and Maya APIs
   - Implement webhook handling for payment verification
   - Use proper encryption for sensitive data

## Future Enhancements

- [ ] Email notifications for bookings
- [ ] SMS notifications via Twilio
- [ ] Real GCash/Maya API integration
- [ ] Online payment gateway (PayPal, Stripe)
- [ ] Customer login area
- [ ] Booking cancellation system
- [ ] Room availability calendar
- [ ] Rating and review system
- [ ] Special offers and promotions
- [ ] Reports and analytics

## Support & Maintenance

For technical support or questions:
- Contact: PalmWaveResort&Suites@gmail.com
- Phone: +41 22 345 67 88
- Address: 322 Main Street, PH, CA 94559

## License

This system is proprietary to Palmwave Resort & Suites. All rights reserved.

---

**Version:** 1.0.0  
**Last Updated:** 2025  
**Developer:** Hotel Management System Team
