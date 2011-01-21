<?php
class PrliLinkMeta
{
  var $table_name;

  function PrliLinkMeta()
  {
    global $wpdb;
    $this->table_name = "{$wpdb->prefix}prli_link_metas";
  }

  function get_link_meta($link_id,$meta_key,$return_var=false)
  {
      global $wpdb;
      $query_str = "SELECT meta_value FROM {$this->table_name} WHERE meta_key=%s and link_id=%d";
      $query = $wpdb->prepare($query_str,$meta_key,$link_id);

      if($return_var)
        return $wpdb->get_var("{$query} LIMIT 1");
      else
        return $wpdb->get_col($query, 0);
  }

  function add_link_meta($link_id, $meta_key, $meta_value)
  {
    global $wpdb;

    $query_str = "INSERT INTO {$this->table_name} " .
                 '(meta_key,meta_value,link_id,created_at) VALUES (%s,%s,%d,NOW())';
    $query = $wpdb->prepare($query_str, $meta_key, $meta_value, $link_id);
    return $wpdb->query($query);
  }

  function update_link_meta($link_id, $meta_key, $meta_values)
  {
    global $wpdb;
    $this->delete_link_meta($link_id, $meta_key);

    if(!is_array($meta_values))
      $meta_values = array($meta_values);

    $status = false;
    foreach($meta_values as $meta_value)
      $status = $this->add_link_meta($link_id, $meta_key, $meta_value);

    return $status;
  }

  function delete_link_meta($link_id, $meta_key)
  {
    global $wpdb;

    $query_str = "DELETE FROM {$this->table_name} " .
                 "WHERE meta_key=%s AND link_id=%d";
    $query = $wpdb->prepare($query_str, $meta_key, $link_id);
    return $wpdb->query($query);
  }
}
?>
