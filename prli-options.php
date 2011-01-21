<?php
require_once 'prli-config.php';
require_once(PRLI_MODELS_PATH . '/models.inc.php');

$errors = array();

// variables for the field and option names 
$prli_exclude_ips  = 'prli_exclude_ips';
$whitelist_ips = 'prli_whitelist_ips';
$filter_robots = 'prli_filter_robots';
$extended_tracking = 'prli_extended_tracking';

$link_track_me = 'prli_link_track_me';
$link_prefix = 'prli_link_prefix';
$link_nofollow = 'prli_link_nofollow';
$link_redirect_type = 'prli_link_redirect_type';
$hidden_field_name = 'prli_update_options';

$update_message = false;

// See if the user has posted us some information
// If they did, this hidden field will be set to 'Y'
if( isset($_REQUEST[ $hidden_field_name ]) and $_REQUEST[ $hidden_field_name ] == 'Y' ) 
{
  // Validate This
  if( !empty($_POST[ $prli_exclude_ips ]) and !preg_match( "#^[ \t]*((\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)|([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*))([ \t]*,[ \t]*((\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)|([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*)))*$#", $_POST[ $prli_exclude_ips ] ) )
    $errors[] = "Excluded IP Addresses must be a comma separated list of IPv4 or IPv6 addresses or ranges.";

  if( !empty($_POST[ $whitelist_ips ]) and !preg_match( "#^[ \t]*((\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)|([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*))([ \t]*,[ \t]*((\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)\.(\d{1,3}|\*)|([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*):([0-9a-fA-F]{1,4}|\*)))*$#", $_POST[ $whitelist_ips ] ) )
    $errors[] = "Whitlist IP Addresses must be a comma separated list of IPv4 or IPv6 addresses or ranges.";

  $errors = apply_filters('prli-validate-options',$errors);

  // Read their posted value
  $prli_options->prli_exclude_ips = stripslashes($_POST[ $prli_exclude_ips ]);
  $prli_options->whitelist_ips = stripslashes($_POST[ $whitelist_ips ]);
  $prli_options->filter_robots = (int)isset($_POST[ $filter_robots ]);
  $prli_options->extended_tracking = stripslashes($_POST[ $extended_tracking ]);
  $prli_options->link_track_me = (int)isset($_POST[ $link_track_me ]);
  $prli_options->link_prefix = (int)isset($_POST[ $link_prefix ]);
  $prli_options->link_nofollow = (int)isset($_POST[ $link_nofollow ]);
  $prli_options->link_redirect_type = $_POST[ $link_redirect_type ];

  do_action('prli-store-options');

  if( count($errors) > 0 )
    require(PRLI_VIEWS_PATH.'/shared/errors.php');
  else
  {
    // Save the posted value in the database
    update_option( 'prli_options', $prli_options );

    // Put an options updated message on the screen

    $update_message = __('Options saved.');
  }
}
else if(isset($_REQUEST['action']) and $_REQUEST['action'] == 'clear_all_clicks')
{
  $prli_click->clearAllClicks();

  $update_message = __('Hit Database was Cleared.');
}
else if(isset($_REQUEST['action']) and $_REQUEST['action'] == 'clear_30day_clicks')
{
  $num_clicks = $prli_click->clear_clicks_by_age_in_days(30);

  if($num_clicks)
    $update_message = __("Hits older than 30 days ({$num_clicks} Hits) were deleted" );
  else
    $update_message = __("No hits older than 30 days were found, so nothing was deleted" );
}
else if(isset($_REQUEST['action']) and $_REQUEST['action'] == 'clear_90day_clicks')
{
  $num_clicks = $prli_click->clear_clicks_by_age_in_days(90);

  if($num_clicks)
    $update_message = __("Hits older than 90 days ({$num_clicks} Hits) were deleted" );
  else
    $update_message = __("No hits older than 90 days were found, so nothing was deleted" );
}

if($update_message)
{
?>
<div class="updated"><p><strong><?php echo $update_message; ?></strong></p></div>
<?php
}

require_once 'classes/views/prli-options/form.php';

?>
