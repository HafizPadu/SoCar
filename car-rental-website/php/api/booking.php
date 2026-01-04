<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }
    
    $conn = getDBConnection();
    
    // Validate required fields
    $required = ['car_id', 'customer_name', 'customer_email', 'customer_phone', 'pickup_date', 'return_date', 'total_price'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
            $conn->close();
            exit;
        }
    }
    
    // Insert booking
    $sql = "INSERT INTO bookings (car_id, customer_name, customer_email, customer_phone, 
            pickup_date, return_date, total_price) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssd", 
        $input['car_id'],
        $input['customer_name'],
        $input['customer_email'],
        $input['customer_phone'],
        $input['pickup_date'],
        $input['return_date'],
        $input['total_price']
    );
    
    if ($stmt->execute()) {
        $bookingId = $stmt->insert_id;
        
        // Update car availability
        $updateSql = "UPDATE cars SET available = 0 WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("i", $input['car_id']);
        $updateStmt->execute();
        $updateStmt->close();
        
        echo json_encode([
            'success' => true,
            'message' => 'Booking created successfully',
            'booking_id' => $bookingId,
            'reference' => 'SR-' . str_pad($bookingId, 8, '0', STR_PAD_LEFT)
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create booking']);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>