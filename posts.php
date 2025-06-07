<?php
include('includes/config.php');
include('includes/database.php');
include('includes/functions.php');
secure();

include('includes/header.php');

if (isset($_GET['delete'])) {
    if ($stm = $connect->prepare('DELETE FROM posts where id = ?')) {
        $stm->bind_param('i', $_GET['delete']);
        $stm->execute();
        $stm->close();

        set_message("A post  " . htmlspecialchars($_GET['delete']) . " has beed deleted");
        header('Location: posts.php');
        die();
    } else {
        echo '<div class="alert alert-danger">Could not prepare statement!</div>';
    }
}

if ($stm = $connect->prepare('SELECT * FROM posts')) {
    $stm->execute();
    $result = $stm->get_result();

    if ($result->num_rows > 0) {
        ?>

        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow border-0 rounded">
                        <div class="card-body">
                            <h2 class="card-title mb-4 text-center">Posts Management</h2>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">#ID</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Author ID</th>
                                            <th scope="col">Content</th>
                                            <th scope="col" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($record = $result->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($record['id']); ?></td>
                                                <td><?php echo htmlspecialchars($record['title']); ?></td>
                                                <td><?php echo htmlspecialchars($record['author']); ?></td>
                                                <td class="text-center align-middle"
                                                    style="vertical-align: middle; text-align: center;">
                                                    <?php echo $record['content']; ?>
                                                </td>

                                                <td class="text-center">
                                                    <a href="posts_edit.php?id=<?php echo $record['id']; ?>"
                                                        class="btn btn-sm btn-outline-primary me-2">Edit</a>
                                                    <a href="posts.php?delete=<?php echo $record['id']; ?>"
                                                        onclick="return confirm('Are you sure you want to delete post ID <?php echo $record['id']; ?>?');"
                                                        class="btn btn-sm btn-outline-danger">Delete</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-end mt-4">
                                <a href="posts_add.php" class="btn btn-success">+ Add New Post</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
    } else {
        echo '<div class="container mt-5"><div class="alert alert-info">No posts found.</div></div>';
    }
    $stm->close();
} else {
    echo '<div class="container mt-5"><div class="alert alert-danger">Could not prepare statement!</div></div>';
}

include('includes/footer.php');
?>