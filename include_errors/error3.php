<!-- Error input password -->
<?php  if (count($error3) > 0) : ?>
  <div class="error_input">
    <?php foreach ($error3 as $error_3) : ?>
        <p><?php echo $error_3 ?></p>
  	<?php endforeach ?>
  </div>
<?php  endif ?>

