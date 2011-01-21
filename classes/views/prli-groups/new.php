<div class="wrap">
<h2><img src="<?php echo PRLI_IMAGES_URL.'/pretty-link-med.png'; ?>"/>&nbsp;Pretty Link: Add Group</h2>

<?php
  require(PRLI_VIEWS_PATH.'/shared/errors.php');
?>

<form name="form1" method="post" action="?page=<?php echo PRLI_PLUGIN_NAME ?>/prli-groups.php">
<input type="hidden" name="action" value="create">
<?php wp_nonce_field('update-options'); ?>
<input type="hidden" name="id" value="<?php echo $id; ?>">

<table class="form-table">
  <tr class="form-field">
    <td width="75px" valign="top">Name*: </td>
    <td><input type="text" name="name" value="<?php echo (($_POST['name'] != null)?$_POST['name']:''); ?>" size="75">
      <br/><span class="setting-description">This is how you'll identify your Group.</span></td>
  </tr>
  <tr class="form-field">
    <td valign="top">Description: </td>
    <td><textarea style="height: 100px;" name="description"><?php echo (($_POST['description'] != null)?$_POST['description']:''); ?></textarea>
    <br/><span class="setting-description">A Description of this group.</span></td>
  </tr>
  <tr class="form-field" valign="top">
    <td valign="top">Links: </td>
    <td valign="top">
      <div style="height: 150px; width: 95%; border: 1px solid #8cbdd5; overflow: auto;">
        <table width="100%" cellspacing="0">
          <thead style="background-color: #dedede; padding: 0px; margin: 0px; line-height: 8px; font-size: 14px;">
            <th width="50%" style="padding-left: 5px; margin: 0px;"><strong>Name</strong></th>
            <th width="50%" style="padding-left: 5px; margin: 0px;"><strong>Current Group</strong></th>
          </thead>
          <?php
          for($i = 0; $i < count($links); $i++)
          {
            $link = $links[$i];
            ?>
            <tr style="line-height: 15px; font-size: 12px;<?php echo (($i%2)?' background-color: #efefef;':''); ?>">
              <td style="min-width: 50%; width: 50%;"><input type="checkbox" style="width: 15px;" name="link[<?php echo $link->id; ?>]" <?php echo ((isset($_POST['link'][$link->id]) and $_POST['link'][$link->id] == 'on')?'checked="true"':''); ?>/>&nbsp;<?php echo htmlspecialchars(stripslashes($link->name)) . " <strong>(" . $link->slug . ")</strong>"; ?></td>
              <td style="min-width: 50%; width: 50%;"><?php echo htmlspecialchars(stripslashes($link->group_name)); ?></td>
            </tr>
            <?php
            
          }
          ?>
        </table>
      </div>
      <span class="setting-description">Select some links for this group. <strong>Note: each link can only be in one group at a time.</strong></span></td>
    </td>
  </tr>
</table>
</div>

<p class="submit">
<input type="submit" name="Submit" value="Create" />&nbsp;or&nbsp;<a href="?page=<?php echo PRLI_PLUGIN_NAME ?>/prli-groups.php">Cancel</a>
</p>

</form>
</div>
