<!-- Error input username -->
<?php  if (count($error1) > 0) : ?>
  <div class="error_input">
    <?php foreach ($error1 as $error_1) : ?>
        <p><?php echo $error_1 ?></p>
  	<?php endforeach ?>
  </div>
<?php  endif ?>

