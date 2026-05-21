-- Create Database
CREATE DATABASE IF NOT EXISTS hotel_booking_system;
USE hotel_booking_system;

-- Admin Users Table
CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Room Categories Table
CREATE TABLE IF NOT EXISTS room_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Rooms Table
CREATE TABLE IF NOT EXISTS rooms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    capacity INT NOT NULL,
    photo_url VARCHAR(255),
    photo_url_2 VARCHAR(255),
    photo_url_3 VARCHAR(255),
    photo_url_4 VARCHAR(255),
    amenities TEXT,
    available INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES room_categories(id)
);

-- Customers Table
CREATE TABLE IF NOT EXISTS customers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings Table
CREATE TABLE IF NOT EXISTS bookings (
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
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

-- Payment Transactions Table
CREATE TABLE IF NOT EXISTS payment_transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    transaction_id VARCHAR(100) UNIQUE,
    status VARCHAR(50) DEFAULT 'pending',
    reference_number VARCHAR(100),
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id)
);

-- Insert Default Admin User (username: admin, password: admin123)
INSERT INTO admins (username, password, email) VALUES 
('admin', '$2y$10$YourHashedPasswordHere', 'admin@palmwave.com');

-- Insert Room Categories
INSERT INTO room_categories (name, description) VALUES 
('Standard Rooms', 'Comfortable rooms perfect for budget-conscious travelers'),
('Deluxe Rooms', 'Spacious rooms with premium amenities'),
('Suites', 'Luxurious suites for ultimate comfort'),
('Villas', 'Private villas with exclusive features'),
('Penthouse', 'Premium penthouses with breathtaking views');

-- Insert Sample Rooms
INSERT INTO rooms (category_id, name, description, price, capacity, photo_url, amenities) VALUES 
(1, 'Coral Breeze Room', 'Charming room with ocean breeze and modern amenities', 3500.00, 2, './assets/coral.png', 'Wi-Fi, Air-conditioning, TV, Private Bathroom'),
(1, 'Seabreeze Comfort Room', 'Comfortable seaside room with relaxing atmosphere', 3500.00, 2, './assets/seabreeze.png', 'Wi-Fi, Air-conditioning, TV, Private Bathroom'),
(2, 'Azure Horizon Deluxe', 'Premium deluxe room with stunning horizon views', 5500.00, 3, './assets/Azure.png', 'Wi-Fi, Air-conditioning, Flat-screen TV, Bathrobe, Premium Toiletries'),
(2, 'Golden Palm Deluxe Room', 'Luxury deluxe room with palm garden view', 5500.00, 3, './assets/Golden.png', 'Wi-Fi, Air-conditioning, Flat-screen TV, Bathrobe, Premium Toiletries'),
(3, 'Ocean Pearl Executive Suite', 'Elegant suite with ocean pearl design elements', 8500.00, 4, './assets/Ocean.png', 'Wi-Fi, Air-conditioning, Living Area, Kitchenette, Premium Bathroom'),
(3, 'Sapphire Wave Suite', 'Luxurious suite with wave-inspired décor', 8500.00, 4, './assets/Sapphire.png', 'Wi-Fi, Air-conditioning, Living Area, Kitchenette, Premium Bathroom'),
(3, 'Sunset Mirage Suite', 'Spectacular suite with sunset views', 8500.00, 4, './assets/Sunset Mirage Suite.png', 'Wi-Fi, Air-conditioning, Living Area, Kitchenette, Premium Bathroom'),
(4, 'Palm Royale Villa', 'Exclusive villa with royal amenities', 12000.00, 6, './assets/Palm Royale Villa.png', 'Private Pool, Wi-Fi, Air-conditioning, Living Area, Full Kitchen, Terrace'),
(4, 'Lagoon Crest Villa', 'Private villa overlooking lagoon', 12000.00, 6, './assets/Lagoon Crest Villa.png', 'Private Pool, Wi-Fi, Air-conditioning, Living Area, Full Kitchen, Terrace'),
(5, 'Royal Tides Oceanfront Penthouse', 'Ultimate luxury penthouse with oceanfront location', 20000.00, 8, './assets/Royal Tides Oceanfront Penthouse.png', 'Private Infinity Pool, Concierge, Wi-Fi, Living Area, Full Kitchen, Spa');
