<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $conn = getDBConnection();
    
    // Get filters from query parameters
    $type = $_GET['type'] ?? '';
    $minPrice = $_GET['min_price'] ?? 0;
    $maxPrice = $_GET['max_price'] ?? 1000;
    
    // Build query
    $sql = "SELECT * FROM cars WHERE available = 1";
    $params = [];
    
    if (!empty($type)) {
        $sql .= " AND type = ?";
        $params[] = $type;
    }
    
    $sql .= " AND price BETWEEN ? AND ?";
    $params[] = $minPrice;
    $params[] = $maxPrice;
    
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $types = str_repeat('s', count($params) - 2) . 'dd';
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $cars = [];
    while ($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
    
    echo json_encode(['success' => true, 'cars' => $cars]);
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>