<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  
  <title>SMS OTP</title>

  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
    crossorigin="anonymous"
  />
  <link
      href="css/styles.css"
      rel="stylesheet"
  />
</head>
<body>
<header>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="/">SMS OTP</a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <?php if (auth()->guest()): ?>
            <li class="nav-item">
              <a class="nav-link" href="/register">Register</a>
            </li>
          <?php endif; ?>
    
          <?php if (auth()->check()): ?>
            <?php if (!auth()->user()->isVerified()): ?>
              <li class="nav-item">
                <a
                  class="nav-link"
                  href="/verify"
                >Verify</a>
              </li>
            <?php endif; ?>
            <li class="nav-item">
              <a
                class="nav-link"
                href="/logout"
                onclick="document.getElementById('logout-form').submit(); return false;"
              >Logout</a>
              <form action="/logout" method="post" id="logout-form"></form>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
</header>
<main class="my-4">
<?php require_once __DIR__ . '/_alerts.php';  ?>
