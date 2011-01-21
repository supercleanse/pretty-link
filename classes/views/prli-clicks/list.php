<div class="wrap">
<?php
  require(PRLI_VIEWS_PATH.'/shared/nav.php');
?>
  <h2><img src="<?php echo PRLI_IMAGES_URL.'/pretty-link-med.png'; ?>"/>&nbsp;Pretty Link: Hits</h2>
  <span style="font-size: 14px; font-weight: bold;">For <?php echo stripslashes($link_name); ?>: </span>
  <?php
  // Don't show this sheesh if we're displaying the vuid or ip grouping
  if(empty($params['ip']) and empty($params['vuid']))
  {
  ?>
  <a href="#" style="display:inline;" class="filter_toggle">Customize Report</a>
  <?php
  }
  ?>
<?php
  if(!empty($params['l']) and $params['l'] != 'all')
    echo '<br/><a href="?page='. PRLI_PLUGIN_NAME .'/prli-links.php">&laquo Back to Links</a>';
  else if(!empty($params['ip']) or !empty($params['vuid']))
    echo '<br/><a href="?page='. PRLI_PLUGIN_NAME .'/prli-clicks.php">&laquo Back to Hits</a>';

  if(empty($params['ip']) and empty($params['vuid']))
  {
?>


<div class="filter_pane">
  <form class="form-fields" name="form2" method="post" action="">
    <?php wp_nonce_field('prli-reports'); ?>
    <span>Type:</span>&nbsp;
    <select id="type" name="type" style="display: inline;">
      <option value="all"<?php print ((empty($params['type']) or $params['type'] == "all")?" selected=\"true\"":""); ?>>All Hits&nbsp;</option>
      <option value="unique"<?php print (($params['type'] == "unique")?" selected=\"true\"":""); ?>>Unique Hits&nbsp;</option>
    </select>
    <br/>
    <br/>
    <span>Date Range:</span>
    <div id="dateselectors" style="display: inline;">
      <input type="text" name="sdate" id="sdate" value="<?php echo $params['sdate']; ?>" style="display:inline;"/>&nbsp;to&nbsp;<input type="text" name="edate" id="edate" value="<?php echo $params['edate']; ?>" style="display:inline;"/>
    </div>
    <br/>
    <br/>
    <div class="submit" style="display: inline;"><input type="submit" name="Submit" value="Customize"/> or <a href="#" class="filter_toggle">Cancel</a></div>
  </form>
</div>

  <div id="my_chart"></div>

<?php
  }
  $navstyle = "float: right;";
  require(PRLI_VIEWS_PATH.'/shared/table-nav.php');
?>

  <div id="search_pane" style="padding-top: 5px;">
    <form class="form-fields" name="click_form" method="post" action="">
      <?php wp_nonce_field('prli-clicks'); ?>

      <input type="hidden" name="sort" id="sort" value="<?php echo $sort_str; ?>" />
      <input type="hidden" name="sdir" id="sort" value="<?php echo $sdir_str; ?>" />
      <input type="text" name="search" id="search" value="<?php echo $search_str; ?>" style="display:inline;"/>
      <div class="submit" style="display: inline;"><input type="submit" name="Submit" value="Search Hits"/>
      <?php
      if(!empty($search_str))
      {
      ?>
      or <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php<?php echo (!empty($params['l'])?'&l='.$params['l']:''); ?>">Reset</a>
      <?php
      }
      ?>
      </div>
    </form>
  </div>
<table class="widefat post fixed" cellspacing="0">
    <thead>
    <tr>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
      <th class="manage-column" width="5%"><a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php<?php echo $sort_params; ?>&sort=btype<?php echo (($sort_str == 'btype' and $sdir_str == 'asc')?'&sdir=desc':''); ?>">Browser<?php echo (($sort_str == 'btype')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
    <?php } ?>
      <th class="manage-column" width="12%">
        <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php<?php echo $sort_params; ?>&sort=ip<?php echo (($sort_str == 'ip' and $sdir_str == 'asc')?'&sdir=desc':''); ?>">IP<?php echo (($sort_str == 'ip')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
      <th class="manage-column" width="12%">
        <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php<?php echo $sort_params; ?>&sort=vuid<?php echo (($sort_str == 'vuid' and $sdir_str == 'asc')?'&sdir=desc':''); ?>">Visitor<?php echo (($sort_str == 'vuid')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
    <?php } ?>
      <th class="manage-column" width="13%">
        <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php<?php echo $sort_params; ?>&sort=created_at<?php echo (($sort_str == 'created_at' and $sdir_str == 'asc')?'&sdir=desc':''); ?>">Timestamp<?php echo ((empty($sort_str) or $sort_str == 'created_at')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.((empty($sort_str) or $sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
      <th class="manage-column" width="16%">
        <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php<?php echo $sort_params; ?>&sort=host<?php echo (($sort_str == 'host' and $sdir_str == 'asc')?'&sdir=desc':''); ?>">Host<?php echo (($sort_str == 'host')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
    <?php } ?>
      <th class="manage-column" width="16%">
        <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php<?php echo $sort_params; ?>&sort=uri<?php echo (($sort_str == 'uri' and $sdir_str == 'asc')?'&sdir=desc':''); ?>">URI<?php echo (($sort_str == 'uri')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
      <th class="manage-column" width="16%">
        <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php<?php echo $sort_params; ?>&sort=referer<?php echo (($sort_str == 'referer' and $sdir_str == 'asc')?'&sdir=desc':''); ?>">Referrer<?php echo (($sort_str == 'referer')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
      <th class="manage-column" width="13%">
        <a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php<?php echo $sort_params; ?>&sort=link<?php echo (($sort_str == 'link' and $sdir_str == 'asc')?'&sdir=desc':''); ?>">Link<?php echo (($sort_str == 'link')?'&nbsp;&nbsp;&nbsp;<img src="'.PRLI_IMAGES_URL.'/'.(($sdir_str == 'desc')?'arrow_down.png':'arrow_up.png').'"/>':'') ?></a>
      </th>
    </tr>
    </thead>
  <?php

  if(count($clicks) <= 0)
  {
      ?>
    <tr>
      <td colspan="7">No Hits have been recorded yet</td>
    </tr>
    <?php
  }
  else
  {
    foreach($clicks as $click)
    {
      ?>
      <tr>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
        <td><img src="http://d14715w921jdje.cloudfront.net/browser/<?php echo prli_browser_image($click->btype); ?>" alt="<?php echo $click->btype . " v" . $click->bversion; ?>" title="<?php echo $click->btype . " v" . $click->bversion; ?>"/>&nbsp;<img src="http://d14715w921jdje.cloudfront.net/os/<?php echo prli_os_image($click->os); ?>" alt="<?php echo $click->os; ?>" title="<?php echo $click->os; ?>"/></td>
    <?php } ?>
        <td><a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php&ip=<?php echo $click->ip; ?>" title="View All Activity for IP Address: <?php echo $click->ip; ?>"><?php echo $click->ip; ?> (<?php echo $click->ip_count; ?>)</a></td>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
        <td><a href="?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-clicks.php&vuid=<?php echo $click->vuid; ?>" title="View All Activity for Visitor: <?php echo $click->vuid; ?>"><?php echo $click->vuid; ?><?php echo (($click->vuid != null)?" ($click->vuid_count)":''); ?></a></td>
    <?php } ?>
        <td><?php echo $click->created_at; ?></td>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
        <td><?php echo $click->host; ?></td>
    <?php } ?>
        <td><?php echo $click->uri; ?></td>
        <td><?php echo $click->referer; ?></td>
        <td><a href="?page=<?php print PRLI_PLUGIN_NAME; ?>/prli-clicks.php&l=<?php echo $click->link_id; ?>" title="View clicks for <?php echo stripslashes($click->link_name); ?>"><?php echo stripslashes($click->link_name); ?></a></td>
      </tr>
      <?php
    }
  }
  ?>
    <tfoot>
    <tr>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
      <th class="manage-column">Browser</th>
    <?php } ?>
      <th class="manage-column">IP</th>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
      <th class="manage-column">Visitor</th>
    <?php } ?>
      <th class="manage-column">Timestamp</th>
    <?php if( isset($prli_options->extended_tracking) and $prli_options->extended_tracking == "extended" ) { ?>
      <th class="manage-column">Host</th>
    <?php } ?>
      <th class="manage-column">URI</th>
      <th class="manage-column">Referrer</th>
      <th class="manage-column">Link</th>
    </tr>
    </tfoot>
</table>

<a href="?page=pretty-link/prli-clicks.php&action=csv<?php echo $page_params; ?>">Download CSV (<?php echo stripslashes($link_name); ?>)</a>

<?php
  require(PRLI_VIEWS_PATH.'/shared/table-nav.php');
?>

</div>
