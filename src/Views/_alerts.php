<?php if (session()->hasFlash('success')): ?>
  <div class="container">
    <div class="alert alert-success" role="alert"><?php echo session()->getFlash('success'); ?></div>
  </div>
<?php endif; ?>

<?php if (session()->hasFlash('warning')): ?>
  <div class="container">
    <div class="alert alert-warning" role="alert"><?php echo session()->getFlash('warning'); ?></div>
  </div>
<?php endif; ?>

<?php if (session()->hasFlash('danger')): ?>
  <div class="container">
    <div class="alert alert-danger" role="alert"><?php echo session()->getFlash('danger'); ?></div>
  </div>
<?php endif; ?>
