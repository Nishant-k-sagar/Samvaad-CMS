<?php

include('includes/config.php');
include('includes/database.php');
include('includes/functions.php');

if(isset($_SESSION['id'])){
    header('Location: dashboard.php');
    die();
}

include('includes/header.php');

$message = '';

if (isset($_POST['email'])) {
    if ($stm = $connect->prepare('SELECT * FROM users WHERE email = ? AND password = ? AND active = 1')){
        $hashed = SHA1($_POST['password']);
        $stm->bind_param('ss', $_POST['email'], $hashed);
        $stm->execute();

        $result = $stm->get_result();
        $user = $result->fetch_assoc();

        if ($user){
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];

            set_message("You have successfully logged in " . $_SESSION['username']);
            header('Location: dashboard.php');
            die();
        } else {
            $message = '<div class="alert alert-danger mb-4">Invalid email or password, or your account is inactive.</div>';
        }
        $stm->close();

    } else {
        $message = '<div class="alert alert-danger mb-4">Could not prepare statement!</div>';
    }
}

?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow border-0 rounded">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-center">Sign In</h2>
                    <?php echo $message; ?>
                    <form method="post" novalidate>
                        <div class="mb-3">
                            <label class="form-label" for="email">Email address</label>
                            <input type="email" id="email" name="email" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input type="password" id="password"  name="password" class="form-control" required />
                        </div>
                        <button type="submit" class="btn btn-success w-100">Sign in</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>
