<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title><?php echo htmlspecialchars(stripslashes($pretty_link->name)); ?></title>
    <meta name="description" content="<?php echo stripslashes($pretty_link->description); ?>" />
  </head>
  <frameset rows="*" framespacing=0 frameborder=0>
    <frame src="<?php echo $pretty_link_url.$param_string; ?>" frameborder=0 marginwidth=0 marginheight=0>
    <noframes>Your browser does not support frames. Click <a href="<?php echo $pretty_link_url.$param_string; ?>">here</a> to view the page.</noframes>
  </frameset>
</html>
