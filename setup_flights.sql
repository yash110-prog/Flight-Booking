-- Create flights table
CREATE TABLE IF NOT EXISTS flights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    flight_number VARCHAR(10) NOT NULL,
    departure_city VARCHAR(50) NOT NULL,
    arrival_city VARCHAR(50) NOT NULL,
    departure_time DATETIME NOT NULL,
    arrival_time DATETIME NOT NULL,
    duration VARCHAR(20) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    total_seats INT NOT NULL,
    available_seats INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create passengers table
CREATE TABLE IF NOT EXISTS passengers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    flight_id INT NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    seat_number VARCHAR(10),
    booking_reference VARCHAR(10) NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (flight_id) REFERENCES flights(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_reference VARCHAR(10) NOT NULL UNIQUE,
    flight_id INT NOT NULL,
    user_id INT NOT NULL,
    number_of_passengers INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    booking_status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (flight_id) REFERENCES flights(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample flights
INSERT INTO flights (flight_number, departure_city, arrival_city, departure_time, arrival_time, duration, price, total_seats, available_seats) VALUES
('SK101', 'DEL', 'BOM', '2024-03-20 10:00:00', '2024-03-20 12:00:00', '2h 00m', 5999.00, 180, 150),
('SK102', 'BOM', 'DEL', '2024-03-20 13:00:00', '2024-03-20 15:00:00', '2h 00m', 6299.00, 180, 150),
('SK201', 'DEL', 'BLR', '2024-03-20 11:30:00', '2024-03-20 14:00:00', '2h 30m', 7499.00, 200, 180),
('SK202', 'BLR', 'DEL', '2024-03-20 15:30:00', '2024-03-20 18:00:00', '2h 30m', 7699.00, 200, 180),
('SK301', 'BOM', 'BLR', '2024-03-20 09:00:00', '2024-03-20 10:30:00', '1h 30m', 4999.00, 180, 160),
('SK302', 'BLR', 'BOM', '2024-03-20 11:30:00', '2024-03-20 13:00:00', '1h 30m', 5299.00, 180, 160),
('SK401', 'DEL', 'DXB', '2024-03-20 23:00:00', '2024-03-21 01:30:00', '3h 30m', 15999.00, 250, 200),
('SK402', 'DXB', 'DEL', '2024-03-20 03:00:00', '2024-03-20 07:30:00', '3h 30m', 16499.00, 250, 200),
('SK501', 'BOM', 'SIN', '2024-03-20 22:00:00', '2024-03-21 06:00:00', '5h 30m', 22999.00, 280, 220),
('SK502', 'SIN', 'BOM', '2024-03-20 08:00:00', '2024-03-20 13:30:00', '5h 30m', 23499.00, 280, 220); 