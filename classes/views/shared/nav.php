<?php
  global $prli_update;
  
  if($prli_update->pro_is_installed_and_authorized())
    $support_link = "&nbsp;|&nbsp;<a href=\"http://prettylinkpro.com/user-manual\">" . __('Pro Manual') . '</a>';
  else
    $support_link = "&nbsp;|&nbsp;<a href=\"http://prettylinkpro.com\">" . __('Upgrade to Pro') . '</a>';
?>
<p style="font-size: 14px; font-weight: bold; float: right; text-align: right; padding-top: 0px; padding-right: 10px;"><?php _e('Connect'); ?>:&nbsp;&nbsp;<a href="http://twitter.com/blairwilli"><img src="<?php echo PRLI_IMAGES_URL; ?>/twitter_32.png" style="width: 24px; height: 24px;" /></a>&nbsp;<a href="http://www.facebook.com/pages/Pretty-Link/283252860401"><img src="<?php echo PRLI_IMAGES_URL; ?>/facebook_32.png" style="width: 24px; height: 24px;" /></a><br/><?php _e('Get Help'); ?>:&nbsp;&nbsp;<a href="http://blairwilliams.com/xba" target="_blank"><?php _e('Tutorials'); ?></a><?php echo $support_link; ?>&nbsp;|&nbsp;<a href="http://blairwilliams.com/work"><?php _e('One on One'); ?></a></p>
