<div class="wrap">
  <a href="http://blairwilliams.com/pretty-link"><img style="float: left; border: 0px;" src="<?php echo PRLI_IMAGES_URL . '/prettylink_logo_small.jpg'; ?>"/></a><div style="min-height: 48px;"><div style="min-height: 18px; margin-left: 137px; margin-top: 0px; padding-top: 0px; border: 1px solid #e5e597; background-color: #ffffa0; display: block;"><p style="font-size: 11px; margin:0px; padding: 0px; padding-left: 10px;"><?php echo $message; ?></p></div></div>

<form name="form1" method="post" action="?page=<?php echo PRLI_PLUGIN_NAME ?>/prli-links.php">
<input type="hidden" name="action" value="quick-create">
<?php wp_nonce_field('update-options'); ?>

<table class="form-table">
  <tr class="form-field">
    <td valign="top">Target URL</td>
    <td><input type="text" name="url" value="" size="75">
  </tr>
  <tr>
    <td valign="top">Pretty Link</td>
    <td><strong><?php echo $prli_blogurl; ?></strong>/<input type="text" name="slug" value="<?php echo $prli_link->generateValidSlug(); ?>">
  </tr>
</table>

<p class="submit">
<input type="submit" name="Submit" value="Create" />
</p>
</form>
</div>
