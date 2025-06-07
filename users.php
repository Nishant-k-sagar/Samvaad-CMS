<?php

include('includes/config.php');
include('includes/database.php');
include('includes/functions.php');
secure();

include('includes/header.php');

if (isset($_GET['delete'])) {
    if ($stm = $connect->prepare('DELETE FROM users WHERE id = ? AND is_protected = FALSE')) {
        $stm->bind_param('i', $_GET['delete']);
        $stm->execute();

        set_message("User with ID " . htmlspecialchars($_GET['delete']) . " has been deleted.");
        header('Location: users.php');
        $stm->close();
        die();
    } else {
        echo '<div class="alert alert-danger">Could not prepare delete statement!</div>';
    }
}

if ($stm = $connect->prepare('SELECT * FROM users')) {
    $stm->execute();
    $result = $stm->get_result();

    if ($result->num_rows > 0) {
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow border-0 rounded">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-center">Users Management</h2>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#ID</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($record = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($record['id']); ?></td>
                                        <td><?php echo htmlspecialchars($record['username']); ?></td>
                                        <td><?php echo htmlspecialchars($record['email']); ?></td>
                                        <td>
                                            <?php 
                                            echo $record['active'] 
                                                ? '<span class="badge bg-success">Active</span>' 
                                                : '<span class="badge bg-secondary">Inactive</span>';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="users_edit.php?id=<?php echo $record['id']; ?>" class="btn btn-sm btn-outline-primary me-2">Edit</a>
                                            <a href="users.php?delete=<?php echo $record['id']; ?>" 
                                               onclick="return confirm('Are you sure you want to delete user ID <?php echo $record['id']; ?>?');" 
                                               class="btn btn-sm btn-outline-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-end mt-4">
                        <a href="users_add.php" class="btn btn-success">+ Add New User</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    } else {
        echo '<div class="container mt-5"><div class="alert alert-info">No users found.</div></div>';
    }

    $stm->close();
} else {
    echo '<div class="container mt-5"><div class="alert alert-danger">Could not prepare statement!</div></div>';
}

include('includes/footer.php');
?>
