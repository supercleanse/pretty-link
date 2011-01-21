<?php
require_once('models.inc.php');

class PrliUtils
{
  /** Okay I realize that Percentagize isn't really a word but
    * this is so that the values we have will work with google
    * charts.
    */
  function percentagizeArray($data,$max_value)
  {
    $new_data = array();
    foreach($data as $point)
    {
      if( $max_value > 0 )
      {
        $new_data[] = $point / $max_value * 100;
      }
      else
      {
        $new_data[] = 0;
      }
    }
    return $new_data;
  }
  
  function getTopValue($values_array)
  {
    rsort($values_array);
    return $values_array[0];
  }
  
  function getFirstClickDate()
  {
    global $wpdb;
  
    $clicks_table = $wpdb->prefix . "prli_clicks";
    $query = "SELECT created_at FROM $clicks_table ORDER BY created_at LIMIT 1";
    $first_click = $wpdb->get_var($query);
  
    if(isset($first_click))
    {
      return strtotime($first_click);
    }
    else
      return null; 
  }
  
  function getMonthsArray()
  {
    global $wpdb;
    global $prli_click;
  
    $months = array(); 
    $year = date("Y");
    $month = date("m");
    $current_timestamp = time();
    $current_month_timestamp = mktime(0, 0, 0, date("m", $current_timestamp), 1, date("Y", $current_timestamp));
  
    $clicks_table = $prli_click->tableName();
    $first_click = $wpdb->get_var("SELECT created_at FROM $clicks_table ORDER BY created_at LIMIT 1;");
    $first_timestamp = ((empty($first_click))?$current_timestamp:strtotime($first_click));
    $first_date = mktime(0, 0, 0, date("m", $first_timestamp), 1, date("Y", $first_timestamp));
  
    while($current_month_timestamp >= $first_date)
    {
      $months[] = $current_month_timestamp;
      if(date("m") == 1)
        $current_month_timestamp = mktime(0, 0, 0, 12, 1, date("Y", $current_month_timestamp)-1);
      else
        $current_month_timestamp = mktime(0, 0, 0, date("m", $current_month_timestamp)-1, 1, date("Y", $current_month_timestamp));
    }
    return $months;
  }
  
  // For Pagination
  function getLastRecordNum($r_count,$current_p,$p_size)
  {
    return (($r_count < ($current_p * $p_size))?$r_count:($current_p * $p_size));
  }
  
  // For Pagination
  function getFirstRecordNum($r_count,$current_p,$p_size)
  {
    if($current_p == 1)
      return 1;
    else
      return ($this->getLastRecordNum($r_count,($current_p - 1),$p_size) + 1);
  }
  
  function slugIsAvailable( $full_slug, $id = '' )
  {
    global $wpdb, $prli_blogurl, $prli_link;
  
    // We don't care about anything after the slash for now because we don't want
    // to have to worry about comparing against every imaginable combination in WordPress
    $slug_components = explode('/',$full_slug);
    $slug = $slug_components[0];
  
    // Check slug uniqueness against posts, pages and categories
    $posts_table = $wpdb->prefix . "posts";
    $terms_table = $wpdb->prefix . "terms";
  
    $post_slug = $wpdb->get_var("SELECT post_name FROM $posts_table WHERE post_name='$slug'");
    $term_slug = $wpdb->get_col("SELECT slug FROM $terms_table WHERE slug='$slug'");
  
    if( $post_slug == $slug or $term_slug == $slug )
      return false;
  
    // Check slug against files on the root wordpress install
    $root_dir = opendir(ABSPATH); 
  
    while (($file = readdir($root_dir)) !== false) {
      $haystack = strtolower($file);
      if($haystack == $slug)
        return false;
    }
  
    // Check slug against other slugs in the prli links database.
    // We'll use the full_slug here because its easier to guarantee uniqueness.
    if($id != null and $id != '')
      $query = "SELECT slug FROM " . $prli_link->table_name . " WHERE slug='" . $full_slug . "' AND id <> " . $id;
    else
      $query = "SELECT slug FROM " . $prli_link->table_name . " WHERE slug='" . $full_slug . "'";
  
    $link_slug = $wpdb->get_var($query);
  
    if( $link_slug == $full_slug )
      return false;
  
    $pre_slug_slug = PrliUtils::get_permalink_pre_slug_uri(true,true);

    if($full_slug == $pre_slug_slug)
      return false;

    // TODO: Check permalink structure to avoid the ability of creating a year or something as a slug
  
    return true;
  }
  
  function &php_get_browsercap_ini()
  {
    // Since it's a fairly expensive proposition to load the ini file
    // let's make sure we only do it once
    static $browsecap_ini;
    
    if(!isset($browsecap_ini))
    {
      if( version_compare(PHP_VERSION, '5.3.0') >= 0 )
        $browsecap_ini =& parse_ini_file( PRLI_PATH . "/includes/php/php_browsecap.ini", true, INI_SCANNER_RAW );
      else
        $browsecap_ini =& parse_ini_file( PRLI_PATH . "/includes/php/php_browsecap.ini", true );
    }
    
    return $browsecap_ini;
  }
  
  /* Needed because we don't know if the target uesr will have a browsercap file installed
     on their server ... particularly in a shared hosting environment this is difficult
  */
  function php_get_browser($agent = NULL)
  {
    $agent=$agent?$agent:$_SERVER['HTTP_USER_AGENT'];
    $yu=array();
    $q_s=array("#\.#","#\*#","#\?#");
    $q_r=array("\.",".*",".?");
    $brows =& $this->php_get_browsercap_ini();

    if(!empty($brows) and $brows and is_array($brows))
    {
      foreach($brows as $k=>$t)
      {
        if(fnmatch($k,$agent))
        {
          $yu['browser_name_pattern']=$k;
          $pat=preg_replace($q_s,$q_r,$k);
          $yu['browser_name_regex']=strtolower("^$pat$");
          foreach($brows as $g=>$r)
          {
            if($t['Parent']==$g)
            {
              foreach($brows as $a=>$b)
              {
                if($r['Parent']==$a)
                {
                  $yu=array_merge($yu,$b,$r,$t);
                  foreach($yu as $d=>$z)
                  {
                    $l=strtolower($d);
                    $hu[$l]=$z;
                  }
                }
              }
            }
          }
      
          break;
        }
      }
    }
  
    return $hu;
  }
  
  // This is where the magic happens!
  function track_link($slug,$values)
  {
    global $wpdb, $prli_click, $prli_options, $prli_link, $prli_update;
  
    $query = "SELECT * FROM ".$prli_link->table_name." WHERE slug='$slug' LIMIT 1";
    $pretty_link = $wpdb->get_row($query);
    $pretty_link_target = apply_filters('prli_target_url',array('url' => $pretty_link->url, 'link_id' => $pretty_link->id));
    $pretty_link_url = $pretty_link_target['url'];
    
    if(isset($pretty_link->track_me) and $pretty_link->track_me)
    {
      $first_click = 0;
      
      $click_ip =         isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'';
      $click_referer =    isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
      $click_uri =        isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'';
      $click_user_agent = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';

      //Set Cookie if it doesn't exist
      $cookie_name = 'prli_click_' . $pretty_link->id;

      //Used for unique click tracking
      $cookie_expire_time = time()+60*60*24*30; // Expire in 30 days
      
      if(!isset($_COOKIE[$cookie_name]))
      {
        setcookie($cookie_name,$slug,$cookie_expire_time,'/');
        $first_click = 1;
      }
     
      if(isset($prli_options->extended_tracking) and $prli_options->extended_tracking == 'extended')
      {
        $click_browser = $this->php_get_browser();
        $click_host = gethostbyaddr($click_ip);

        $visitor_cookie = 'prli_visitor';
        //Used for visitor activity
        $visitor_cookie_expire_time = time()+60*60*24*365; // Expire in 1 year
        
        // Retrieve / Generate visitor id
        if(!isset($_COOKIE[$visitor_cookie]))
        {
          $visitor_uid = $prli_click->generateUniqueVisitorId();
          setcookie($visitor_cookie,$visitor_uid,$visitor_cookie_expire_time,'/');
        }
        else
          $visitor_uid = $_COOKIE[$visitor_cookie];
      }
      else
      {
        $click_browser = array( 'browser' => '', 'version' => '', 'platform' => '', 'crawler' => '' );
        $click_host = '';
        $visitor_uid = '';
      }
      
      if($prli_options->extended_tracking != 'count')
      {
        //Record Click in DB
        $insert_str = "INSERT INTO {$prli_click->table_name} (link_id,vuid,ip,browser,btype,bversion,os,referer,uri,host,first_click,robot,created_at) VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%d,%d,NOW())";
        $insert = $wpdb->prepare($insert_str, $pretty_link->id,
                                              $visitor_uid,
                                              $click_ip,
                                              $click_user_agent,
                                              $click_browser['browser'],
                                              $click_browser['version'],
                                              $click_browser['platform'],
                                              $click_referer,
                                              $click_uri,
                                              $click_host,
                                              $first_click,
                                              $this->this_is_a_robot($click_user_agent,$click_browser));
        
        $results = $wpdb->query( $insert );
        
        do_action('prli_record_click',array('link_id' => $pretty_link->id, 'click_id' => $wpdb->insert_id, 'url' => $pretty_link_url));
      }
      else
      {
        global $prli_link_meta;
        $exclude_ips = explode(",", $prli_options->prli_exclude_ips);
        if(!in_array($click_ip, $exclude_ips) and !$this->this_is_a_robot($click_user_agent,$click_browser))
        {
          $clicks  = $prli_link_meta->get_link_meta($pretty_link->id, 'static-clicks', true);
          $clicks = (empty($clicks) or $clicks === false)?0:$clicks;
          $prli_link_meta->update_link_meta($pretty_link->id, 'static-clicks', $clicks+1);

          if($first_click)
          {
            $uniques  = $prli_link_meta->get_link_meta($pretty_link->id, 'static-uniques', true);
            $uniques = (empty($uniques) or $uniques === false)?0:$uniques;
            $prli_link_meta->update_link_meta($pretty_link->id, 'static-uniques', $uniques+1);
          }
        }
      }
    }
  
    // Reformat Parameters
    $param_string = '';
      
    if(isset($pretty_link->param_forwarding) and ($pretty_link->param_forwarding == 'custom' OR $pretty_link->param_forwarding == 'on') and isset($values) and count($values) >= 1)
    {
      $first_param = true;
      foreach($values as $key => $value)
      {
        if($first_param)
        {
          $param_string = (preg_match("#\?#", $pretty_link_url)?"&":"?");
          $first_param = false;
        }
        else
          $param_string .= "&";
    
        $param_string .= "$key=$value";
      }
    }
    
    if(isset($pretty_link->nofollow) and $pretty_link->nofollow)
      header("X-Robots-Tag: noindex, nofollow", true);

    switch($pretty_link->redirect_type)
    {
      case '301':
        header("HTTP/1.1 301 Moved Permanently");
        header('Location: '.$pretty_link_url.$param_string);
        break;
      default:
        if( $pretty_link->redirect_type == '307' or
            !$prli_update->pro_is_installed_and_authorized() )
        {
          if($_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.0')
            header("HTTP/1.1 302 Found");
          else
            header("HTTP/1.1 307 Temporary Redirect");
          header('Location: '.$pretty_link_url.$param_string);
        }
        else
          do_action('prli_issue_cloaked_redirect', $pretty_link->redirect_type, $pretty_link, $pretty_link_url, $param_string);
    }
  }
  
  function get_custom_forwarding_rule($param_struct)
  {
    $param_struct = preg_replace('#%.*?%#','(.*?)',$param_struct);
    return preg_replace('#\(\.\*\?\)$#','(.*)',$param_struct); // replace the last one with a greedy operator
  }
  
  function get_custom_forwarding_params($param_struct, $start_index = 1)
  {
    preg_match_all('#%(.*?)%#', $param_struct, $matches);
  
    $param_string = '';
    $match_index = $start_index;
    for($i = 0; $i < count($matches[1]); $i++)
    {
      if($i == 0 and $start_index == 1)
        $param_string .= "?";
      else
        $param_string .= "&";
  
      $param_string .= $matches[1][$i] . "=$$match_index";
      $match_index++;
    }
  
    return $param_string;
  }
  
  function decode_custom_param_str($param_struct, $uri_string)
  {
    // Get the structure matches (param names)
    preg_match_all('#%(.*?)%#', $param_struct, $struct_matches);
  
    // Get the uri matches (param values)
    $match_str = '#'.$this->get_custom_forwarding_rule($param_struct).'#';
    preg_match($match_str, $uri_string, $uri_matches);
  
    $param_array = array();
    for($i = 0; $i < count($struct_matches[1]); $i++)
      $param_array[$struct_matches[1][$i]] = $uri_matches[$i+1];
  
    return $param_array;
  }

  // Detects whether an array is a true numerical array or an
  // associative array (or hash).
  function prli_array_type($item)
  {
    $array_type = 'unknown';

    if(is_array($item))
    {
      $array_type = 'array';

      foreach($item as $key => $value)
      {
        if(!is_numeric($key))
        {
          $array_type = 'hash';
          break;
        }
      }
    }

    return $array_type;
  }

  // This eliminates the need to use php's built in json_encoder
  // which only works with PHP 5.2 and above.
  function prli_json_encode($json_array)
  {
    $json_str = '';

    if(is_array($json_array))
    {
      if($this->prli_array_type($json_array) == 'array')
      {
        $first = true;
        $json_str .= "[";
        foreach($json_array as $item)
        {
          if(!$first)
            $json_str .= ",";

          if(is_numeric($item))
            $json_str .= (($item < 0)?"\"$item\"":$item);
          else if(is_array($item))
            $json_str .= $this->prli_json_encode($item);
          else if(is_string($item))
            $json_str .= '"'.$item.'"';
          else if(is_bool($item))
            $json_str .= (($item)?"true":"false");

          $first = false;
        }
        $json_str .= "]";
      }
      else if($this->prli_array_type($json_array) == 'hash')
      {
        $first = true;
        $json_str .= "{";
        foreach($json_array as $key => $item)
        {
          if(!$first)
            $json_str .= ",";

          $json_str .= "\"$key\":";

          if(is_numeric($item))
            $json_str .= (($item < 0)?"\"$item\"":$item);
          else if(is_array($item))
            $json_str .= $this->prli_json_encode($item);
          else if(is_string($item))
            $json_str .= "\"$item\"";
          else if(is_bool($item))
            $json_str .= (($item)?"true":"false");

          $first = false;
        }
        $json_str .= "}";
      }
    }

    return $json_str;
  }

  // This eliminates the need to use php's built in json_encoder
  // which only works with PHP 5.2 and above.
  function prli_json_decode(&$json_str,$type='array',$index = 0)
  {
    $json_array = array();
    $index_str = '';
    $value_str = '';
    $in_string = false;
    $in_index = ($type=='hash'); //first char in hash is an index
    $in_value = ($type=='array'); //first char in array is a value

    $json_special_chars_array = array('{','[','}',']','"',',',':');

    // On the first pass we need to do some special stuff
    if($index == 0)
    {
      if($json_str[$index] == '{')
      {
        $type = 'hash';
        $in_index = true;
        $in_value = false;
      }
      else if($json_str[$index]=='[')
      {
        $type = 'array';
        $in_index = false;
        $in_value = true;
      }
      else
        return false; // not valid json

      // skip to next index
      $index++;
    }

    for($i = $index; $i < strlen($json_str); $i++)
    {
      if($in_string and in_array($json_str[$i],$json_special_chars_array))
      {
        if($json_str[$i] == '"')
          $in_string = false;
        else
        {
          if($in_value)
            $value_str .= $json_str[$i];
          else if($in_index)
            $index_str .= $json_str[$i];
        }
      }
      else
      {
        switch($json_str[$i])
        {
          case '{':
            $array_vals = $this->prli_json_decode($json_str,'hash',$i + 1);

            if($type=='hash')
              $json_array[$index_str] = $array_vals[1]; // We'll never get an array as an index
            else if($type=='array')
              $json_array[] = $array_vals[1];

            $i = $array_vals[0]; // Skip ahead to the new index
            break;

          case '[':
            $array_vals = $this->prli_json_decode($json_str,'array',$i + 1);

            if($type=='hash')
              $json_array[$index_str] = $array_vals[1];
            else if($type=='array')
              $json_array[] = $array_vals[1];

            $i = $array_vals[0]; // Skip ahead to the new index
            break;

          case '}':
            if(!empty($index_str) and !empty($value_str))
            {
              $json_array[$index_str] = $this->prli_decode_json_unicode($value_str);
              $index_str = '';
              $value_str = '';
            }
            return array($i,$json_array);

          case ']':
            if(!empty($value_str))
            {
              $json_array[] = $this->prli_decode_json_unicode($value_str);
              $value_str = '';
            }
            return array($i,$json_array);

          // skip the null character
          case '\0':
              break;

          // Handle Escapes
          case '\\':
            if($in_string)
            {
              if(in_array($json_str[$i + 1],$json_special_chars_array))
              {
                if($in_value)
                  $value_str .= '\\'.$json_str[$i + 1];
                else if($in_index)
                  $index_str .= '\\'.$json_str[$i + 1];

                $i++; // skip the escaped char now that its been recorded
              }
              else
              {
                if($in_value)
                  $value_str .= $json_str[$i];
                else if($in_index)
                  $index_str .= $json_str[$i];
              }
            }
            break;

          case '"':
            $in_string = !$in_string; // just tells us if we're in a string
            break;

          case ':':
            if($type == 'hash')
            {
              $in_value = true;
              $in_index = false;
            }
            break;

          case ',':
            if($type == 'hash')
            {
              if(!empty($index_str) and !empty($value_str))
              {
                $json_array[$index_str] = $this->prli_decode_json_unicode($value_str);
                $index_str = '';
                $value_str = '';
              }

              $in_index = true;
              $in_value = false;
            }
            else if($type == 'array')
            {
              if(!empty($value_str))
              {
                $json_array[] = $this->prli_decode_json_unicode($value_str);
                $value_str = '';
              }

              $in_value = true;
              $in_index = false; // always false in an array
            }
            break;

          // record index and value
          default:
            if($in_value)
              $value_str .= $json_str[$i];
            else if($in_index)
              $index_str .= $json_str[$i];
        }
      }
    }

    return array(-1,$json_array);
  }

  function prli_decode_json_unicode($val)
  { 
    $val = preg_replace_callback("/\\\u([0-9a-fA-F]{4})/",
                                 create_function(
                                   '$matches',
                                   'return html_entity_decode("&#".hexdec($matches[1]).";",ENT_COMPAT,"UTF-8");'
                                 ),
                                 $val);
    return $val;
  }

  // Get the timestamp of the start date
  function get_start_date($values,$min_date = '')
  {
    // set default to 30 days ago
    if(empty($min_date))
      $min_date = 30;

    if(!empty($values['sdate']))
    {
      $sdate = explode("-",$values['sdate']);
      $start_timestamp = mktime(0,0,0,$sdate[1],$sdate[2],$sdate[0]);
    }
    else
      $start_timestamp = time()-60*60*24*(int)$min_date;
  
    return $start_timestamp;
  }
  
  // Get the timestamp of the end date
  function get_end_date($values)
  {
    if(!empty($values['edate']))
    {
      $edate = explode("-",$values['edate']);
      $end_timestamp = mktime(0,0,0,$edate[1],$edate[2],$edate[0]);
    }
    else
      $end_timestamp = time();
  
    return $end_timestamp;
  }

  function prepend_and_or_where( $starts_with = ' WHERE', $where = '' )
  {
    return (( $where == '' )?'':$starts_with . $where);
  }

  function uninstall_pro()
  {
    $prlipro_path = PRLI_PATH . '/pro';

    // unlink pro directory
    $this->delete_dir($prlipro_path);
    
    delete_option( 'prlipro_activated' );
    delete_option( 'prlipro_username' );
    delete_option( 'prlipro_password' );
    delete_option( 'prlipro-credentials' );
    
    // Yah- I just leave the pro database tables & data hanging
    // around in case you want to re-install it at some point
  }

  function install_pro_db()
  {
    global $wpdb;

    $pro_db_version = 1; // this is the version of the database we're moving to
    $old_pro_db_version = get_option('prlipro_db_version');

    if($pro_db_version != $old_pro_db_version)
    {
      $upgrade_path = ABSPATH . 'wp-admin/includes/upgrade.php';
      require_once($upgrade_path);

      // Pretty Link Pro Tables
      $tweets_table           = "{$wpdb->prefix}prli_tweets";
      $keywords_table         = "{$wpdb->prefix}prli_keywords";
      $reports_table          = "{$wpdb->prefix}prli_reports";
      $report_links_table     = "{$wpdb->prefix}prli_report_links";
      $link_rotations_table   = "{$wpdb->prefix}prli_link_rotations";
      $clicks_rotations_table = "{$wpdb->prefix}prli_clicks_rotations";

      $charset_collate = '';
      if( $wpdb->has_cap( 'collation' ) )
      {
        if( !empty($wpdb->charset) )
          $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if( !empty($wpdb->collate) )
          $charset_collate .= " COLLATE $wpdb->collate";
      }

      /* Create/Upgrade Tweets Table */
      $sql = "CREATE TABLE {$tweets_table} (
                id int(11) NOT NULL auto_increment,
                twid varchar(255) NOT NULL, 
                tw_text varchar(255) default NULL,
                tw_to_user_id varchar(255) default NULL,
                tw_from_user varchar(255) default NULL,
                tw_from_user_id varchar(255) NOT NULL,
                tw_iso_language_code varchar(255) default NULL,
                tw_source varchar(255) default NULL,
                tw_profile_image_url varchar(255) default NULL,
                tw_created_at varchar(255) NOT NULL,
                created_at datetime NOT NULL,
                link_id int(11) default NULL,
                PRIMARY KEY  (id),
                KEY link_id (link_id),
                KEY twid (twid)
              ) {$charset_collate};";
      
      dbDelta($sql);

      /* Create/Upgrade Keywords Table */
      $sql = "CREATE TABLE {$keywords_table} (
                id int(11) NOT NULL auto_increment,
                text varchar(255) NOT NULL,
                link_id int(11) NOT NULL,
                created_at datetime NOT NULL,
                PRIMARY KEY  (id),
                KEY link_id (link_id)
              ) {$charset_collate};";
      
      dbDelta($sql);

      /* Create/Upgrade Reports Table */
      $sql = "CREATE TABLE {$reports_table} (
                id int(11) NOT NULL auto_increment,
                name varchar(255) NOT NULL,
                goal_link_id int(11) default NULL,
                created_at datetime NOT NULL,
                PRIMARY KEY  (id),
                KEY goal_link_id (goal_link_id)
              ) {$charset_collate};";
      
      dbDelta($sql);

      /* Create/Upgrade Reports Table */
      $sql = "CREATE TABLE {$report_links_table} (
                id int(11) NOT NULL auto_increment,
                report_id int(11) NOT NULL,
                link_id int(11) NOT NULL,
                created_at datetime NOT NULL,
                PRIMARY KEY  (id),
                KEY report_id (report_id),
                KEY link_id (link_id)
              ) {$charset_collate};";
      
      dbDelta($sql);

      /* Create/Upgrade Link Rotations Table */
      $sql = "CREATE TABLE {$link_rotations_table} (
                id int(11) NOT NULL auto_increment,
                url varchar(255) default NULL,
                weight int(11) default 0,
                r_index int(11) default 0,
                link_id int(11) NOT NULL,
                created_at datetime NOT NULL,
                PRIMARY KEY  (id),
                KEY link_id (link_id)
              ) {$charset_collate};";
      
      dbDelta($sql);

      /* Create/Upgrade Clicks / Rotations Table */
      $sql = "CREATE TABLE {$clicks_rotations_table} (
                id int(11) NOT NULL auto_increment,
                click_id int(11) NOT NULL,
                link_id int(11) NOT NULL,
                url text NOT NULL,
                PRIMARY KEY  (id),
                KEY click_id (click_id),
                KEY link_id (link_id)
              ) {$charset_collate};";
      
      dbDelta($sql);
    }

    /***** SAVE DB VERSION *****/
    delete_option('prlipro_db_version');
    add_option('prlipro_db_version',$pro_db_version);
  }

  // be careful with this one -- I use it to forceably reinstall pretty link pro
  function delete_dir($dir) 
  {
    if (!file_exists($dir))
      return true;

    if (!is_dir($dir))
      return unlink($dir);
  
    foreach (scandir($dir) as $item) 
    {
      if ($item == '.' || $item == '..')
        continue;
  
      if (!$this->delete_dir($dir.DIRECTORY_SEPARATOR.$item))
        return false;
    }
  
    return rmdir($dir);
  }

  // Used in the install procedure to migrate database columns
  function migrate_before_db_upgrade()
  {
    global $prli_options, $prli_update, $prli_link, $prli_click, $wpdb;
    $db_version = (int)get_option('prli_db_version');

    if(!$db_version)
      return;

    // Migration for version 1 of the database
    if($db_version and $db_version < 1)
    {
      $query = "SELECT * from {$prli_link->table_name}";
      $links = $wpdb->get_results($query);
      $query_str = "UPDATE {$prli_link->table_name} SET redirect_type=%s WHERE id=%d";

      foreach($links as $link)
      {
        if(isset($link->track_as_img) and $link->track_as_img)
        {
          $query = $wpdb->prepare($query_str, 'pixel', $link->id);
          $wpdb->query($query);
        }
        else if(isset($link->use_prettybar) and $link->use_prettybar)
        {
          $query = $wpdb->prepare($query_str, 'prettybar', $link->id);
          $wpdb->query($query);
        }
        else if(isset($link->use_ultra_cloak) and $link->use_ultra_cloak)
        {
          $query = $wpdb->prepare($query_str, 'cloak', $link->id);
          $wpdb->query($query);
        }
      }

      $query = "ALTER TABLE {$prli_link->table_name} DROP COLUMN track_as_img, DROP COLUMN use_prettybar, DROP COLUMN use_ultra_cloak, DROP COLUMN gorder";
      $wpdb->query($query);
    }

    if($db_version and $db_version < 2)
    {
      unset($prli_options->prli_exclude_ips);
      unset($prli_options->prettybar_image_url);
      unset($prli_options->prettybar_background_image_url);
      unset($prli_options->prettybar_color);
      unset($prli_options->prettybar_text_color);
      unset($prli_options->prettybar_link_color);
      unset($prli_options->prettybar_hover_color);
      unset($prli_options->prettybar_visited_color);
      unset($prli_options->prettybar_title_limit);
      unset($prli_options->prettybar_desc_limit);
      unset($prli_options->prettybar_link_limit);

      // Save the posted value in the database
      update_option( 'prli_options', $prli_options );
    }

    // Modify the tables so they're UTF-8
    if($db_version and $db_version < 3)
    { 
      $charset_collate = '';
      if( $wpdb->has_cap( 'collation' ) )
      {
        if( !empty($wpdb->charset) )
          $charset_collate = "CONVERT TO CHARACTER SET $wpdb->charset";
        if( !empty($wpdb->collate) )
          $charset_collate .= " COLLATE $wpdb->collate";
      }

      if(!empty($charset_collate))
      {
        $prli_table_names = array( "{$wpdb->prefix}prli_groups",
                                   "{$wpdb->prefix}prli_clicks",
                                   "{$wpdb->prefix}prli_links",
                                   "{$wpdb->prefix}prli_link_metas",
                                   "{$wpdb->prefix}prli_tweets",
                                   "{$wpdb->prefix}prli_keywords",
                                   "{$wpdb->prefix}prli_reports",
                                   "{$wpdb->prefix}prli_report_links",
                                   "{$wpdb->prefix}prli_link_rotations",
                                   "{$wpdb->prefix}prli_clicks_rotations" );

        foreach($prli_table_names as $prli_table_name)
        {
          $query = "ALTER TABLE {$prli_table_name} {$charset_collate}";
          $wpdb->query($query);
        }
      }
    }
    
    // Upgrade the twitter hide badges on pages / posts for pro users
    if($db_version and $db_version < 7)
    {
      if($prli_update->pro_is_installed())
      {
        global $prlipro_options;

        if(trim($prlipro_options->twitter_badge_hidden) != '')
        {
          $hidden_post_ids = explode(',',trim($prlipro_options->twitter_badge_hidden));
          foreach($hidden_post_ids as $post_id)
          {
            $prlipro_post_options = PrliProPostOptions::get_stored_object($post_id);
            $prlipro_post_options->hide_twitter_button = 1;
            $prlipro_post_options->store($post_id);
          }
        }
      }
    }
    
    if($db_version and $db_version < 8)
    {
      // Install / Upgrade Pretty Link Pro
      $prlipro_username = get_option( 'prlipro_username' );
      $prlipro_password = get_option( 'prlipro_password' );

      if( !empty($prlipro_username) and !empty($prlipro_password) )
      {
        $creds = array('username' => $prlipro_username,
                       'password' => $prlipro_password);
        update_option('prlipro-credentials', $creds);
      }
    } 

    // Hiding pretty link custom fields
    if($db_version and $db_version < 10)
    {
      $query_str = "UPDATE {$wpdb->postmeta} SET meta_key=%s WHERE meta_key=%s";

      $query = $wpdb->prepare($query_str, '_pretty-link', 'pretty-link');
      $wpdb->query($query);

      $query = $wpdb->prepare($query_str, '_prli-keyword-cached-content', 'prli-keyword-cached-content');
      $wpdb->query($query);

      $query = $wpdb->prepare($query_str, '_prlipro-post-options', 'prlipro-post-options');
      $wpdb->query($query);
    }

    if($db_version and $db_version < 11)
    {
      /* Too much to handle on larger tweet databases -- this code will still be accessible by going to the following url:
         {$prli_blogurl}/wp-admin/admin.php?page=pretty-link/pro/prlipro-options.php&action=trim_dup_tweets
      // Clearing out duplicate tweets
      if($prli_update->pro_is_installed())
      {
        $block_size = 2000;
        $upper_limit = $block_size - 1;
        $tweet_table = "{$wpdb->prefix}prli_tweets";

        $query = $wpdb->prepare("SELECT count(DISTINCT twid) FROM {$tweet_table}");
        $twid_count = $wpdb->get_var($query);

        for($offset=0; $offset < $twid_count; $offset += $block_size)
        {
          $limit = $offset + $upper_limit;
          $query = $wpdb->prepare("SELECT id FROM {$tweet_table} GROUP BY twid LIMIT %d,%d",$offset,$limit);
          $tweet_ids = $wpdb->get_col($query);

          if(is_array($tweet_ids) and count($tweet_ids) > 0)
          {
            $query = $wpdb->prepare("DELETE FROM {$tweet_table} WHERE id not in (" . implode(',', $tweet_ids) . ")");
            $wpdb->query($query);
          }
        }
      }
      */
    }
  }


  function migrate_after_db_upgrade()
  {
    global $prli_options, $prli_link, $prli_link_meta, $prli_click, $wpdb;
    $db_version = (int)get_option('prli_db_version');

    if(!$db_version)
      return;

    if($db_version and $db_version < 5)
    {
      // Migrate pretty-link-posted-to-twitter
      $query = "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key=%s";
      $query = $wpdb->prepare($query,'pretty-link-posted-to-twitter');
      $posts_posted = $wpdb->get_results($query);

      foreach($posts_posted as $postmeta)
      {
        if($postmeta->meta_value == '1')
        {
          $link_id = PrliUtils::get_prli_post_meta($postmeta->post_id,'pretty-link',true);
          $prli_link_meta->update_link_meta($link_id,'pretty-link-posted-to-twitter','1');
        }
      }

      // Cleanup
      $query = "DELETE FROM {$wpdb->prefix}postmeta WHERE meta_key=%s OR meta_key=%s OR meta_key=%s OR meta_key=%s";
      $query = $wpdb->prepare($query,'pretty-link-posted-to-twitter','pretty-link-tweet-count','pretty-link-tweet-last-update','prli-keyword-replacement-count');
      $results = $wpdb->query($query);

      $query = "DELETE FROM {$prli_link_meta->table_name} WHERE meta_key=%s";
      $query = $wpdb->prepare($query,'prli-url-aliases');
      $results = $wpdb->query($query);
    }
  }

  function this_is_a_robot($browser_ua,&$browsecap,$header='')
  {
    $click = new PrliClick();
    $click->browser = $browser_ua;
    $click->btype = $browsecap['browser'];
    return $this->is_robot($click, $browsecap, $header);
  }

  function is_robot(&$click,&$browsecap,$header='')
  {
    global $prli_utils, $prli_click, $prli_options;
    $ua_string = trim(urldecode($click->browser));
    $btype = trim($click->btype);

    // Yah, if the whole user agent string is missing -- wtf?
    if(empty($ua_string))
      return 1;

    // If we're doing extended tracking and the Browser type
    // was unidentifiable then it's most likely a bot
    if( isset($prli_options->extended_tracking) and
        $prli_options->extended_tracking == 'extended' and 
        empty($btype) )
      return 1;

    // Some bots actually say they're bots right up front let's get rid of them asap
    if(preg_match("#(bot|Bot|spider|Spider|crawl|Crawl)#",$ua_string))
      return 1;

    $crawler = $browsecap['crawler'];

    // If php_browsecap tells us its a bot, let's believe him
    if($crawler == 1)
      return 1;

    return 0;
  }

  function get_permalink_pre_slug_uri($force=false,$trim=false)
  {
    global $prli_options;

    if($force or $prli_options->link_prefix)
    {
      preg_match('#^([^%]*?)%#', get_option('permalink_structure'), $struct);
      $pre_slug_uri = $struct[1];

      if($trim)
      {
        $pre_slug_uri = trim($pre_slug_uri);
        $pre_slug_uri = preg_replace('#^/#','',$pre_slug_uri);
        $pre_slug_uri = preg_replace('#/$#','',$pre_slug_uri);
      }

      return $pre_slug_uri;
    }
    else
      return '/';
  }

  function get_permalink_pre_slug_regex()
  {
    $pre_slug_uri = PrliUtils::get_permalink_pre_slug_uri(true);

    if(empty($pre_slug_uri))
      return '/';
    else
      return "{$pre_slug_uri}|/";
  }
    
  function rewriting_on()
  {
    $permalink_structure = get_option('permalink_structure');
  
    return ($permalink_structure and !empty($permalink_structure));
  }

  function get_prli_post_meta($post_id, $key, $single=false)
  {
    if( isset($post_id) and !empty($post_id) and
        $post_id and is_numeric($post_id) ) 
      return get_post_meta($post_id, $key, $single);
    else
      return false;
  }

  function update_prli_post_meta($post_id, $meta_key, $meta_value)
  {
    if( isset($post_id) and !empty($post_id) and
        $post_id and is_numeric($post_id) ) 
      return update_post_meta($post_id, $meta_key, $meta_value);
    else
      return false;
  }

  function delete_prli_post_meta($post_id, $key)
  {
    if( isset($post_id) and !empty($post_id) and
        $post_id and is_numeric($post_id) ) 
      return delete_post_meta($post_id, $key);
    else
      return false;
  }

  /** Gets rid of any pretty link postmetas created without a post_id **/
  function clear_unknown_post_metas()
  {
    global $wpdb;

    $query = "SELECT count(*) FROM {$wpdb->postmeta} WHERE ( meta_key LIKE 'prli%' OR meta_key LIKE 'pretty-link%' OR meta_key LIKE '_prli%' OR meta_key LIKE '_pretty-link%' ) AND post_id=0";
    $count = $wpdb->get_var($query);

    if($count)
    {
      $query = "DELETE FROM {$wpdb->postmeta} WHERE ( meta_key LIKE 'prli%' OR meta_key LIKE 'pretty-link%' OR meta_key LIKE '_prli%' OR meta_key LIKE '_pretty-link%' ) AND post_id=0";
      $wpdb->query($query);
    }
  }
}
?>
