<div class="wrap">
        <div id="icon-options-general" class="icon32"><br /></div>
<h2 id="prli_title">Pretty Link: Options</h2>
<br/>
<?php
$permalink_structure = get_option('permalink_structure');
if(!$permalink_structure or empty($permalink_structure))
{
?>
  <div class="error" style="padding-top: 5px; padding-bottom: 5px;"><strong>WordPress Must be Configured:</strong> Pretty Link won't work until you select a Permalink Structure other than "Default" ... <a href="<?php echo $prli_siteurl; ?>/wp-admin/options-permalink.php">Permalink Settings</a></div>
<?php
}
?>
<?php do_action('prli-options-message'); ?>
<a href="admin.php?page=<?php echo PRLI_PLUGIN_NAME; ?>/prli-links.php">&laquo Pretty Link Admin</a>

<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<?php wp_nonce_field('update-options'); ?>

<h3><a class="toggle link-toggle-button">Link Option Defaults <span class="link-expand" style="display: none;">[+]</span><span class="link-collapse">[-]</span></a></h3>
<ul class="link-toggle-pane" style="list-style-type: none;">
  <li>
    <input type="checkbox" name="<?php echo $link_track_me; ?>" <?php echo (($prli_options->link_track_me != 0)?'checked="true"':''); ?>/>&nbsp; Track Link
    <br/><span class="description">Default all new links to be tracked.</span>
  </li>
  <li>
    <input type="checkbox" name="<?php echo $link_nofollow; ?>" <?php echo (($prli_options->link_nofollow != 0)?'checked="true"':''); ?>/>&nbsp; Add <code>nofollow</code> to Link
<br/><span class="description">Add the <code>nofollow</code> attribute by default to new links.</span>
  </li>
  <li>
    <input type="checkbox" name="<?php echo $link_prefix; ?>" <?php echo (($prli_options->link_prefix != 0)?'checked="true"':''); ?>/>&nbsp; Use a prefix from your Permalink structure in your Pretty Links
<br/><span class="description">This option should only be checked if you have elements in your permalink structure that must be present in any link on your site. For example, some WordPress installs don't have the benefit of full rewrite capabilities and in this case you'd need an index.php included in each link (http://example.com/index.php/mycoolslug instead of http://example.com/mycoolslug). If this is the case for you then check this option but the vast majority of users will want to keep this unchecked.</span>
  </li>
  <li>
    <h4>Default Link Redirection Type:</h4>
    <select name="<?php echo $link_redirect_type; ?>">
        <option value="307" <?php echo (($prli_options->link_redirect_type == '307')?' selected="selected"':''); ?>/>Temporary (307)</option>
        <option value="301" <?php echo (($prli_options->link_redirect_type == '301')?' selected="selected"':''); ?>/>Permanent (301)</option>
        <?php do_action('prli_default_redirection_types',$prli_options->link_redirect_type); ?>
    </select>
    <br/><span class="description">Select the type of redirection you want your newly created links to have.</span>
  </li>
</ul>
<?php do_action('prli_custom_option_pane'); ?>
<h3><a class="toggle reporting-toggle-button">Reporting Options <span class="reporting-expand" style="display: none;">[+]</span><span class="reporting-collapse">[-]</span></a></h3>
<table class="reporting-toggle-pane form-table">
  <tr class="form-field">
    <td valign="top">Excluded IP Addresses: </td>
    <td>
      <input type="text" name="<?php echo $prli_exclude_ips; ?>" value="<?php echo $prli_options->prli_exclude_ips; ?>"> 
      <br/><span class="description">Enter IP Addresses or IP Ranges you want to exclude from your Hit data and Stats. Each IP Address should be separated by commas. Example: <code>192.168.0.1, 192.168.2.1, 192.168.3.4 or 192.168.*.*</code></span>
      <br/><span class="description" style="color: red;">Your Current IP Address is <?php echo $_SERVER['REMOTE_ADDR']; ?></span>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <input type="checkbox" class="filter-robots-checkbox" name="<?php echo $filter_robots; ?>" <?php echo (($prli_options->filter_robots != 0)?'checked="true"':''); ?>/>&nbsp; <?php _e('Filter Robots'); ?>
      <br/><span class="description"><?php _e('Filter known Robots and unidentifiable browser clients from your hit data, stats and reports. <code>IMPORTANT: Any robot hits recorded with any version of Pretty Link before 1.4.22 won\'t be filtered by this setting.</code>'); ?></span>
      <table class="option-pane whitelist-ips">
        <tr class="form-field">
          <td valign="top"><?php _e('Whitelist IP Addresses:'); ?>&nbsp;</td>
          <td>
            <input type="text" name="<?php echo $whitelist_ips; ?>" value="<?php echo $prli_options->whitelist_ips; ?>"> 
            <br/><span class="description"><?php _e('Enter IP Addresses or IP Ranges you want to always include in your Hit data and Stats even if they are flagged as robots. Each IP Address should be separated by commas. Example: <code>192.168.0.1, 192.168.2.1, 192.168.3.4 or 192.168.*.*</code>'); ?></span>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <h4><?php _e('Tracking Style:'); ?></h4><span class="description"><code><?php _e('Note: Changing your tracking style can affect the accuracy of your existing statistics.'); ?></code></span>
      <div id="option-pane">
        <ul style="list-style-type: none;" class="pane">
          <li>
            <input type="radio" name="<?php echo $extended_tracking; ?>" value="normal"<?php echo (($prli_options->extended_tracking == 'normal')?' checked="checked"':''); ?>/>&nbsp;<?php _e('Normal Tracking'); ?>
          </li>
          <li>
            <input type="radio" name="<?php echo $extended_tracking; ?>" value="extended"<?php echo (($prli_options->extended_tracking == 'extended')?' checked="checked"':''); ?>/>&nbsp;<?php _e('Extended Tracking (more stats / slower performance)'); ?>
          </li>
          <li>
            <input type="radio" name="<?php echo $extended_tracking; ?>" value="count"<?php echo (($prli_options->extended_tracking == 'count')?' checked="checked"':''); ?>/>&nbsp;<?php _e('Simple Click Count Tracking (less stats / faster performance)'); ?>
          </li>
        </ul>
      </div>
    </td>
  </tr>
</table>

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options') ?>" />
</p>


<h3>Trim Hit Database</h3>

<p><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>&action=clear_30day_clicks" onclick="return confirm('***WARNING*** If you click OK you will delete ALL of the Hit data that is older than 30 days. Your data will be gone forever -- no way to retreive it. Do not click OK unless you are absolutely sure you want to delete this data because there is no going back!');">Delete Hits older than 30 days</a>
<br/><span class="description">This will clear all hits in your database that are older than 30 days.</span></p>

<p><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>&action=clear_90day_clicks" onclick="return confirm('***WARNING*** If you click OK you will delete ALL of the Hit data that is older than 90 days. Your data will be gone forever -- no way to retreive it. Do not click OK unless you are absolutely sure you want to delete this data because there is no going back!');">Delete Hits older than 90 days</a>
<br/><span class="description">This will clear all hits in your database that are older than 90 days.</span></p>

<p><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>&action=clear_all_clicks" onclick="return confirm('***WARNING*** If you click OK you will delete ALL of the Hit data in your Database. Your data will be gone forever -- no way to retreive it. Do not click OK unless you are absolutely sure you want to delete all your data because there is no going back!');">Delete All Hits</a>
<br/><span class="description">Seriously, only click this link if you want to delete all the Hit data in your database.</span></p>

</form>
</div>
