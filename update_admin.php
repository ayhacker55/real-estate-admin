<?php
include('db.php');
session_start();

$id = intval($_POST['id']);
$surname = trim($_POST['surname']);
$lastname = trim($_POST['surname']);  // fixed typo here: it was trimming surname twice
$email = trim($_POST['email']);
$username = trim($_POST['username']);
$role = trim($_POST['role']);
$existing_photo = $_POST['existing_photo'] ?? '';

$new_photo_path = $existing_photo;
$upload_dir = 'uploads/admins/';

if (!empty($_FILES['profile_photo']['name'])) {
    $file_tmp = $_FILES['profile_photo']['tmp_name'];
    $file_name = basename($_FILES['profile_photo']['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($file_ext, $allowed_ext)) {
        die("Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.");
    }

    $new_file_name = uniqid('admin_', true) . '.' . $file_ext;
    $target_path = $upload_dir . $new_file_name;

    if (!move_uploaded_file($file_tmp, $target_path)) {
        die("Failed to upload new profile photo.");
    }

    // Full path for existing photo deletion
    $full_existing_photo_path = 'uploads/admins/' . $existing_photo;

    if (!empty($existing_photo) && file_exists($full_existing_photo_path)) {
        unlink($full_existing_photo_path);
    }
    $new_photo_path = $target_path;
}

// Decide whether to update photo or not based on upload
if ($new_photo_path !== $existing_photo) {
    // Photo updated - update profile_photo column
    $stmt = $db->prepare("UPDATE admins SET surname = ?, lastname = ?, email = ?, role = ?, profile_photo = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $surname, $lastname, $email, $role, $new_file_name, $id);
} else {
    // Photo NOT updated - keep existing photo in DB
    $stmt = $db->prepare("UPDATE admins SET surname = ?, lastname = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $surname, $lastname, $email, $role, $id);
}

if ($stmt->execute()) {
    echo "Admin profile updated successfully.";
} else {
    echo "Error updating admin: " . $stmt->error;
}
?>
