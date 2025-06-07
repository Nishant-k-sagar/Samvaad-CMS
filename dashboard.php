<?php
include('includes/config.php');
include('includes/database.php');
include('includes/functions.php');
secure();
include('includes/header.php');
?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card shadow border-0 rounded">
        <div class="card-body px-4 py-5">
          <h2 class="card-title mb-4 text-center">Dashboard</h2>
          <div class="d-grid gap-3">
            <a href="users.php" class="btn btn-outline-primary btn-lg w-100 rounded-pill d-flex align-items-center justify-content-center gap-3 shadow-sm hover-shadow">
              <span class="fs-3">👥</span> <span>Users Management</span>
            </a>
            <a href="posts.php" class="btn btn-outline-primary btn-lg w-100 rounded-pill d-flex align-items-center justify-content-center gap-3 shadow-sm hover-shadow">
              <span class="fs-3">📝</span> <span>Posts Management</span>
            </a>
            <a href="images.php" class="btn btn-outline-primary btn-lg w-100 rounded-pill d-flex align-items-center justify-content-center gap-3 shadow-sm hover-shadow">
              <span class="fs-3">🖼️</span> <span>Images Management</span>
            </a>
            <a href="images_show.php" class="btn btn-outline-primary btn-lg w-100 rounded-pill d-flex align-items-center justify-content-center gap-3 shadow-sm hover-shadow">
              <span class="fs-3">📷</span> <span>Show Images</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .hover-shadow:hover {
    box-shadow: 0 0.75rem 1.5rem rgba(0, 123, 255, 0.4);
    transform: translateY(-3px);
    transition: all 0.3s ease;
  }
  .hover-shadow {
    transition: all 0.3s ease;
  }
</style>

<?php
include('includes/footer.php');
?>
