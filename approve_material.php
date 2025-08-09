<?php
$db = mysqli_connect("localhost", "root", "", "real_estate") or die("Could not connect");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $query = "UPDATE building_material SET publish = 'approved' WHERE id = $id";
    if (mysqli_query($db, $query)) {
        header("Location: vbuilding.php?msg=approved"); // Replace with actual page
        exit;
    } else {
        echo "Error approving record: " . mysqli_error($db);
    }
} else {
    echo "Invalid ID";
}
?>
