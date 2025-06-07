<?php

include('includes/config.php');
include('includes/database.php');
include('includes/functions.php');
secure();

include('includes/header.php');

$message = '';

if (isset($_POST['username'])) {

    if ($stm = $connect->prepare('UPDATE users SET username = ?, email = ?, active = ? WHERE id = ?')) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $active = (int)$_POST['active'];
        $id = (int)$_GET['id'];

        $stm->bind_param('ssii', $username, $email, $active, $id);
        $stm->execute();
        $stm->close();

        // Update password if provided (and not empty)
        if (!empty($_POST['password'])) {
            if ($stm = $connect->prepare('UPDATE users SET password = ? WHERE id = ?')) {
                $hashed = sha1($_POST['password']); // For production, use password_hash()
                $stm->bind_param('si', $hashed, $id);
                $stm->execute();
                $stm->close();
            } else {
                $message = '<div class="alert alert-danger mb-4">Could not prepare password update statement!</div>';
            }
        }

        set_message("User with ID $id has been updated.");
        header('Location: users.php');
        die();

    } else {
        $message = '<div class="alert alert-danger mb-4">Could not prepare user update statement!</div>';
    }
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($stm = $connect->prepare('SELECT * FROM users WHERE id = ?')) {
        $stm->bind_param('i', $id);
        $stm->execute();

        $result = $stm->get_result();
        $user = $result->fetch_assoc();
        $stm->close();

        if ($user) {
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow border-0 rounded">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-center">Edit User</h2>
                    <?php echo $message; ?>

                    <form method="post" novalidate>
                        <fieldset class="mb-3">
                            <legend class="fs-5">User Information</legend>
                            <div class="mb-3">
                                <label class="form-label" for="username">Username</label>
                                <input type="text" id="username" name="username" class="form-control"
                                    value="<?php echo htmlspecialchars($user['username']); ?>" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="email">Email address</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    value="<?php echo htmlspecialchars($user['email']); ?>" required />
                            </div>
                        </fieldset>

                        <fieldset class="mb-3">
                            <legend class="fs-5">Security</legend>
                            <div class="mb-3">
                                <label class="form-label" for="password">Password <small class="text-muted">(leave blank to keep current)</small></label>
                                <input type="password" id="password" name="password" class="form-control" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="active">Status</label>
                                <select name="active" class="form-select" id="active" required>
                                    <option value="1" <?php echo ($user['active'] ? 'selected' : ''); ?>>Active</option>
                                    <option value="0" <?php echo (!$user['active'] ? 'selected' : ''); ?>>Inactive</option>
                                </select>
                            </div>
                        </fieldset>

                        <button type="submit" class="btn btn-primary w-100">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
        } else {
            echo '<div class="container mt-5"><div class="alert alert-warning">User not found.</div></div>';
        }
    } else {
        echo '<div class="container mt-5"><div class="alert alert-danger">Could not prepare statement!</div></div>';
    }
} else {
    echo '<div class="container mt-5"><div class="alert alert-danger">No user selected.</div></div>';
    die();
}

include('includes/footer.php');
?>
