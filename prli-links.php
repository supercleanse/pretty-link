<?php
require_once 'prli-config.php';
require_once(PRLI_MODELS_PATH . '/models.inc.php');

$params = $prli_link->get_params_array();

if($params['action'] == 'list')
{
  if(empty($params['group']))
    $prli_message = prli_get_main_message();
  else
    $prli_message = "Links in Group: " . $wpdb->get_var("SELECT name FROM " . $prli_group->table_name . " WHERE id=".$params['group']);
  if($params['regenerate'] == 'true')
  {
    $wp_rewrite->flush_rules();
    $prli_message = "Your Pretty Links were Successfully Regenerated";
  }

  prli_display_links_list($params, $prli_message);
}
else if($params['action'] == 'list-form')
{
  if(apply_filters('prli-link-list-process-form', true))
    prli_display_links_list($params, prli_get_main_message());
}
else if($params['action'] == 'quick-create')
{
  $errors = $prli_link->validate($_POST);

  if( count($errors) > 0 )
  {
    $groups = $prli_group->getAll('',' ORDER BY name');
    $values = setup_new_vars($groups);
    require_once 'classes/views/prli-links/new.php';
  }
  else
  {
    $_POST['param_forwarding'] = 'off';
    $_POST['param_struct'] = '';
    $_POST['name'] = '';
    $_POST['description'] = '';
    if( $prli_options->link_track_me )
      $_POST['track_me'] = 'on';
    if( $prli_options->link_nofollow )
      $_POST['nofollow'] = 'on';

    $_POST['redirect_type'] = $prli_options->link_redirect_type;

    $record = $prli_link->create( $_POST );

    $prli_message = "Your Pretty Link was Successfully Created";
    prli_display_links_list($params, $prli_message, '', 1);
  }
}
else if($params['action'] == 'create')
{
  $errors = $prli_link->validate($_POST);

  $errors = apply_filters( "prli_validate_link", $errors );

  if( count($errors) > 0 )
  {
    $groups = $prli_group->getAll('',' ORDER BY name');
    $values = setup_new_vars($groups);
    require_once 'classes/views/prli-links/new.php';
  }
  else
  {
    $record = $prli_link->create( $_POST );

    do_action( "prli_update_link", $record );

    $prli_message = "Your Pretty Link was Successfully Created";
    prli_display_links_list($params, $prli_message, '', 1);
  }
}
else if($params['action'] == 'edit')
{
  $groups = $prli_group->getAll('',' ORDER BY name');

  $record = $prli_link->getOne( $params['id'] );
  $values = setup_edit_vars($groups,$record);
  $id = $params['id'];
  require_once 'classes/views/prli-links/edit.php';
}
else if($params['action'] == 'bulk-update')
{
  if(apply_filters('prli-bulk-link-update', true))
  {
    $prli_message = "Your Pretty Links were Successfully Updated";
    prli_display_links_list($params, $prli_message, '', 1);
  }
}
else if($params['action'] == 'update')
{
  $errors = $prli_link->validate($_POST);
  $id = $_POST['id'];

  $errors = apply_filters( "prli_validate_link", $errors );

  if( count($errors) > 0 )
  {
    $groups = $prli_group->getAll('',' ORDER BY name');
    $record = $prli_link->getOne( $params['id'] );
    $values = setup_edit_vars($groups,$record);
    require_once 'classes/views/prli-links/edit.php';
  }
  else
  {
    $record = $prli_link->update( $_POST['id'], $_POST );

    do_action( "prli_update_link", $id );

    $prli_message = "Your Pretty Link was Successfully Updated";
    prli_display_links_list($params, $prli_message, '', 1);
  }
}
else if($params['action'] == 'reset')
{
  $prli_link->reset( $params['id'] );
  $prli_message = "Your Pretty Link was Successfully Reset";
  prli_display_links_list($params, $prli_message, '', 1);
}
else if($params['action'] == 'destroy')
{
  $prli_link->destroy( $params['id'] );
  $prli_message = "Your Pretty Link was Successfully Destroyed";
  prli_display_links_list($params, $prli_message, '', 1);
}

// Helpers
function prli_display_links_list($params, $prli_message, $page_params_ov = false, $current_page_ov = false)
{
  global $wpdb, $prli_utils, $prli_click, $prli_group, $prli_link, $page_size, $prli_options;

  $controller_file = basename(__FILE__);

  $where_clause = '';
  $page_params  = '';

  if(!empty($params['group']))
  {
    $where_clause = " group_id=" . $params['group'];
    $page_params = "&group=" . $params['group'];
  }

  $link_vars = prli_get_link_sort_vars($params, $where_clause);

  if($current_page_ov)
    $current_page = $current_page_ov;
  else
    $current_page = $params['paged'];

  if($page_params_ov)
    $page_params .= $page_params_ov;
  else
    $page_params .= $link_vars['page_params'];

  $sort_str = $link_vars['sort_str'];
  $sdir_str = $link_vars['sdir_str'];
  $search_str = $link_vars['search_str'];

  $record_count = $prli_link->getRecordCount($link_vars['where_clause']);
  $page_count = $prli_link->getPageCount($page_size,$link_vars['where_clause']);
  $links = $prli_link->getPage($current_page,$page_size,$link_vars['where_clause'],$link_vars['order_by']);
  $page_last_record = $prli_utils->getLastRecordNum($record_count,$current_page,$page_size);
  $page_first_record = $prli_utils->getFirstRecordNum($record_count,$current_page,$page_size);

  require_once 'classes/views/prli-links/list.php';
}

function prli_get_link_sort_vars($params,$where_clause = '')
{
  $order_by = '';
  $page_params = '';

  // These will have to work with both get and post
  $sort_str = $params['sort'];
  $sdir_str = $params['sdir'];
  $search_str = $params['search'];

  // Insert search string
  if(!empty($search_str))
  {
    $search_params = explode(" ", $search_str);

    foreach($search_params as $search_param)
    {
      if(!empty($where_clause))
        $where_clause .= " AND";

      $where_clause .= " (li.name like '%$search_param%' OR li.slug like '%$search_param%' OR li.url like '%$search_param%' OR li.created_at like '%$search_param%')";
    }

    $page_params .="&search=$search_str";
  }

  // make sure page params stay correct
  if(!empty($sort_str))
    $page_params .="&sort=$sort_str";

  if(!empty($sdir_str))
    $page_params .= "&sdir=$sdir_str";

  // Add order by clause
  switch($sort_str)
  {
    case "name":
    case "clicks":
    case "group_name":
    case "slug":
      $order_by .= " ORDER BY $sort_str";
      break;
    default:
      $order_by .= " ORDER BY created_at";
  }

  // Toggle ascending / descending
  if((empty($sort_str) and empty($sdir_str)) or $sdir_str == 'desc')
  {
    $order_by .= ' DESC';
    $sdir_str = 'desc';
  }
  else
    $sdir_str = 'asc';

  return array('order_by' => $order_by,
               'sort_str' => $sort_str, 
               'sdir_str' => $sdir_str, 
               'search_str' => $search_str, 
               'where_clause' => $where_clause, 
               'page_params' => $page_params);
}


?>
