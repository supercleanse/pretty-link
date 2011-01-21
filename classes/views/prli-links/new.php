<div class="wrap">
<h2><img src="<?php echo PRLI_IMAGES_URL . '/pretty-link-med.png'; ?>"/>&nbsp;Pretty Link: Add Link</h2>

<?php
  require(PRLI_VIEWS_PATH.'/shared/errors.php');
?>

<form name="form1" method="post" action="?page=<?php echo PRLI_PLUGIN_NAME ?>/prli-links.php">
<input type="hidden" name="action" value="create">
<?php wp_nonce_field('update-options'); ?>
<input type="hidden" name="id" value="<?php echo $id; ?>">

<?php
  require(PRLI_VIEWS_PATH.'/prli-links/form.php');
?>

<p class="submit">
<input type="submit" name="Submit" value="Create" />&nbsp;or&nbsp;<a href="?page=<?php echo PRLI_PLUGIN_NAME ?>/prli-links.php">Cancel</a>
</p>

</form>
</div>
