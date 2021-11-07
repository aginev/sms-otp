<?php require_once __DIR__ . '/_header.php';  ?>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-4">
      <h1 class="mb-3">Verify Your Phone</h1>
        
      <?php if (session()->hasFlash('errors')): ?>
          <?php require_once __DIR__ . '/_form-errors.php';  ?>
      <?php endif; ?>

      <?php if ($isAllowedToVerify): ?>
        <form action="/verify" method="post">
          <div class="mb-3">
            <label for="code" class="form-label">Verification Code</label>
            <input
              type="text"
              inputmode="numeric"
              autocomplete="one-time-code"
              class="form-control"
              id="code"
              name="code"
              required
              pattern="\d{6}"
            >
          </div>
          <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary">Verify</button>
          </div>
        </form>
      <?php else: ?>
        <p><strong>You are not allowed to verify your phone so often.</strong></p>
        <p>Wait 1 minute before to try again!</p>
      <?php endif; ?>
      
      <?php if ($isAllowedToIssueNewVerificationCode): ?>
        <form action="/issue-new-verification-code" method="post">
          <p>Still didn't received your phone verification code?</p>
          <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary">Issue New One Here</button>
          </div>
        </form>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/_footer.php';  ?>
