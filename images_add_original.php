<?php
include('includes/config.php');
include('includes/database.php');
include('includes/functions.php');
secure();
include('includes/header.php');

$message = '';

if (isset($_POST['submit'])) {
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/jpg'];
    $success = 0;
    $fail = 0;

    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $fileName = basename($_FILES['images']['name'][$key]);
                $fileType = mime_content_type($tmp_name);

                if (in_array($fileType, $allowed)) {
                    $fileData = file_get_contents($tmp_name);
                    if ($stm = $connect->prepare('INSERT INTO images (image_name, image_type, image_data) VALUES (?, ?, ?)')) {
                        $null = NULL;
                        $stm->bind_param('ssb', $fileName, $fileType, $null);
                        $stm->send_long_data(2, $fileData);
                        $stm->execute();
                        $stm->close();
                        $success++;
                    } else {
                        $fail++;
                    }
                } else {
                    $fail++;
                }
            } else {
                $fail++;
            }
        }
        set_message("$success image(s) uploaded successfully. $fail failed.");
        header('Location: images.php');
        die();
    } else {
        $message = '<div class="alert alert-warning mb-4">No files uploaded.</div>';
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow border-0 rounded">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-center">Add New Images</h2>
                    <?php echo $message; ?>
                    <form action="images_add.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="image" class="form-label">Select images to upload:</label>
                            <input type="file" name="images[]" id="image" accept="image/*" class="form-control" multiple required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" name="submit" class="btn btn-success">Upload Images</button>
                            <a href="images.php" class="btn btn-secondary">Back to Images</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>
