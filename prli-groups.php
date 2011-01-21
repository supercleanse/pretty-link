<?php
require_once 'prli-config.php';
require_once(PRLI_MODELS_PATH . '/models.inc.php');

$params = $prli_group->get_params_array();

if($params['action'] == 'list')
{
  $prli_message = "Create a group and use it to organize your Pretty Links.";
  prli_display_groups_list($params, $prli_message);
}
else if($params['action'] == 'new')
{
  $links = $prli_link->getAll('',' ORDER BY li.name');
  require_once 'classes/views/prli-groups/new.php';
}
else if($params['action'] == 'create')
{
  $errors = $prli_group->validate($_POST);
  if( count($errors) > 0 )
  {
    $links = $prli_link->getAll('',' ORDER BY li.name');
    require_once 'classes/views/prli-groups/new.php';
  }
  else
  {
    $insert_id = $prli_group->create( $_POST );
    prli_update_groups($insert_id, $_POST['link']);
    $prli_message = "Your Pretty Link Group was Successfully Created";
    prli_display_groups_list($params, $prli_message, '', 1);
  }
}
else if($params['action'] == 'edit')
{
  $record = $prli_group->getOne( $params['id'] );
  $id = $params['id'];
  $links = $prli_link->getAll('',' ORDER BY li.name');
  require_once 'classes/views/prli-groups/edit.php';
}
else if($params['action'] == 'update')
{
  $errors = $prli_group->validate($_POST);
  $id = $_POST['id'];
  if( count($errors) > 0 )
  {
    $links = $prli_link->getAll('',' ORDER BY li.name');
    require_once 'classes/views/prli-groups/edit.php';
  }
  else
  {
    $record = $prli_group->update( $_POST['id'], $_POST );
    prli_update_groups($_POST['id'],$_POST['link']);
    $prli_message = "Your Pretty Link Group was Successfully Updated";
    prli_display_groups_list($params, $prli_message, '', 1);
  }
}
else if($params['action'] == 'destroy')
{
  $prli_group->destroy( $params['id'] );
  $prli_message = "Your Pretty Link Group was Successfully Deleted";
  prli_display_groups_list($params, $prli_message, '', 1);
}

function prli_update_groups($group_id, $values)
{
  global $prli_link;

  $links = $prli_link->getAll();

  foreach($links as $link)
  {
    // Only update a group if the user's pulling it from another group
    if($link->group_id != $group_id and empty($values[$link->id]))
      continue;

    $prli_link->update_group($link->id, $values[$link->id], $group_id);
  }
}

// Helpers
function prli_display_groups_list($params, $prli_message, $page_params_ov = false, $current_page_ov = false)
{
  global $wpdb, $prli_utils, $prli_group, $prli_click, $prli_link, $page_size;

  $controller_file = basename(__FILE__);

  $group_vars = prli_get_group_sort_vars($params);

  if($current_page_ov)
    $current_page = $current_page_ov;
  else
    $current_page = $params['paged'];

  if($page_params_ov)
    $page_params = $page_params_ov;
  else
    $page_params = $group_vars['page_params'];

  $sort_str = $group_vars['sort_str'];
  $sdir_str = $group_vars['sdir_str'];
  $search_str = $group_vars['search_str'];

  $record_count = $prli_group->getRecordCount($group_vars['where_clause']);
  $page_count = $prli_group->getPageCount($page_size,$group_vars['where_clause']);
  $groups = $prli_group->getPage($current_page,$page_size,$group_vars['where_clause'],$group_vars['order_by']);
  $page_last_record = $prli_utils->getLastRecordNum($record_count,$current_page,$page_size);
  $page_first_record = $prli_utils->getFirstRecordNum($record_count,$current_page,$page_size);

  require_once 'classes/views/prli-groups/list.php';
}

function prli_get_group_sort_vars($params,$where_clause = '')
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

      $where_clause .= " (name like '%$search_param%' OR description like '%$search_param%' OR created_at like '%$search_param%')";
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
    case "link_count":
    case "click_count":
    case "description":
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
