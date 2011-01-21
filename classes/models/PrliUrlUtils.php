<?php
class PrliUrlUtils {

  function get_title($url, $slug='')
  {
    $title = '';

    $data = PrliUrlUtils::curl_read_remote_file($url);

    // Look for <title>(.*?)</title> in the text
    if($data and preg_match('#<title>[\s\n\r]*?(.*?)[\s\n\r]*?</title>#im', $data, $matches))
      $title = trim($matches[1]);

    if(empty($title) or !$title)
      return $slug;
    
    return $title;
  }
  
  function valid_url($url)
  {
    $data = PrliUrlUtils::curl_read_remote_header($url);

    if(!empty($data) and $data)
    {
       preg_match("/HTTP\/1\.[1|0]\s(\d{3})/",$data,$matches);
       return ($matches[1] == '200');
    }

    // Let's just assume its valid if we can't test it
    return true;
  }

  function curl_read_remote_header($url)
  {
    if(function_exists('curl_init'))
    {
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HEADER, true);
      curl_setopt($ch, CURLOPT_NOBODY, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

      $data = curl_exec($ch);

      curl_close($ch);
      
      return $data;
    }
    
    return false;
  }

  function curl_read_remote_file($url)
  {
    if(function_exists('curl_init'))
    {
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

      $data = curl_exec($ch);

      curl_close($ch);
      
      return $data;
    }
    
    return false;
  }

  /**
  * Sends http request ensuring the request will fail before $timeout seconds
  * gotta use a socket connection because some hosting setups don't allow fopen.
  * Supports SSL sites as well as 301, 302 & 307 redirects
  * Returns the response content (no header, trimmed)
  * @param string $url
  * @param string $num_chunks Set to 0 if you want to read the full file
  * @param string $chunk_size In bytes
  * @param int $timeout
  * @return string|false false if request failed
  */
  function read_remote_file($url, $num_chunks=0, $headers='', $params='', $chunk_size=1024, $timeout=30 )
  {
    $purl = @parse_url($url);

    $sock_host   = $purl['host'];
    $sock_port   = ($purl['port']?(int)$purl['port']:80);
    $sock_scheme = $purl['scheme'];

    $req_host    = $purl['host'];
    $req_path    = $purl['path'];

    if(empty($req_path))
      $req_path = "/";

    if($sock_scheme == 'https')
    {
      $sock_port = 443;
      $sock_host = "ssl://{$sock_host}";
    }

    $fp = fsockopen($sock_host, $sock_port, $errno, $errstr, $timeout);
    $contents = '';
    $header = '';

    if (!$fp)
      return false;
    else
    {
      // Send get request
      $request = "GET {$req_path}{$params} HTTP/1.1\r\n";
      $request .= "Host: {$req_host}\r\n";
      $request .= $headers;
      $request .= "Connection: Close\r\n\r\n";
      fwrite($fp, $request);

      // Read response
      $head_end_found = false;
      $buffer = '';
      for($i = 0; !feof($fp); $i++)
      {
        if($num_chunks > 0 and $i >= $num_chunks)
          break;

        $out = fread($fp,$chunk_size);
        if($head_end_found)
          $contents .= $out;
        else
        {
          $buffer .= $out;
          $head_end = strpos($buffer, "\r\n\r\n");
          if($head_end !== false)
          {
            $head_end_found = true;
            $contents .= substr($buffer, ($head_end + 4));
            $header .= substr($buffer, 0, $head_end);
            // Follow HTTP redirects
            if(preg_match("#http/1\.1 (301|302|307)#i",$header))
            {
              preg_match("#^Location:(.*?)$#im",$header,$matches);
              return PrliUrlUtils::read_remote_file(trim($matches[1]));
            }
            else if(preg_match("#http/1\.1 (400|401|402|403|404|405|406|407|408|409|410|411|412|413|414|415|416|417|500|501|502|503|504|505)#i",$header))
              return false; // The file wasn't found
          }
        }
      }
      fclose($fp);
    }

    if(empty($contents))
      return false;
    else
      return trim($contents);
  }
}
?>
