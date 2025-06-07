<?php
include('includes/config.php');
include('includes/database.php');
include('includes/functions.php');
secure();
include('includes/header.php');

// Fetch all images
$result = $connect->query("SELECT image_name, image_url, uploaded_at FROM images ORDER BY uploaded_at DESC");
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow border-0 rounded">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-center">All Images</h2>
                    <div class="row">
                        <?php if ($result->num_rows > 0) { 
                            while ($row = $result->fetch_assoc()) { ?>
                            <div class="col-md-3 col-sm-4 col-6 mb-4 text-center">
                                <img src="<?= htmlspecialchars($row['image_url'] ?? '') ?>" 
                                     alt="<?= htmlspecialchars($row['image_name'] ?? '') ?>" 
                                     class="img-thumbnail" 
                                     style="max-width:100%;height:200px;object-fit:cover">
                                <div class="mt-2">
                                    <?= htmlspecialchars($row['image_name'] ?? '') ?>
                                </div>
                                <small class="text-muted">
                                    <?= htmlspecialchars($row['uploaded_at'] ?? '') ?>
                                </small>
                            </div>
                            <?php }
                        } else { ?>
                            <div class="col-12">
                                <div class="alert alert-info text-center">No images found</div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>
