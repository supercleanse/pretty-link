<?php
$root = dirname(dirname(dirname(dirname(__FILE__))));
if (file_exists($root.'/wp-load.php')) 
  require_once($root.'/wp-load.php');
else
  require_once($root.'/wp-config.php');

require_once('prli-config.php');
require_once(PRLI_MODELS_PATH . '/models.inc.php');

if(isset($_GET['k']))
{
  if($_GET['k'] == $prli_options->bookmarklet_auth)
  {
    $redirect_type = ((isset($_GET['rt']) and $_GET['rt'] != '-1')?$_GET['rt']:'');
    $track = ((isset($_GET['trk']) and $_GET['trk'] != '-1')?$_GET['trk']:'');
    $group = ((isset($_GET['grp']) and $_GET['grp'] != '-1')?$_GET['grp']:'');

    $result = prli_create_pretty_link( $_GET['target_url'], '', '', '', $group, $track, '', $redirect_type );

    $plink = $prli_link->getOne($result);
    $target_url = $plink->url;
    $target_url_title = $plink->name;
    $pretty_link = "{$prli_blogurl}".PrliUtils::get_permalink_pre_slug_uri()."{$plink->slug}";

    $twitter_status = substr($target_url_title,0,(114 - strlen($pretty_link))) . ((strlen($target_url_title) > 114)?"...":'') . " | $pretty_link";
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>Here's your Pretty Link</title>
     <style type="text/css">
       body {
         font-family: Arial;
         text-align: center;
         margin-top: 25px;
       }
       
       h4 {
         font-size: 18px;
         color: #aaaaaa;
       }

       h2 {
         font-size: 24px;
         font-weight: bold;
       }

       h2 a {
         text-decoration: none;
         color: #1f487e;
       }

       h2 a:hover {
         text-decoration: none;
         color: blue;
       }
     </style>
   </head>
   <body>
     <p><img src="<?php echo PRLI_IMAGES_URL; ?>/prettylink_logo.jpg" /></p>
     <h4><em>here's your pretty link for:</em><br/><?php echo $target_url_title; ?><br/>(<span title="<?php echo $target_url; ?>"><?php echo substr($target_url,0,50) . ((strlen($target_url)>50)?"...":''); ?></span>)</h4>
     <h2><a href="<?php echo $pretty_link; ?>"><?php echo $pretty_link; ?></a></h2>
     <p>send this link to:<br/>
     <a href="http://del.icio.us/post?url=<?php echo urlencode($pretty_link) ?>&title=<?php echo urlencode($target_url_title); ?>" target="_blank"><img src="<?php echo PRLI_IMAGES_URL; ?>/delicious_32.png" title="delicious" width="32px" height="32px" border="0" /></a>&nbsp;&nbsp;
     <a href="http://www.stumbleupon.com/submit?url=<?php echo urlencode($pretty_link) ?>&title=<?php echo urlencode($target_url_title); ?>" target="_blank"><img src="<?php echo PRLI_IMAGES_URL; ?>/stumbleupon_32.png" title="stumbleupon" width="32px" height="32px" border="0" /></a>&nbsp;&nbsp;
     <a href="http://digg.com/submit?phase=2&url=<?php echo urlencode($pretty_link) ?>&title=<?php echo urlencode($target_url_title); ?>" target="_blank"><img src="<?php echo PRLI_IMAGES_URL; ?>/digg_32.png" title="digg" width="32px" height="32px" border="0" /></a>&nbsp;&nbsp;
     <a href="http://twitter.com/home?status=<?php echo urlencode($twitter_status); ?>" target="_blank"><img src="<?php echo PRLI_IMAGES_URL; ?>/twitter_32.png" title="twitter" width="32px" height="32px" border="0" /></a>&nbsp;&nbsp;
     <a href="http://www.mixx.com/submit?page_url=<?php echo urlencode($pretty_link) ?>&title=<?php echo urlencode($target_url_title); ?>" target="_blank"><img src="<?php echo PRLI_IMAGES_URL; ?>/mixx_32.png" title="mixx" width="32px" height="32px" border="0" /></a>&nbsp;&nbsp;
     <a href="http://technorati.com/faves?add=<?php echo urlencode($pretty_link) ?>" target="_blank"><img src="<?php echo PRLI_IMAGES_URL; ?>/technorati_32.png" title="technorati" width="32px" height="32px" border="0" /></a>&nbsp;&nbsp;
     <a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode($pretty_link) ?>&t=<?php echo urlencode($target_url_title); ?>" target="_blank"><img src="<?php echo PRLI_IMAGES_URL; ?>/facebook_32.png" title="facebook" width="32px" height="32px" border="0" /></a>&nbsp;&nbsp;
     <a href="http://www.newsvine.com/_tools/seed&save?u=<?php echo urlencode($pretty_link) ?>&h=<?php echo urlencode($target_url_title); ?>" target="_blank"><img src="<?php echo PRLI_IMAGES_URL; ?>/newsvine_32.png" title="news vine" width="32px" height="32px" border="0" /></a>&nbsp;&nbsp;
     <a href="http://reddit.com/submit?url=<?php echo urlencode($pretty_link) ?>&title=<?php echo urlencode($target_url_title); ?>" target="_blank"><img src="<?php echo PRLI_IMAGES_URL; ?>/reddit_32.png" title="reddit" width="32px" height="32px" border="0" /></a>&nbsp;&nbsp;
     <a href="http://www.linkedin.com/sharearticle?mini=true&url=<?php echo urlencode($pretty_link) ?>&title=<?php echo urlencode($target_url_title); ?>" target="_blank"><img src="<?php echo PRLI_IMAGES_URL; ?>/linkedin_32.png" title="linkedin" width="32px" height="32px" border="0" /></a>&nbsp;&nbsp;
     <a href="http://myweb2.search.yahoo.com/myresults/bookmarklet?u=<?php echo urlencode($pretty_link) ?>&=<?php echo urlencode($target_url_title); ?>" target="_blank"><img src="<?php echo PRLI_IMAGES_URL; ?>/yahoobuzz_32.png" title="yahoo! bookmarks" width="32px" height="32px" border="0" /></a>&nbsp;&nbsp;
     <p><a href="<?php echo $_GET['target_url']; ?>">&laquo; back</a></p>
   </body>
 </html>
  <?php
  }
  else
  {
    wp_redirect($prli_blogurl);
    exit;
  }
}
else
{
  wp_redirect($prli_blogurl);
  exit;
}
?>
