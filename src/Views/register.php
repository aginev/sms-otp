<?php require_once __DIR__ . '/_header.php';  ?>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-4">
      <h1 class="mb-3">Register</h1>
    
      <?php if (session()->hasFlash('errors')): ?>
          <?php require_once __DIR__ . '/_form-errors.php';  ?>
      <?php endif; ?>
  
      <form action="/register" method="post">
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input
              type="email"
              class="form-control"
              id="email"
              placeholder="name@example.com"
              name="email"
              value="<?php echo $input['email'] ?? ''; ?>"
              required
          >
        </div>
        <div class="mb-3">
          <label for="phone" class="form-label">Phone</label>
          <input
              type="text"
              class="form-control"
              id="phone"
              placeholder="+359 88 7123 456"
              name="phone"
              value="<?php echo $input['phone'] ?? ''; ?>"
              required
          >
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
          <label for="confirm_password" class="form-label">Confirm Password</label>
          <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <div class="d-grid mb-3">
          <button type="submit" class="btn btn-primary">Register</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/_footer.php';  ?>
