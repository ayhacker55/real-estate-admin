<?php
include('db.php');


$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Get blog media info
    $stmt = $db->prepare("SELECT media_path, media_type FROM blog_posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($media, $mediaType);
    $stmt->fetch();
    $stmt->close();

    // Delete blog from DB
    $stmt = $db->prepare("DELETE FROM blog_posts WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Delete associated media file if it's an image
        if ($mediaType === 'image' && !empty($media) && file_exists("uploads/" . $media)) {
            @unlink("uploads/" . $media);
        }

        echo "<script>alert('Blog post deleted successfully.'); window.location.href='vblog.php';</script>";
    } else {
        echo "<script>alert('Error deleting blog post.'); window.location.href='vblog.php';</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('Invalid blog ID.'); window.location.href='vblog.php';</script>";
}
?>
