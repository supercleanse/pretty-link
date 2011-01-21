<?php
require_once(PRLI_MODELS_PATH.'/PrliLink.php');
require_once(PRLI_MODELS_PATH.'/PrliClick.php');
require_once(PRLI_MODELS_PATH.'/PrliGroup.php');
require_once(PRLI_MODELS_PATH.'/PrliUtils.php');
require_once(PRLI_MODELS_PATH.'/PrliUrlUtils.php');
require_once(PRLI_MODELS_PATH.'/PrliLinkMeta.php');
require_once(PRLI_MODELS_PATH.'/PrliUpdate.php');

global $prli_link;
global $prli_link_meta;
global $prli_click;
global $prli_group;
global $prli_utils;
global $prli_url_utils;
global $prli_update;

$prli_link      = new PrliLink();
$prli_link_meta = new PrliLinkMeta();
$prli_click     = new PrliClick();
$prli_group     = new PrliGroup();
$prli_utils     = new PrliUtils();
$prli_url_utils = new PrliUrlUtils();
$prli_update    = new PrliUpdate();

function prli_get_main_message( $message = "Get started by <a href=\"?page=pretty-link/prli-links.php&action=new\">adding a URL</a> that you want to turn into a pretty link.<br/>Come back to see how many times it was clicked.", $expiration=3600) // Get new messages every 1 hour
{
  global $prli_update, $wp_version;
  include_once(ABSPATH."/wp-includes/class-IXR.php");

  $message_mothership = (($prli_update->pro_is_installed_and_authorized())?'http://prettylinkpro.com/xmlrpc.php':'http://blairwilliams.com/xmlrpc.php');

  if( version_compare($wp_version, '3.0', '>=') )
    $messages = get_site_transient('_prli_messages'); // for WordPress 3.0
  else
    $messages = get_transient('_prli_messages'); // for WordPress 2.8+

  // if the messages array has expired go back to the mothership
  if($messages === false)
  {
    $client = new IXR_Client($message_mothership);
    if ($client->query('prli.get_main_message_array'))
      $messages = $client->getResponse();

    // If we're having connection issues on the mothership then store the default message in the transient
    if(empty($messages) or !$messages or !is_array($messages))
      $messages = array($message);

    if( version_compare($wp_version, '3.0', '>=') )
      set_site_transient("_prli_messages", $messages, $expiration); // for WordPress 3.0
    else
      set_transient("_prli_messages", $messages, $expiration); // for WordPress 2.8+
  }

  if(empty($messages) or !$messages or !is_array($messages))
    return $message;
  else
    return $messages[array_rand($messages)];
}

?>
