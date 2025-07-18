-- Create the database
CREATE DATABASE IF NOT EXISTS airline_db;
USE airline_db;

-- Create airlines table
CREATE TABLE IF NOT EXISTS airlines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create flights table
CREATE TABLE IF NOT EXISTS flights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    airline_id INT NOT NULL,
    flight_number VARCHAR(20) NOT NULL,
    departure_city VARCHAR(100) NOT NULL,
    arrival_city VARCHAR(100) NOT NULL,
    departure_time DATETIME NOT NULL,
    arrival_time DATETIME NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    available_seats INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (airline_id) REFERENCES airlines(id)
);

-- Create bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    flight_id INT NOT NULL,
    booking_date DATETIME NOT NULL,
    total_passengers INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (flight_id) REFERENCES flights(id)
);

-- Create passengers table
CREATE TABLE IF NOT EXISTS passengers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id)
);
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    email VARCHAR(150) UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



-- Insert sample airlines
INSERT INTO airlines (name, code) VALUES
('Air India', 'AI'),
('IndiGo', '6E'),
('SpiceJet', 'SG'),
('Vistara', 'UK'),
('GoAir', 'G8');

-- Insert sample flights
INSERT INTO flights (
    airline_id, flight_number, departure_city, arrival_city,
    departure_time, arrival_time, price, available_seats
) VALUES
-- Original Domestic Flights
(1, 'AI101', 'Delhi', 'Mumbai', '2025-05-15 08:00:00', '2025-05-15 10:00:00', 4980.00, 100),
(1, 'AI102', 'Mumbai', 'Delhi', '2025-05-15 11:00:00', '2025-05-15 13:00:00', 4980.00, 100),
(2, '6E201', 'Bangalore', 'Delhi', '2025-06-10 09:00:00', '2025-06-10 11:30:00', 4482.00, 150),
(2, '6E202', 'Delhi', 'Bangalore', '2025-06-10 12:00:00', '2025-06-10 14:30:00', 4482.00, 150),
(3, 'SG301', 'Chennai', 'Kolkata', '2025-07-05 10:00:00', '2025-07-05 12:00:00', 3984.00, 120),
(3, 'SG302', 'Kolkata', 'Chennai', '2025-07-05 13:00:00', '2025-07-05 15:00:00', 3984.00, 120),
(4, 'UK401', 'Hyderabad', 'Mumbai', '2025-08-12 11:00:00', '2025-08-12 12:30:00', 3486.00, 80),
(4, 'UK402', 'Mumbai', 'Hyderabad', '2025-08-12 14:00:00', '2025-08-12 15:30:00', 3486.00, 80),

-- New International Flights
(1, 'AI901', 'Delhi', 'Dubai', '2025-09-01 06:00:00', '2025-09-01 09:00:00', 16600.00, 120),
(1, 'AI902', 'Dubai', 'Delhi', '2025-09-01 18:00:00', '2025-09-01 21:00:00', 16600.00, 120),
(2, '6E701', 'Mumbai', 'New York', '2025-10-10 02:00:00', '2025-10-10 14:00:00', 58000.00, 200),
(2, '6E702', 'New York', 'Mumbai', '2025-10-11 16:00:00', '2025-10-12 04:00:00', 58000.00, 200),
(3, 'SG801', 'Delhi', 'London', '2025-11-20 07:00:00', '2025-11-20 15:00:00', 49800.00, 150),
(3, 'SG802', 'London', 'Delhi', '2025-11-21 09:00:00', '2025-11-21 17:00:00', 49800.00, 150),
(4, 'UK601', 'Chennai', 'Singapore', '2025-12-05 08:00:00', '2025-12-05 14:00:00', 28200.00, 130),
(4, 'UK602', 'Singapore', 'Chennai', '2025-12-06 10:00:00', '2025-12-06 16:00:00', 28200.00, 130);
