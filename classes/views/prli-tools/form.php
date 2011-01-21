<div class="wrap">
<script type="text/javascript">
function toggle_iphone_instructions()
{
  jQuery('.iphone_instructions').slideToggle();
}

</script>
<?php
  require(PRLI_VIEWS_PATH.'/shared/nav.php');
?>
  <h2><img src="<?php echo PRLI_IMAGES_URL.'/pretty-link-med.png'; ?>"/>&nbsp;Pretty Link: Tools</h2>
  <h3>Bookmarklet: </h3>
  <p><strong><a href="javascript:location.href='<?php echo PRLI_URL; ?>/prli-bookmarklet.php?k=<?php echo $prli_options->bookmarklet_auth; ?>&target_url='+escape(location.href);">Get PrettyLink</a></strong><br/>
  <span class="description">Just drag this "Get PrettyLink" link to your toolbar to install the bookmarklet. As you browse the web, you can just click this bookmarklet to create a pretty link from the current url you're looking at.&nbsp;&nbsp;<a href="http://blairwilliams.com/pretty-link-bookmarklet/">(more help)</a></span>
  <br/><br/><a href="javascript:toggle_iphone_instructions()"><strong><?php _e('Show iPhone Bookmarklet Instructions'); ?></strong></a>
  <div class="iphone_instructions" style="display: none"><strong>Note:</strong> iPhone users can install this bookmarklet in their Safari to create Pretty Links with the following steps:<br/>
    <ol>
      <li>Copy this text:<br/><code>javascript:location.href='<?php echo PRLI_URL; ?>/prli-bookmarklet.php?k=<?php echo $prli_options->bookmarklet_auth; ?>&target_url='+escape(location.href);</code></li>
      <li>Tap the + button at the bottom of the screen</li>
      <li>Choose "Add Bookmark", rename your bookmark to "Get PrettyLink" (or whatever you want) and then "Save"</li>
      <li>Navigate through your Bookmarks folders until you find the new bookmark and click "Edit"</li>
      <li>Delete all the text from the address</li>
      <li>Paste the text you copied in Step 1 into the address field</li>
      <li>To save the changes hit "Bookmarks" and <strong>you're done!</strong> Now when you find a page you want to save off as a Pretty Link, just click the "Bookmarks" icon at the bottom of the screen and select your link.</li>
    </ol>
  </div>
<?php do_action('prli-add-tools'); ?>
</div>
