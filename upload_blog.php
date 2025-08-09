<?php
include('db.php');
if ($db->connect_error) {
    die("DB Connection failed: " . $db->connect_error);
}

$title = $_POST['title'];
$content = $_POST['content']; // preserve formatting
$author = $_POST['author'];
$email = $_POST['email'];
$mediaType = $_POST['mediaType'];
$mediaPath = "";

if ($mediaType === "image" && isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = "uploads/";
    $filename = time() . "_" . basename($_FILES['imageFile']['name']);
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['imageFile']['tmp_name'], $targetPath)) {
        $mediaPath = $targetPath;
    } else {
        echo "Error uploading image.";
        exit;
    }
} elseif ($mediaType === "youtube") {
    $mediaPath = $_POST['youtubeUrl'];
}

$stmt = $db->prepare("INSERT INTO blog_posts (title, content, author, email, media_type, media_path) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $title, $content, $author, $email, $mediaType, $filename);

if ($stmt->execute()) {
    echo "Blog post submitted successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$db->close();
?>
