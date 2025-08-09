reject_material.php<?php
$db = mysqli_connect("localhost", "root", "", "real_estate") or die("Could not connect");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $query = "UPDATE building_material SET publish = 'rejected' WHERE id = $id";
    if (mysqli_query($db, $query)) {
        header("Location: vbuilding.php?msg=rejected"); // Replace with actual page
        exit;
    } else {
        echo "Error rejecting record: " . mysqli_error($db);
    }
} else {
    echo "Invalid ID";
}
?>
