<?php
/* @var $user \SmsOtp\Models\User */
?>
<?php require_once __DIR__ . '/_header.php';  ?>

<div class="container">
  <h1>Hello, <?php echo auth()->user()->phone ?></h1>
</div>

<?php require_once __DIR__ . '/_footer.php';  ?>
