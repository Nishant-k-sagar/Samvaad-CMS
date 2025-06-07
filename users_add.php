<?php

include('includes/config.php');
include('includes/database.php');
include('includes/functions.php');
secure();

include('includes/header.php');

$message = '';

if (isset($_POST['username'])) {

    // Basic server-side validation (you can extend as needed)
    if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])) {
        $message = '<div class="alert alert-danger mb-4">Please fill all required fields.</div>';
    } else {

        if ($stm = $connect->prepare('INSERT INTO users (username, email, password, active) VALUES (?, ?, ?, ?)')) {
            // Use SHA1 as in your original, but better to use password_hash() for production
            $hashed = sha1($_POST['password']);
            $username = $_POST['username'];
            $email = $_POST['email'];
            $active = (int)$_POST['active'];

            $stm->bind_param('sssi', $username, $email, $hashed, $active);
            $stm->execute();
            $stm->close();

            set_message("A new user '$username' has been added.");
            header('Location: users.php');
            die();
        } else {
            $message = '<div class="alert alert-danger mb-4">Could not prepare statement!</div>';
        }
    }
}

?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow border-0 rounded">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-center">Add New User</h2>
                    <?php echo $message; ?>

                    <form method="post" novalidate>
                        <fieldset class="mb-3">
                            <legend class="fs-5">User Information</legend>

                            <div class="mb-3">
                                <label class="form-label" for="username">Username</label>
                                <input type="text" id="username" name="username" class="form-control" required />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="email">Email address</label>
                                <input type="email" id="email" name="email" class="form-control" required />
                            </div>
                        </fieldset>

                        <fieldset class="mb-3">
                            <legend class="fs-5">Security</legend>

                            <div class="mb-3">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control" required />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="active">Status</label>
                                <select name="active" class="form-select" id="active">
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </fieldset>

                        <button type="submit" class="btn btn-success w-100">Add User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

include('includes/footer.php');
?>
