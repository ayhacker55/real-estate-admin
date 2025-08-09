<?php
include('db.php');

$surname = $_POST['surname'] ?? '';
$lastname = $_POST['lastname'] ?? '';
$email = $_POST['email'] ?? '';
$username = $_POST['username'] ?? '';
$role = $_POST['role'] ?? '';
$password = $_POST['password'] ?? '';
$profile_photo = '';
$status = 'active'; // Set default status

// Validation
if (!$surname || !$lastname || !$email || !$username || !$role || !$password) {
    echo "All fields are required.";
    exit;
}

// Handle profile photo upload
if (!empty($_FILES['profile_photo']['name'])) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 2 * 1024 * 1024; // 2MB
    $file_type = $_FILES['profile_photo']['type'];
    $file_size = $_FILES['profile_photo']['size'];
    $tmp_name = $_FILES['profile_photo']['tmp_name'];

    if (!in_array($file_type, $allowed_types)) {
        echo "Invalid image format. Only JPG, PNG, or GIF allowed.";
        exit;
    }

    if ($file_size > $max_size) {
        echo "Image is too large. Max size is 2MB.";
        exit;
    }

    // Generate unique file name
    $photo_name = time() . "_" . basename($_FILES['profile_photo']['name']);
    $upload_dir = 'uploads/admins/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $upload_path = $upload_dir . $photo_name;

    // Resize image
    list($width, $height) = getimagesize($tmp_name);
    $new_width = 300;
    $new_height = 300;
    $src_image = null;

    switch ($file_type) {
        case 'image/jpeg':
            $src_image = imagecreatefromjpeg($tmp_name);
            break;
        case 'image/png':
            $src_image = imagecreatefrompng($tmp_name);
            break;
        case 'image/gif':
            $src_image = imagecreatefromgif($tmp_name);
            break;
        default:
            echo "Unsupported image type.";
            exit;
    }

    $dst_image = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    switch ($file_type) {
        case 'image/jpeg':
            imagejpeg($dst_image, $upload_path);
            break;
        case 'image/png':
            imagepng($dst_image, $upload_path);
            break;
        case 'image/gif':
            imagegif($dst_image, $upload_path);
            break;
    }

    imagedestroy($src_image);
    imagedestroy($dst_image);

    $profile_photo = $photo_name;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check for duplicates
$check = $db->prepare("SELECT id FROM admins WHERE username = ? OR email = ?");
$check->bind_param("ss", $username, $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "Username or email already exists.";
    $check->close();
    exit;
}
$check->close();

// Insert admin
$stmt = $db->prepare("INSERT INTO admins (surname, lastname, email, username, role, password, profile_photo, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $surname, $lastname, $email, $username, $role, $hashed_password, $profile_photo, $status);

if ($stmt->execute()) {
    echo "Admin created successfully.";
} else {
    echo "Error creating admin.";
}
$stmt->close();
?>
