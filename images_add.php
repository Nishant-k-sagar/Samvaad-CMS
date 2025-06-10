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
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    
    // Get form data
    $memberName = $_POST['member_name'] ?? '';
    $memberRole = $_POST['member_role'] ?? '';
    $memberBio = $_POST['member_bio'] ?? '';
    $memberType = $_POST['member_type'] ?? 'general';
    
    // Check if file was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['image']['name']);
        $tmpName = $_FILES['image']['tmp_name'];
        $fileType = mime_content_type($tmpName);
        
        if (in_array($fileType, $allowed)) {
            try {
                // Upload to Cloudinary
                $result = (new UploadApi())->upload($tmpName, [
                    'folder' => 'team_members',
                    'resource_type' => 'image'
                ]);

                // Store in database with team member fields
                if ($stm = $connect->prepare('INSERT INTO images (image_name, image_url, public_id, member_name, member_role, member_bio, member_type) VALUES (?, ?, ?, ?, ?, ?, ?)')) {
                    $stm->bind_param('sssssss', 
                        $fileName,
                        $result['secure_url'],
                        $result['public_id'],
                        $memberName,
                        $memberRole,
                        $memberBio,
                        $memberType
                    );
                    
                    if ($stm->execute()) {
                        set_message("Image uploaded successfully!");
                        header('Location: images.php');
                        die();
                    } else {
                        $message = '<div class="alert alert-danger mb-4">Database error: ' . $connect->error . '</div>';
                    }
                    $stm->close();
                } else {
                    $message = '<div class="alert alert-danger mb-4">Database prepare failed</div>';
                }
            } catch (Exception $e) {
                $message = '<div class="alert alert-danger mb-4">Upload failed: ' . $e->getMessage() . '</div>';
            }
        } else {
            $message = '<div class="alert alert-danger mb-4">Invalid file type</div>';
        }
    } else {
        $message = '<div class="alert alert-warning mb-4">Please select an image</div>';
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow border-0 rounded">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-center">Add New Image</h2>
                    <?= $message ?>
                    <form method="post" enctype="multipart/form-data">
                        
                        <!-- Image Upload -->
                        <div class="mb-3">
                            <label class="form-label">Select Image *</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                        </div>
                        
                        <!-- Team Member Type -->
                        <div class="mb-3">
                            <label class="form-label">Image Type</label>
                            <select name="member_type" class="form-select" onchange="toggleMemberFields(this.value)">
                                <option value="general">Regular Image</option>
                                <option value="core_team">Core Team Member</option>
                                <option value="volunteer">Volunteer</option>
                            </select>
                        </div>
                        
                        <!-- Team Member Fields (hidden by default) -->
                        <div id="memberFields" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Member Name</label>
                                <input type="text" name="member_name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Member Role</label>
                                <input type="text" name="member_role" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Member Bio</label>
                                <textarea name="member_bio" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="submit" name="submit" class="btn btn-success">Upload Image</button>
                            <a href="images.php" class="btn btn-secondary">Back to Images</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleMemberFields(value) {
    const fields = document.getElementById('memberFields');
    const roleInput = document.querySelector('input[name="member_role"]');

    // Show/hide member fields based on selection
    fields.style.display = (value === 'general') ? 'none' : 'block';

    // Update placeholder and value
    if (value === 'volunteer') {
        roleInput.value = 'Volunteer';
        roleInput.placeholder = 'Volunteer (by default)';
    } else if (value === 'core_team') {
        roleInput.value = 'Core Team Member';
        roleInput.placeholder = 'Core Team Member (by default)';
    } else {
        roleInput.placeholder = '';
        roleInput.value = '';
    }
}
</script>


<?php include('includes/footer.php'); ?>
