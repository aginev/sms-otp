<div class="alert alert-danger" role="alert">
  <p><strong>Some of the data is invalid!</strong></p>
  <ul>
      <?php foreach (session()->getFlash('errors') as $error): ?>
        <li><?php echo $error; ?></li>
      <?php endforeach; ?>
  </ul>
</div>
