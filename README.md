# 🏨 Palmwave Resort & Suites - Hotel Room Booking System

A complete, production-ready hotel room booking system with client-facing website, integrated payment processing (GCash & Maya), and a comprehensive admin panel for managing rooms, bookings, customers, and payments.

## 🎯 What You Get

### ✅ Fully Functional Booking System
- Browse rooms by category with interactive navbar dropdown
- Detailed room information with photos and amenities
- Complete booking form with automatic price calculation
- Two payment methods: **GCash** and **Maya**
- Instant booking confirmation

### ✅ Complete Admin Panel
- Dashboard with real-time statistics
- **Room Management:** Create, Read, Update, Delete rooms
- Edit room photos and descriptions
- **Booking Tracking:** Monitor all customer reservations
- **Customer Management:** View profiles and booking history
- **Payment Monitoring:** Track all transactions

### ✅ Professional Features
- Responsive design (mobile-friendly)
- Database-driven architecture
- Secure admin authentication
- Real-time data updates
- Payment transaction tracking
- Customer information management

---

## 🚀 Quick Start (5 Minutes)

### Step 1: Setup Database
1. Open `http://localhost/phpmyadmin`
2. Copy entire `database.sql` file contents
3. Paste into SQL tab and click Go

### Step 2: Access the System
```
Customer Website: http://localhost/HOTEL%20ROOM%20BOOKING%20SYSTEM/
Admin Panel:      http://localhost/HOTEL%20ROOM%20BOOKING%20SYSTEM/admin/admin_login.php
```

### Step 3: Login to Admin
```
Username: admin
Password: admin123
```

**That's it! You're ready to go.** ✨

---

## 📚 Documentation

Choose what you need:

| Document | Purpose |
|----------|---------|
| **QUICK_START.md** | 5-minute setup |
| **SETUP_GUIDE.md** | Complete guide |
| **API_REFERENCE.md** | API details |
| **FEATURES_CHECKLIST.md** | Testing guide |
| **FILES_REFERENCE.md** | File structure |

---

## 🎨 Key Features

### 🌐 Client Website
- Browse all rooms
- Filter by category using dropdown
- View detailed room information
- Make bookings with automatic price calculation
- Pay with GCash or Maya
- Get instant confirmation

### 👨‍💼 Admin Panel  
- Add/Edit/Delete rooms
- Update room photos and descriptions
- View all bookings and customer info
- Track payment transactions
- Monitor revenue

---

## 📋 Room Categories

| Category | Rooms | Price |
|----------|-------|-------|
| Standard | Coral Breeze, Seabreeze Comfort | ₱3,500 |
| Deluxe | Azure Horizon, Golden Palm | ₱5,500 |
| Suites | Ocean Pearl, Sapphire Wave, Sunset Mirage | ₱8,500 |
| Villas | Palm Royale, Lagoon Crest | ₱12,000 |
| Penthouse | Royal Tides Oceanfront | ₱20,000 |

---

## 💳 Payment Methods

### GCash
- Merchant: ****

### Maya
- Merchant: ****

---

## 📋 Setup Checklist

- [ ] Copy all files to `C:\xampp\htdocs\HOTEL ROOM BOOKING SYSTEM\`
- [ ] Open `http://localhost/phpmyadmin`
- [ ] Create database and run `database.sql`
- [ ] Visit the website
- [ ] Test customer booking
- [ ] Login to admin panel
- [ ] Test room management
- [ ] Change admin password

---

## 🔐 Default Admin Credentials

```
URL:      http://localhost/HOTEL%20ROOM%20BOOKING%20SYSTEM/admin/admin_login.php
Username: admin
Password: admin123
```

⚠️ **Change these immediately after setup!**

---

## 🚦 Get Started

1. **For Quick Setup:** Read `QUICK_START.md`
2. **For Full Details:** Read `SETUP_GUIDE.md`
3. **For API Info:** Read `API_REFERENCE.md`
4. **For Testing:** Read `FEATURES_CHECKLIST.md`

---

## 📊 Project Stats

- 25+ Files
- 5 HTML Pages
- 7 Admin Pages
- 6 API Endpoints
- 6 Database Tables
- 3000+ Lines of Code
- 10 Pre-loaded Rooms

---

## 📞 Support

**Palmwave Resort & Suites**
- Email: PalmWaveResort&Suites@gmail.com
- Phone: +41 22 345 67 88
- Address: 322 Main Street, PH, CA 94559

---

## 📄 License

© 2026 Palmwave Resort & Suites. All rights reserved.

---

**Version:** 1.0.0 | **Status:** ✅ Production Ready
  name VARCHAR(255),
  type VARCHAR(100),
  capacity INT,
  description TEXT,
  image VARCHAR(255)
);

CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  room_id INT NOT NULL,
  checkin DATE NOT NULL,
  checkout DATE NOT NULL,
  guest_count INT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (room_id) REFERENCES rooms(id)
);
```

Then insert sample rows or your own data.

## Notes

- The React app now includes a full reservation card with:
  - room selection
  - check-in / check-out date inputs
  - guest count
  - reservation submission
  - booked dates display for the selected room
- Past check-in dates are disabled.
- Submissions are blocked when the selected range overlaps existing bookings.
- If your MySQL tables are missing, the server returns sample room and booking data so the UI still works.
