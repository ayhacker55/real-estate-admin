<?php
$db = mysqli_connect("localhost", "root", "", "real_estate") or die("Could not connect");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Step 1: Fetch gallery photos
    $sql = "SELECT galleryPhotos FROM building_material WHERE id = $id LIMIT 1";
    $res = mysqli_query($db, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $photos = json_decode($row['galleryPhotos'], true);

        // Step 2: Delete photo files
        if (is_array($photos)) {
            foreach ($photos as $photo) {
                $photoPath = __DIR__ . "/uploads/" . basename($photo);
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
            }
        }

        // Step 3: Delete the record
        $delete = mysqli_query($db, "DELETE FROM building_material WHERE id = $id");

        if ($delete) {
            header("Location: vbuilding.php?msg=deleted");
            exit;
        } else {
            echo "Error deleting record: " . mysqli_error($db);
        }
    } else {
        echo "Record not found.";
    }
} else {
    echo "Invalid ID";
}
?>
