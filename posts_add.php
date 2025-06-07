<?php

include('includes/config.php');
include('includes/database.php');
include('includes/functions.php');
secure();

include('includes/header.php');

$message = '';

if (isset($_POST['title'])){

    // Default date to today if empty
    $date = !empty($_POST['date']) ? $_POST['date'] : date('Y-m-d');

    if ($stm = $connect->prepare('INSERT INTO posts (title, content, author, date) VALUES (?, ?, ?, ?)')){
        
        $stm->bind_param('ssis', $_POST['title'], $_POST['content'], $_SESSION['id'], $date);
        $stm->execute();

        set_message("A new post by " . htmlspecialchars($_SESSION['username']) . " has been added.");
        header('Location: posts.php');
        $stm->close();
        die();

    } else {
        $message = '<div class="alert alert-danger mb-4">Could not prepare statement!</div>';
    }
}

?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow border-0 rounded">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-center">Add New Post</h2>
                    <?php echo $message; ?>

                    <form method="post" novalidate>
                        <fieldset class="mb-3">
                            <legend class="fs-5">Post Information</legend>
                            <div class="mb-3">
                                <label class="form-label" for="title">Title</label>
                                <input type="text" id="title" name="title" class="form-control" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="content">Content</label>
                                <textarea name="content" id="content" class="form-control" rows="10" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="date">Date</label>
                                <input type="date" id="date" name="date" class="form-control" />
                            </div>
                        </fieldset>
                        <button type="submit" class="btn btn-success w-100">Add Post</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/tinymce/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#content',
        height: 300,
        menubar: false,
        plugins: [
            'advlist autolink lists link charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
        ],
        toolbar: 'undo redo | formatselect | bold italic backcolor | ' +
                 'alignleft aligncenter alignright alignjustify | ' +
                 'bullist numlist outdent indent | removeformat | help'
    });
</script>

<?php
include('includes/footer.php');
?>
