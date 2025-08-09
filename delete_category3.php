<?php
$db = @mysqli_connect("localhost", "root", "", "real_estate") or die("Could not connect");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM category3 WHERE id = $id";
    mysqli_query($db, $query);
}

header("Location: vcate3.php");
exit;
?>