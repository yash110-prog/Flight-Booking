-- Create bookings table if it doesn't exist
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    reward_points INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create bookings table if it doesn't exist
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    flight_id INT NOT NULL,
    booking_reference VARCHAR(10) NOT NULL,
    total_passengers INT NOT NULL DEFAULT 1,
    total_price DECIMAL(10,2) NOT NULL,
    booking_status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (flight_id) REFERENCES flights(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert a sample user if users table is empty
INSERT INTO users (first_name, last_name, email, password, phone, reward_points)
SELECT 'John', 'Doe', 'demo@example.com', '$2y$10$pI7jOrsXY5KStL9jUxk0peLGQe/VY8CDObe9hFALQ67h64QkXFnea', '123-456-7890', 500
WHERE NOT EXISTS (SELECT 1 FROM users LIMIT 1);

-- Insert sample bookings if no bookings exist
INSERT INTO bookings (user_id, flight_id, booking_reference, total_passengers, total_price, booking_status)
SELECT 
    (SELECT id FROM users ORDER BY id LIMIT 1),
    (SELECT id FROM flights ORDER BY id LIMIT 1),
    'ABC123456',
    2,
    11998.00,
    'confirmed'
WHERE EXISTS (SELECT 1 FROM flights LIMIT 1) 
AND EXISTS (SELECT 1 FROM users LIMIT 1)
AND NOT EXISTS (SELECT 1 FROM bookings LIMIT 1);

-- Insert a second sample booking
INSERT INTO bookings (user_id, flight_id, booking_reference, total_passengers, total_price, booking_status)
SELECT 
    (SELECT id FROM users ORDER BY id LIMIT 1),
    (SELECT id FROM flights ORDER BY id LIMIT 1 OFFSET 1),
    'DEF789012',
    1,
    7499.00,
    'confirmed'
WHERE EXISTS (SELECT 1 FROM flights LIMIT 1 OFFSET 1) 
AND EXISTS (SELECT 1 FROM users LIMIT 1)
AND EXISTS (SELECT 1 FROM bookings LIMIT 1)
AND (SELECT COUNT(*) FROM bookings) < 2; 