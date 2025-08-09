<?php
$db = @mysqli_connect("localhost", "root", "", "real_estate") or die("Could not connect");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    mysqli_query($db, "UPDATE property SET publish = 'rejected' WHERE id = $id");
}

header("Location: vproduct.php");
exit;
