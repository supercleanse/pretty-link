<div class="wrap">
<?php
  require(PRLI_VIEWS_PATH.'/shared/nav.php');
?>

<h2><img src="<?php echo PRLI_IMAGES_URL.'/pretty-link-med.png'; ?>"/>&nbsp;Pretty Link: CSV Downloads</h2>
<span style="font-size: 14px; font-weight: bold;">For <?php echo stripslashes($link_name); ?>: </span>

<h3>Hit Reports:</h3>
<span class="description">All hits on <?php echo stripslashes($link_name); ?></span> 
<br/>
<ul>
<?php
for($i=$hit_page_count; $i>0; $i--)
{
  $hit_min = 0;

  if($i)
    $hit_min = ($i - 1) * $max_rows_per_file;

  if($i==$hit_page_count)
    $hit_max = $hit_record_count;
  else
    $hit_max = ($i * $max_rows_per_file) - 1;

  $hit_count = $hit_max - $hit_min + 1;
  $report_label = "Hits {$hit_min}-{$hit_max} ({$hit_count} Records)";
  $hit_param_delim = (preg_match('#\?#',$hit_report_url)?'&':'?');
?>
<li><a href="<?php echo $hit_report_url . $hit_param_delim; ?>prli_page=<?php echo $i; ?>"><?php echo $report_label; ?></a></li>
<?php
}
?>
</ul>
</div>
