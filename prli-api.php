<?php
/**
 * Pretty Link WordPress Plugin API
 */

/**
 * Returns the API Version as a string.
 */
function prli_api_version()
{
  return '1.3';
}

/**
 * Create a Pretty Link for a long, ugly URL.
 *
 * @param string $target_url Required, it is the value of the Target URL you
 *                           want the Pretty Link to redirect to
 * 
 * @param string $slug Optional, slug for the Pretty Link (string that comes 
 *                     after the Pretty Link's slash) if this value isn't set
 *                     then a random slug will be automatically generated.
 *
 * @param string $name Optional, name for the Pretty Link. If this value isn't
 *                     set then the name will be the slug.
 *
 * @param string $description Optional, description for the Pretty Link.
 *
 * @param integer $group_id Optional, the group that this link will be placed in.
 *                          If this value isn't set then the link will not be
 *                          placed in a group.
 *
 * @param boolean $link_track_me Optional, If true the link will be tracked,
 *                               if not set the default value (from the pretty
 *                               link option page) will be used
 *
 * @param boolean $link_nofollow Optional, If true the nofollow attribute will
 *                               be set for the link, if not set the default
 *                               value (from the pretty link option page) will
 *                               be used
 *
 * @param string $link_redirect_type Optional, valid values include '307', '301', 
 *                                   'prettybar', 'cloak' or 'pixel'
 *                                   if not set the default value (from the pretty
 *                                   link option page) will be used
 *
 * @return boolean / string The Full Pretty Link if Successful and false for Failure.
 *                          This function will also set a global variable named 
 *                          $prli_pretty_slug which gives the slug of the link 
 *                          created if the link is successfully created -- it will
 *                          set a variable named $prli_error_messages if the link 
 *                          was not successfully created.
 */
function prli_create_pretty_link( $target_url,
                                  $slug = '',
                                  $name = '',
                                  $description = '',
                                  $group_id = 0,
                                  $track_me = '',
                                  $nofollow = '',
                                  $redirect_type = '',
                                  $param_forwarding = '',
                                  $param_struct = '' )
{
  global $wpdb, $prli_link, $prli_blogurl;
  global $prli_error_messages, $prli_pretty_link, $prli_pretty_slug, $prli_options;

  $prli_error_messages = array();

  $values = array();
  $values['url']              = $target_url;
  $values['slug']             = (($slug == '')?$prli_link->generateValidSlug():$slug);
  $values['name']             = $name;
  $values['description']      = $description;
  $values['group_id']         = $group_id;
  $values['redirect_type']    = (($redirect_type == '')?$prli_options->link_redirect_type:$redirect_type);
  $values['nofollow']         = (($nofollow == '')?$prli_options->link_nofollow:$nofollow);
  $values['track_me']         = (($track_me == '')?$prli_options->link_track_me:$track_me);
  $values['param_forwarding'] = (($param_forwarding == '')?'off':$param_forwarding);
  $values['param_struct']     = $param_struct;

  // make array look like $_POST
  if(empty($values['nofollow']) or !$values['nofollow'])
    unset($values['nofollow']);
  if(empty($values['track_me']) or !$values['track_me'])
    unset($values['track_me']);

  $prli_error_messages = $prli_link->validate( $values );
    
  if( count($prli_error_messages) == 0 )
  {
    if( $id = $prli_link->create( $values ) )
      return $id;
    else
    {
      $prli_error_messages[] = "An error prevented your Pretty Link from being created";
      return false;
    }
  }
  else
    return false;
}

function prli_update_pretty_link( $id,
                                  $target_url = '',
                                  $slug = '',
                                  $name = -1,
                                  $description = -1,
                                  $group_id = '',
                                  $track_me = '',
                                  $nofollow = '',
                                  $redirect_type = '',
                                  $param_forwarding = '',
                                  $param_struct = -1 )
{
  global $wpdb, $prli_link, $prli_blogurl;
  global $prli_error_messages, $prli_pretty_link, $prli_pretty_slug;

  if(empty($id))
  {
    $prli_error_messages[] = "Pretty Link ID must be set for successful update.";
    return false;
  }

  $record = $prli_link->getOne($id);

  $prli_error_messages = array();

  $values = array();
  $values['id']               = $id;
  $values['url']              = (($target_url == '')?$record->url:$target_url);
  $values['slug']             = (($slug == '')?$record->slug:$slug);
  $values['name']             = (($name == -1)?$record->name:$name);
  $values['description']      = (($description == -1)?$record->description:$description);
  $values['group_id']         = (($group_id == '')?$record->group_id:$group_id);
  $values['redirect_type']    = (($redirect_type == '')?$record->redirect_type:$redirect_type);
  $values['nofollow']         = (($nofollow == '')?$record->nofollow:$nofollow);
  $values['track_me']         = (($track_me == '')?(int)$record->track_me:$track_me);
  $values['param_forwarding'] = (($param_forwarding == '')?$record->param_forwarding:$param_forwarding);
  $values['param_struct']     = (($param_struct == -1)?$record->param_struct:$param_struct);

  // make array look like $_POST
  if(empty($values['nofollow']) or !$values['nofollow'])
    unset($values['nofollow']);
  if(empty($values['track_me']) or !$values['track_me'])
    unset($values['track_me']);

  $prli_error_messages = $prli_link->validate( $values );
    
  if( count($prli_error_messages) == 0 )
  {
    if( $prli_link->update( $id, $values ) )
      return true;
    else
    {
      $prli_error_messages[] = "An error prevented your Pretty Link from being created";
      return false;
    }
  }
  else
    return false;
}

/**
 * Get all the pretty link groups in an array suitable for creating a select box.
 *
 * @return bool (false if failure) | array A numerical array of associative arrays 
 *                                         containing all the data about the pretty
 *                                         link groups.
 */
function prli_get_all_groups()
{
  global $prli_group;
  $groups = $prli_group->getAll('',' ORDER BY gr.name', ARRAY_A);
  return $groups;
}

/**
 * Get all the pretty links in an array suitable for creating a select box.
 *
 * @return bool (false if failure) | array A numerical array of associative arrays
 *                                         containing all the data about the pretty
 *                                         links.
 */
function prli_get_all_links()
{
  global $prli_link;
  $links = $prli_link->getAll('',' ORDER BY li.name', ARRAY_A);
  return $links;
}
                             
/**
 * Gets a specific link from a slug and returns info about it in an array
 *
 * @return bool (false if failure) | array An associative array with all the
 *                                         data about the given pretty link.
 */
function prli_get_link_from_slug($slug, $return_type = OBJECT, $include_stats = false)
{
  global $prli_link;
  $link = $prli_link->getOneFromSlug($slug, $return_type, $include_stats);
  return $link;
}

/**
 * Gets a specific link from id and returns info about it in an array
 *
 * @return bool (false if failure) | array An associative array with all the
 *                                         data about the given pretty link.
 */
function prli_get_link($id, $return_type = OBJECT, $include_stats = false)
{
  global $prli_link;
  $link = $prli_link->getOne($id, $return_type, $include_stats);
  return $link;
}

/**
 * Gets the full pretty link url from an id
 *
 * @return bool (false if failure) | string the pretty link url
 */
function prli_get_pretty_link_url($id)
{
  global $prli_link,$prli_blogurl;

  if($pretty_link = $prli_link->getOne($id))
    return "{$prli_blogurl}".PrliUtils::get_permalink_pre_slug_uri()."{$pretty_link->slug}";

  return false;
}
                             
?>
