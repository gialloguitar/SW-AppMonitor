<?php

require 'redis.php';

/*
$redis->flushall();         - flush ALL items
$redis->set('Key','Value'); - set new Item
$redis->get('Key');         - get Item by Key
$redis->del('Key');         - delete Item by Key
$redis->keys('*');          - list all Keys
*/

$COMMAND = array ('set','get','del','flushall','info');



if (isset($argv[1]) AND in_array($argv[1],$COMMAND)) {

      $comm  = $argv[1];
      
      # GET, DEL query
      if(isset($argv[2]) AND $comm != "set") { 
      $key    = $argv[2];
      $value  = $redis->$comm($key);
      print_r($value);
      }
      # SET
      elseif (isset($argv[3]) AND $comm == "set") {
      $key    = $argv[2];
      $value  = $argv[3];
      $redis->$comm($key,$value);
      }
      # COMMAND without arg
      else {      
      $value = $redis->$comm();
      print_r($value);
      }
}
elseif (!isset($argv[1])){
      $value  = $redis->keys('*');
      print_r($value);
}
else {
echo "\n
Interface for most frequently used redis queries\n\n
Usage: php redQuery.php [command] [[key] [value]]\n
Command: set, get, del, flushall, info\n
By default (without arguments): keys(*)
\n\n";
}

#$value = $redis->keys('*');

#print_r($value);

?>
