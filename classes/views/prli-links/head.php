<script type="text/javascript">
jQuery(document).ready(function() {
  jQuery('.link_actions').hide();
  jQuery('.edit_link').hover(
    function() {
      jQuery(this).children(".link_actions").show();
    },
    function() {
      jQuery(this).children(".link_actions").hide();
    }
  );

  jQuery(".options-table").hide();
  jQuery(".options-table-toggle > .expand-options").show();
  jQuery(".options-table-toggle > .collapse-options").hide();
  jQuery(".options-table-toggle").click( function () {
      jQuery(this).children(".expand-options").toggle();
      jQuery(this).children(".collapse-options").toggle();
      jQuery(".expand-collapse").toggle();
      jQuery(".options-table").toggle();
  });

  jQuery(".toggle_pane").hide();
  jQuery(".toggle").click( function () {
      jQuery(this).next(".toggle_pane").toggle();
  });
  jQuery(".expand-all").click( function () {
      jQuery(".toggle_pane").show();
      jQuery(".expand-all").hide();
      jQuery(".collapse-all").show();
  });
  jQuery(".collapse-all").click( function () {
      jQuery(".toggle_pane").hide();
      jQuery(".expand-all").show();
      jQuery(".collapse-all").hide();
  });
});
</script>

<style type="text/css">

.options-table {
  width: 80%;
  margin-top: 10px;
}

.options-table td {
  padding: 10px;
  background-color: #f4f0db;
}

.options-table h3 {
  padding: 0px;
  margin: 0px;
  padding-left: 10px;
}

.expand-all, .collapse-all, .options-table-toggle {
  cursor: pointer;
}

.toggle {
  line-height: 34px;
  font-size: 12px;
  font-weight: bold;
  padding-bottom: 10px;
  cursor: pointer;
}

.pane {
  background-color: #f4f0db;
  padding-left: 10px;
}

ul.pane li {
  padding: 0px;
  margin: 0px;
}

.edit_link {
  height: 50px;
}

.slug_name {
  font-size: 12px;
  font-weight: bold;
}
.link_actions {
  padding-top: 5px;
}
</style>
