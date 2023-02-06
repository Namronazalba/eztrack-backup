<!-- Error input confirm password -->
<?php  if (count($error4) > 0) : ?>
  <div class="error_input">
    <?php foreach ($error4 as $error_4) : ?>
        <p><?php echo $error_4 ?></p>
  	<?php endforeach ?>
  </div>
<?php  endif ?>

