<?php
include('includes/config.php');
include('includes/database.php');
include('includes/functions.php');
include('includes/cloudinary_config.php');
secure();
include('includes/header.php');

use Cloudinary\Api\Upload\UploadApi;

$message = '';

if (isset($_POST['submit'])) {
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/jpg'];
    $success = 0;
    $fail = 0;
    $errors = [];

    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $fileName = basename($_FILES['images']['name'][$key]);
            
            // Skip empty files
            if ($_FILES['images']['error'][$key] !== UPLOAD_ERR_OK) {
                $errors[] = "File $fileName: Upload error (" . $_FILES['images']['error'][$key] . ")";
                $fail++;
                continue;
            }

            // Verify MIME type
            $fileType = mime_content_type($tmp_name);
            if (!in_array($fileType, $allowed)) {
                $errors[] = "File $fileName: Invalid file type ($fileType)";
                $fail++;
                continue;
            }

            try {
                // Upload to Cloudinary
                $result = (new UploadApi())->upload($tmp_name, [
                    'folder' => 'your_folder_name', // Update or remove this
                    'resource_type' => 'image'
                ]);

                // Store in database
                if ($stm = $connect->prepare('INSERT INTO images (image_name, image_url, public_id) VALUES (?, ?, ?)')) {
                    $stm->bind_param('sss', 
                        $fileName,
                        $result['secure_url'],
                        $result['public_id']
                    );
                    if ($stm->execute()) {
                        $success++;
                    } else {
                        $errors[] = "File $fileName: Database insert failed - " . $connect->error;
                        $fail++;
                    }
                    $stm->close();
                } else {
                    $errors[] = "File $fileName: Database prepare failed - " . $connect->error;
                    $fail++;
                }
            } catch (Exception $e) {
                $errors[] = "File $fileName: Cloudinary upload failed - " . $e->getMessage();
                $fail++;
            }
        }

        // Build result message
        $messageContent = "$success image(s) uploaded successfully.";
        if ($fail > 0) {
            $messageContent .= " $fail failed.";
            if (!empty($errors)) {
                $messageContent .= "<br><small>" . implode("<br>", $errors) . "</small>";
            }
        }
        
        set_message($messageContent);
        header('Location: images.php');
        die();
    } else {
        $message = '<div class="alert alert-warning mb-4">No files selected</div>';
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow border-0 rounded">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-center">Add New Images</h2>
                    <?= $message ?>
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Select images (multiple allowed):</label>
                            <input type="file" 
                                   name="images[]" 
                                   class="form-control" 
                                   accept="image/*" 
                                   multiple 
                                   required
                                   onchange="previewFiles(event)">
                            <div id="filePreview" class="mt-2"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" name="submit" class="btn btn-success">
                                <i class="fas fa-upload me-2"></i>Upload Images
                            </button>
                            <a href="images.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Images
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewFiles(event) {
    const preview = document.getElementById('filePreview');
    preview.innerHTML = '';
    
    const files = event.target.files;
    for (const file of files) {
        const div = document.createElement('div');
        div.className = 'text-muted small mb-1';
        div.textContent = `${file.name} (${Math.round(file.size/1024)}KB)`;
        preview.appendChild(div);
    }
}
</script>

<?php include('includes/footer.php'); ?>
