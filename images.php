<?php
include('includes/config.php');
include('includes/database.php');
include('includes/functions.php');
include('includes/cloudinary_config.php'); // Add Cloudinary config
secure();
include('includes/header.php');

// Handle delete request
if (isset($_GET['delete'])) {
    // First get public_id from database
    if ($stmt = $connect->prepare('SELECT public_id FROM images WHERE id = ?')) {
        $stmt->bind_param('i', $_GET['delete']);
        $stmt->execute();
        $stmt->bind_result($public_id);
        $stmt->fetch();
        $stmt->close();

        // Delete from Cloudinary
        if (!empty($public_id)) {
            try {
                (new Cloudinary\Api\Upload\UploadApi())->destroy($public_id);
            } catch (Exception $e) {
                // Handle error if needed
            }
        }
    }

    // Delete from database
    if ($stm = $connect->prepare('DELETE FROM images WHERE id = ?')) {
        $stm->bind_param('i', $_GET['delete']);
        $stm->execute();
        set_message("Image " . htmlspecialchars($_GET['delete'] ?? '') . " deleted");
        header('Location: images.php');
        $stm->close();
        die();
    } else {
        echo '<div class="alert alert-danger">Could not prepare statement!</div>';
    }
}

// Fetch all images
if ($stm = $connect->prepare('SELECT * FROM images ORDER BY uploaded_at DESC')) {
    $stm->execute();
    $result = $stm->get_result();
    ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow border-0 rounded">
                    <div class="card-body">
                        <h2 class="card-title mb-4 text-center">Images Management</h2>
                        <div class="text-end mb-3">
                            <a href="images_add.php" class="btn btn-success">+ Add New Image</a>
                        </div>
                        <div class="table-responsive">
                            <?php if ($result->num_rows > 0) { ?>
                                <table class="table table-striped table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">#ID</th>
                                            <th scope="col">Image</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Uploaded At</th>
                                            <th scope="col" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($record = $result->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?= htmlspecialchars($record['id'] ?? '') ?></td>
                                                <td>
                                                    <img src="<?= htmlspecialchars($record['image_url'] ?? '') ?>"
                                                        alt="<?= htmlspecialchars($record['image_name'] ?? '') ?>"
                                                        style="max-width:100px;max-height:100px;">
                                                </td>
                                                <td>
                                                    <?= htmlspecialchars($record['image_name'] ?? '') ?>
                                                    <?php if (!empty($record['member_name'])) { ?>
                                                        <br><small
                                                            class="text-primary"><?= htmlspecialchars($record['member_name']) ?></small>
                                                    <?php } ?>
                                                </td>

                                                <td><?= htmlspecialchars($record['uploaded_at'] ?? '') ?></td>
                                                <td class="text-center">
                                                    <a href="images.php?delete=<?= $record['id'] ?>"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Delete image <?= $record['id'] ?>?')">
                                                        Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            <?php } else { ?>
                                <div class="alert alert-info mb-0">No images found.</div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $stm->close();
} else {
    echo '<div class="container mt-5"><div class="alert alert-danger">Could not prepare statement!</div></div>';
}
include('includes/footer.php');
?>