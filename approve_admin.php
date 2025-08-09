<?php
// approve_admin.php
include('db.php');

if (!isset($_GET['id'])) {
    die("Admin ID is required.");
}

$id = (int)$_GET['id'];

// Update status to active
$query = "UPDATE admins SET status = 'active' WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: vadmin.php?msg=Admin approved successfully");
} else {
    header("Location: vadmin.php?error=Failed to approve admin");
}
$stmt->close();
$db->close();
exit;
