<?php
// upload.php

// DB config
$host = "localhost";
$user = "root";
$pass = "";
$dbName = "real_estate";

// Connect DB
$conn = new mysqli($host, $user, $pass, $dbName);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

// Helper function to sanitize input
function clean_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Check method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request method";
    exit;
}

// Validate & sanitize inputs
$title = clean_input($_POST['title'] ?? '');
$type = clean_input($_POST['type'] ?? '');
$listingType = clean_input($_POST['listingType'] ?? '');
$price = floatval($_POST['price'] ?? 0);
$status = clean_input($_POST['status'] ?? '');

$state = clean_input($_POST['state'] ?? '');
$city = clean_input($_POST['city'] ?? '');
$street = clean_input($_POST['street'] ?? '');
$description = clean_input($_POST['description'] ?? '');
$contactName = clean_input($_POST['contactName'] ?? '');
$phone = clean_input($_POST['phone'] ?? '');
$email = clean_input($_POST['email'] ?? '');

// Basic validation
if (!$title || !$type || !$listingType || !$price || !$status ||
    !$state || !$city || !$street || !$description || !$contactName || !$phone || !$email) {
    echo "Please fill in all required fields.";
    exit;
}

// Handle gallery photos upload
$uploadedFiles = [];
$uploadDir = __DIR__ . '/uploads/';

// Create uploads dir if not exists
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if (!empty($_FILES['galleryPhotos'])) {
    $files = $_FILES['galleryPhotos'];
    for ($i = 0; $i < count($files['name']); $i++) {
        $error = $files['error'][$i];
        if ($error === UPLOAD_ERR_OK) {
            $tmpName = $files['tmp_name'][$i];
            $name = basename($files['name'][$i]);
            // Sanitize file name and add timestamp to avoid overwrites
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $allowedExts = ['jpg','jpeg','png','gif','webp'];
            if (!in_array($ext, $allowedExts)) {
                echo "File type not allowed: " . htmlspecialchars($name);
                exit;
            }
            $newName = uniqid('bm_', true) . '.' . $ext;
            $destination = $uploadDir . $newName;
            if (move_uploaded_file($tmpName, $destination)) {
                $uploadedFiles[] = $newName;
            } else {
                echo "Failed to upload file: " . htmlspecialchars($name);
                exit;
            }
        } else {
            echo "Error uploading file: " . $files['name'][$i];
            exit;
        }
    }
}

$photosJson = json_encode($uploadedFiles);

// Begin transaction
$conn->begin_transaction();

try {
    // Insert into building_material
    $stmt = $conn->prepare("INSERT INTO decoration
        (title, type, listingType, price, status,  state, city, street, description, contactName, phone, email, galleryPhotos) 
        VALUES (?, ?, ?, ?, ?, ?, ?,  ?, ?, ?, ?, ?, ?)");
    if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);

    $stmt->bind_param(
        "sssdsisssssss",
        $title,
        $type,
        $listingType,
        $price,
        $status,
        
        $state,
        $city,
        $street,
        $description,
        $contactName,
        $phone,
        $email,
        $photosJson
    );

    if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);

    $buildingMaterialId = $stmt->insert_id;
    $stmt->close();

    // Insert into publish with default draft status
    $stmt2 = $conn->prepare("INSERT INTO publish (building_material_id) VALUES (?)");
    if (!$stmt2) throw new Exception("Prepare failed (publish): " . $conn->error);

    $stmt2->bind_param("i", $buildingMaterialId);

    if (!$stmt2->execute()) throw new Exception("Execute failed (publish): " . $stmt2->error);

    $stmt2->close();

    $conn->commit();

    echo "Success: Decoration material uploaded and publish entry created.";

} catch (Exception $e) {
    $conn->rollback();
    echo "Transaction failed: " . $e->getMessage();
}

$conn->close();
