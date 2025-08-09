<?php
$db = @mysqli_connect("localhost", "root", "", "real_estate") or die("Could not connect");

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    die("Invalid property ID.");
}

// Fetch property gallery JSON
$result = mysqli_query($db, "SELECT gallery FROM property WHERE id = $id");
if (!$result || mysqli_num_rows($result) === 0) {
    die("Property not found.");
}

$row = mysqli_fetch_assoc($result);
$gallery = json_decode($row['gallery'], true);

// Delete images from the server
if (!empty($gallery) && is_array($gallery)) {
    foreach ($gallery as $imagePath) {
        $file = __DIR__ . '/' . $imagePath;
        if (file_exists($file)) {
            unlink($file);
        }
    }
}

// Delete property record
$delete = mysqli_query($db, "DELETE FROM property WHERE id = $id");

if ($delete) {
    // Redirect or show success
    header("Location: vproduct.php?msg=Property deleted successfully");
    exit;
} else {
    echo "Error deleting property: " . mysqli_error($db);
}
?>
