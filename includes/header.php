<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Samvaad-CMS</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <!-- Google Fonts Roboto -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
  <!-- MDB (Bootstrap 5) -->
  <link rel="stylesheet" href="css/mdb.min.css" />

  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background: #f8fafc;
    }
    nav.navbar {
      background-color: #fff;
      box-shadow: 0 1px 3px rgba(0,0,0,0.07);
      padding: 0.5rem 1rem;
    }
    .navbar-brand {
      color: #222;
      font-weight: 700;
      font-size: 1.3rem;
      letter-spacing: 0.5px;
      text-decoration: none;
    }
    .navbar-nav .nav-link {
      color: #444;
      font-weight: 500;
      padding: 0.5rem 1rem;
      text-decoration: none;
      transition: color 0.2s;
      border-radius: 0.3rem;
    }
    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active {
      color: #0d6efd;
      background: #f0f4fa;
      text-decoration: underline;
    }
    .navbar-toggler {
      border: none;
      color: #555;
      font-size: 1.25rem;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg mb-3">
    <div class="container">
      <a class="navbar-brand" href="dashboard.php">Samvaad-CMS</a>
      <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto ">
          <li class="nav-item mx-4">
            <a class="nav-link<?php if(basename($_SERVER['PHP_SELF'])=='index.php') echo ' active'; ?>" href="index.php">
              Home
            </a>
          </li>
          <li class="nav-item mx-4">
            <a class="nav-link<?php if(basename($_SERVER['PHP_SELF'])=='dashboard.php') echo ' active'; ?>" href="dashboard.php">
              Dashboard
            </a>
          </li>
          <li class="nav-item mx-4">
            <a class="nav-link" href="logout.php">
              Logout
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Flash/message output in a consistent container -->
  <div class="container mt-3">
    <?php if(function_exists('get_message')) get_message(); ?>
  </div>
