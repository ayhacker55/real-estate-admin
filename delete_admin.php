<?php
// delete_admin.php
include('db.php');

if (!isset($_GET['id'])) {
    die("Admin ID is required.");
}

$id = (int)$_GET['id'];

// Optionally, you may want to delete profile photo file here if exists
// Fetch profile photo filename first
$query = "SELECT profile_photo FROM admins WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($profile_photo);
$stmt->fetch();
$stmt->close();

if ($profile_photo && file_exists("uploads/admins/" . $profile_photo)) {
    unlink("uploads/admins/" . $profile_photo);
}

// Delete admin record
$query = "DELETE FROM admins WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: vadmn.php?msg=Admin deleted successfully");
} else {
    header("Location: vadmn.php?error=Failed to delete admin");
}
$stmt->close();
$db->close();
exit;
