<?php
$db = @mysqli_connect("localhost", "root", "", "real_estate") or die("Could not connect");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = trim($_POST['category']);

    if (empty($category)) {
        echo "Category cannot be empty.";
        exit;
    }

    // Sanitize and insert into DB
    $category = mysqli_real_escape_string($db, $category);
    $query = "INSERT INTO category (cate) VALUES ('$category')";

    if (mysqli_query($db, $query)) {
        echo "Category added successfully.";
    } else {
        echo "Error: " . mysqli_error($db);
    }
} else {
    echo "Invalid request.";
}
?>
