<?php
include('db.php');


$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $stmt = $db->prepare("UPDATE blog_posts SET status='rejected' WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Blog post rejected.'); window.location.href='vblog.php';</script>";
    } else {
        echo "<script>alert('Error rejecting blog post.'); window.location.href='vblog.php';</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('Invalid blog ID.'); window.location.href='vblog.php';</script>";
}
?>
