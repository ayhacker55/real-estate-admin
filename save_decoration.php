<?php
// Connect to DB
$db = @mysqli_connect("localhost", "root", "", "real_estate") or die("Could not connect");

// Helper function to sanitize input
function clean_input($db, $data) {
    return mysqli_real_escape_string($db, trim($data));
}

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST fields safely
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    $title = clean_input($db, $_POST['title'] ?? '');
    $type = clean_input($db, $_POST['type'] ?? '');
    $listingType = clean_input($db, $_POST['listingType'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $status = clean_input($db, $_POST['status'] ?? '');
    $state = clean_input($db, $_POST['state'] ?? '');
    $city = clean_input($db, $_POST['city'] ?? '');
    $street = clean_input($db, $_POST['street'] ?? '');
    $description = clean_input($db, $_POST['description'] ?? '');
    $contactName = clean_input($db, $_POST['contactName'] ?? '');
    $phone = clean_input($db, $_POST['phone'] ?? '');
    $email = clean_input($db, $_POST['email'] ?? '');
    $publish = isset($_POST['publish']) ? intval($_POST['publish']) : 0;

    // Step 1: Fetch existing photos if updating
    $existingPhotos = [];
    if ($id > 0) {
        $sql = "SELECT galleryPhotos FROM decoration WHERE id = $id LIMIT 1";
        $res = mysqli_query($db, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $existingPhotos = json_decode($row['galleryPhotos'], true);
            if (!is_array($existingPhotos)) {
                $existingPhotos = [];
            }
        }
    }

    // Step 2: Remove photos marked for deletion
    if (!empty($_POST['removePhotos']) && is_array($_POST['removePhotos'])) {
        foreach ($_POST['removePhotos'] as $photoToRemove) {
            $photoToRemove = basename($photoToRemove); // sanitize filename
            if (($key = array_search($photoToRemove, $existingPhotos)) !== false) {
                // Delete file from uploads folder if exists
                $filePath = __DIR__ . "/uploads/" . $photoToRemove;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                // Remove from array
                unset($existingPhotos[$key]);
            }
        }
        // Reindex array
        $existingPhotos = array_values($existingPhotos);
    }

    // Step 3: Handle new uploaded photos
    if (!empty($_FILES['galleryPhotosNew']['name'][0])) {
        $files = $_FILES['galleryPhotosNew'];

        // Loop through all uploaded files
        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $tmpName = $files['tmp_name'][$i];
                $originalName = basename($files['name'][$i]);

                // Create unique filename to avoid overwrite
                $ext = pathinfo($originalName, PATHINFO_EXTENSION);
                $filename = uniqid('photo_') . '.' . $ext;

                $targetDir = __DIR__ . "/uploads/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                $targetFile = $targetDir . $filename;

                if (move_uploaded_file($tmpName, $targetFile)) {
                    // Add to gallery photos array
                    $existingPhotos[] = $filename;
                }
            }
        }
    }

    // Step 4: JSON encode gallery photos array for DB storage
    $galleryPhotosJson = json_encode(array_values($existingPhotos));

    // Step 5: Insert or update record
    if ($id > 0) {
        // Update
        $sql = "UPDATE decoration SET
            title = '$title',
            type = '$type',
            listingType = '$listingType',
            price = $price,
            status = '$status',
            state = '$state',
            city = '$city',
            street = '$street',
            description = '$description',
            contactName = '$contactName',
            phone = '$phone',
            email = '$email',
            galleryPhotos = '" . mysqli_real_escape_string($db, $galleryPhotosJson) . "',
            publish = $publish
            WHERE id = $id
        ";
    } else {
        // Insert
        $sql = "INSERT INTO decoration (
            title, type, listingType, price, status, state, city, street, description, contactName, phone, email, galleryPhotos, publish
        ) VALUES (
            '$title', '$type', '$listingType', $price, '$status', '$state', '$city', '$street', '$description', '$contactName', '$phone', '$email', '" . mysqli_real_escape_string($db, $galleryPhotosJson) . "', $publish
        )";
    }

    if (mysqli_query($db, $sql)) {
        // Success, redirect or show message
        header("Location: edit_decoration.php?id=$id"); // change to your success page
        exit;
    } else {
        echo "Error: " . mysqli_error($db);
    }
} else {
    echo "Invalid request method.";
}
