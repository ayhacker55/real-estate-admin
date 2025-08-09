<?php
include('db.php');

session_start();
$username = $_SESSION['username'] ?? null;
if (!$username) {
    http_response_code(401);
    echo "Unauthorized: Username not found in session.";
    exit;
}

$oldPassword = $_POST['old_password'] ?? '';
$newPassword = $_POST['new_password'] ?? '';

if (!$oldPassword || !$newPassword) {
    echo "Both old and new passwords are required.";
    exit;
}

// Fetch current password hash
$stmt = $db->prepare("SELECT password FROM admins WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Admin not found.";
    exit;
}

$admin = $result->fetch_assoc();

if (!password_verify($oldPassword, $admin['password'])) {
    echo "Old password is incorrect.";
    exit;
}

// Hash new password
$newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

// Update password in DB
$update = $db->prepare("UPDATE admins SET password = ? WHERE username = ?");
$update->bind_param("ss", $newPasswordHash, $username);

if ($update->execute()) {
    echo "Password updated successfully.";
} else {
    echo "Error updating password.";
}
?>
