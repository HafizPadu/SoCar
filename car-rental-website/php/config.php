<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'car_rental_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Create connection
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

// For testing - create table if not exists
function createTables() {
    $conn = getDBConnection();
    
    // Cars table
    $sql = "CREATE TABLE IF NOT EXISTS cars (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        type VARCHAR(50) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        seats INT NOT NULL,
        transmission VARCHAR(20) NOT NULL,
        fuel_type VARCHAR(20) NOT NULL,
        image_url VARCHAR(255),
        available BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $conn->query($sql);
    
    // Bookings table
    $sql = "CREATE TABLE IF NOT EXISTS bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        car_id INT NOT NULL,
        customer_name VARCHAR(100) NOT NULL,
        customer_email VARCHAR(100) NOT NULL,
        customer_phone VARCHAR(20) NOT NULL,
        pickup_date DATE NOT NULL,
        return_date DATE NOT NULL,
        total_price DECIMAL(10,2) NOT NULL,
        status VARCHAR(20) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (car_id) REFERENCES cars(id)
    )";
    
    $conn->query($sql);
    
    // Insert sample data if empty
    $result = $conn->query("SELECT COUNT(*) as count FROM cars");
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        $sampleCars = [
            "('Toyota Camry', 'sedan', 45.00, 5, 'automatic', 'petrol', 'images/cars/camry.jpg', 1)",
            "('Honda CR-V', 'suv', 65.00, 7, 'automatic', 'petrol', 'images/cars/crv.jpg', 1)",
            "('BMW X5', 'luxury', 120.00, 5, 'automatic', 'petrol', 'images/cars/x5.jpg', 1)",
            "('Toyota Corolla', 'economy', 35.00, 5, 'automatic', 'hybrid', 'images/cars/corolla.jpg', 1)",
            "('Mercedes Sprinter', 'van', 85.00, 12, 'manual', 'diesel', 'images/cars/sprinter.jpg', 1)"
        ];
        
        $sql = "INSERT INTO cars (name, type, price, seats, transmission, fuel_type, image_url, available) 
                VALUES " . implode(',', $sampleCars);
        $conn->query($sql);
    }
    
    $conn->close();
}

// Call this function once to create tables
// createTables();
?>