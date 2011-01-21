<script type="text/javascript">
jQuery(document).ready(function() {
<?php do_action('prli_options_js'); ?>

  jQuery('.reporting-expand').show();
  jQuery('.reporting-collapse').hide();
  jQuery('.reporting-toggle-pane').hide();
  jQuery('.reporting-toggle-button').click(function() {
    jQuery('.reporting-toggle-pane').toggle();
    jQuery('.reporting-expand').toggle();
    jQuery('.reporting-collapse').toggle();
  });

  jQuery('.link-expand').show();
  jQuery('.link-collapse').hide();
  jQuery('.link-toggle-pane').hide();
  jQuery('.link-toggle-button').click(function() {
    jQuery('.link-toggle-pane').toggle();
    jQuery('.link-expand').toggle();
    jQuery('.link-collapse').toggle();
  });

  if (jQuery('.filter-robots-checkbox').is(':checked')) {
    jQuery('.whitelist-ips').show();
  }
  else {
    jQuery('.whitelist-ips').hide();
  }

  jQuery('.filter-robots-checkbox').change(function() {
    if (jQuery('.filter-robots-checkbox').is(':checked')) {
      jQuery('.whitelist-ips').show();
    }
    else {
      jQuery('.whitelist-ips').hide();
    }
  });
});
</script>

<style type="text/css">
.toggle {
  cursor: pointer;
}
</style>

<?php do_action('prli-options-head'); ?>
