<!-- JQuery UI Includes -->
<link type="text/css" href="<?php echo PRLI_URL; ?>/includes/jquery/css/ui-lightness/jquery-ui-1.7.1.custom.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo PRLI_URL; ?>/includes/jquery/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="<?php echo PRLI_URL; ?>/includes/jquery/js/jquery-ui-1.7.1.custom.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#sdate").datepicker({ dateFormat: 'yy-mm-dd', defaultDate: -30, minDate: -<?php echo $min_date; ?>, maxDate: 0 });
    $("#edate").datepicker({ dateFormat: 'yy-mm-dd', minDate: -<?php echo $min_date; ?>, maxDate: 0 });
  });
</script>

<script type="text/javascript">
$(document).ready(function(){
  $(".filter_pane").hide();
  $(".filter_toggle").click( function () {
      $(".filter_pane").slideToggle("slow");
  });
});
</script>

<style type="text/css">
.filter_toggle {
  line-height: 34px;
  font-size: 14px;
  font-weight: bold;
  padding-bottom: 10px;
}

.filter_pane {
  background-color: white;
  border: 2px solid #777777;
  height: 275px;
  width: 600px;
  padding-left: 20px;
  padding-top: 10px;
}

</style>

<!-- Open Flash Chart Includes -->
<script type="text/javascript" src="<?php echo PRLI_URL; ?>/includes/version-2-kvasir/js/json/json2.js"></script>
<script type="text/javascript" src="<?php echo PRLI_URL; ?>/includes/version-2-kvasir/js/swfobject.js"></script>
<script type="text/javascript">
swfobject.embedSWF("<?php echo PRLI_URL; ?>/includes/version-2-kvasir/open-flash-chart.swf", "my_chart", "100%", "250", "9.0.0");
</script>

<script type="text/javascript">

function ofc_ready() 
{ 
  //alert('ofc_ready');
}

function open_flash_chart_data()
{
  //alert( 'reading data' );
  return JSON.stringify(data);
}

function findSWF(movieName) {
  if (navigator.appName.indexOf("Microsoft")!= -1) {
    return window[movieName];
  } else {
    return document[movieName];
  }
}
 
OFC = {};
 
OFC.jquery = {
  name: "jQuery",
  version: function(src) { return $('#'+ src)[0].get_version() },
  rasterize: function (src, dst) { $('#'+ dst).replaceWith(OFC.jquery.image(src)) },
  image: function(src) { return "<img src='data:image/png;base64," + $('#'+src)[0].get_img_binary() + "' />"},
  popup: function(src) {
    var img_win = window.open('', 'Charts: Export as Image')
    with(img_win.document) {
      write('<html><head><title>Charts: Export as Image<\/title><\/head><body>' + OFC.jquery.image(src) + '<div>Right-Click on the above Image to Save<\/div><\/body><\/html>') }
    // stop the 'loading...' message
    img_win.document.close();
  }
}
 
// Using an object as namespaces is JS Best Practice. I like the Control.XXX style.
//if (!Control) {var Control = {}}
//if (typeof(Control == "undefined")) {var Control = {}}
if (typeof(Control == "undefined")) {var Control = {OFC: OFC.jquery}}
 
 
// By default, right-clicking on OFC and choosing "save image locally" calls this function.
// You are free to change the code in OFC and call my wrapper (Control.OFC.your_favorite_save_method)
// function save_image() { alert(1); Control.OFC.popup('my_chart') }
function save_image() {
    //alert(1);
    OFC.jquery.popup('my_chart')
}

function moo() {
    //alert(99);
};
    
var data = <?php echo $prli_click->setupClickLineGraph($start_timestamp,$end_timestamp,$link_id,$type,$group); ?>;

</script>
