<!-- Error input email -->
<?php  if (count($error2) > 0) : ?>
  <div class="error_input">
    <?php foreach ($error2 as $error_2) : ?>
        <p><?php echo $error_2 ?></p>
  	<?php endforeach ?>
  </div>
<?php  endif ?>

