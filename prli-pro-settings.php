<?php
require_once 'prli-config.php';
require_once(PRLI_MODELS_PATH . '/models.inc.php');

$errors = array();

// variables for the field and option names 
$prlipro_username = 'prlipro_username';
$prlipro_password = 'prlipro_password';
$hidden_field_name = 'prli_update_options';

// Read in existing option value from database
$prlipro_username_val = get_option( $prlipro_username );
$prlipro_password_val = get_option( $prlipro_password );

if($_GET['action'] == 'force-pro-reinstall')
{
  // Queue the update and auto upgrade
  $prli_update->queue_update(true);
  $reinstall_url = wp_nonce_url('update.php?action=upgrade-plugin&plugin=pretty-link/pretty-link.php', 'upgrade-plugin_pretty-link/pretty-link.php');
?>
<div class="updated"><p><strong><?php printf(__('You\'re almost done!<br/>%1$sFinish your Re-Install of Pretty Link Pro%2$s'), '<a href="'.$reinstall_url.'">', '</a>'); ?></strong></p></div>
<?php
}
if($_GET['action'] == 'pro-uninstall')
{
  $prli_utils->uninstall_pro();
  ?>
  
  <div class="updated"><p><strong><?php _e('Pretty Link Pro Successfully Uninstalled.' ); ?></strong></p></div>
  <?php
}

require_once 'classes/views/prli-options/pro-settings.php';
