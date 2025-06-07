<?php
include('includes/config.php');
include('includes/database.php');
include('includes/functions.php');
secure();
include('includes/header.php');

// Fetch all images from the database
$sql = "SELECT image_name, image_type, image_data, uploaded_at FROM images ORDER BY uploaded_at DESC";
$result = $connect->query($sql);
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow border-0 rounded">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-center">All Images</h2>
                    <div class="row">
                        <?php
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Encode the image data for embedding
                                $base64 = base64_encode($row['image_data']);
                                $src = 'data:' . htmlspecialchars($row['image_type']) . ';base64,' . $base64;
                                ?>
                                <div class="col-md-3 col-sm-4 col-6 mb-4 text-center">
                                    <img src="<?php echo $src; ?>"
                                         alt="<?php echo htmlspecialchars($row['image_name']); ?>"
                                         class="img-thumbnail"
                                         style="max-width: 100%; max-height: 200px;">
                                    <div><?php echo htmlspecialchars($row['image_name']); ?></div>
                                    <div class="text-muted small"><?php echo htmlspecialchars($row['uploaded_at']); ?></div>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<div class="col-12"><div class="alert alert-info text-center mb-0">No images found.</div></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>
