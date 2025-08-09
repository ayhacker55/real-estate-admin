<?php
// suspend_admin.php
$db = mysqli_connect("localhost", "root", "", "real_estate") or die("Could not connect");

if (!isset($_GET['id'])) {
    die("Admin ID is required.");
}

$id = (int)$_GET['id'];

// Update status to suspended
$query = "UPDATE admins SET status = 'suspended' WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: vadmin.php?msg=Admin suspended successfully");
} else {
    header("Location: vadmin.php?error=Failed to suspend admin");
}
$stmt->close();
$db->close();
exit;
