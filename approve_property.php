<?php
$db = @mysqli_connect("localhost", "root", "", "real_estate") or die("Could not connect");

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    die("Invalid property ID.");
}

// Update the publish column
$query = "UPDATE property SET publish = 'approved' WHERE id = $id";
$updated = mysqli_query($db, $query);

if ($updated) {
    header("Location: vproduct.php?msg=Property approved successfully");
    exit;
} else {
    echo "Failed to approve property: " . mysqli_error($db);
}
?>
