<?php

require 'sites.php';
require 'redis.php';

/*
$redis->flushall();         - flush ALL items
$redis->set('Key','Value'); - set new Item
$redis->get('Key');         - get Item by Key
$redis->del('Key');         - delete Item by Key
$redis->keys('*');          - list all Keys
*/

$CHANGEDP=0;
$SITEMAPSDIR="/var/www/vhosts/acino.eukservers.com/sw/sitemaps/";
$count=0;
$push=0;

if (isset($_POST['push'])) $push=True;

function tempToArray ($text) {
         if ($text) {
            while (($buffer = fgets($text)) !== false) {
            $arr[] = $buffer;
            }
         return $arr;
         }
         else return "false";
}

function checkAvailability ($s) {

         $resp [] = @explode(" ",exec("curl -sI http://$s|head -1"))[1];
         $resp [] = @explode(" ",exec("curl -sI https://$s|head -1"))[1];
         $c       = preg_grep("/.*200.*/", $resp);
         unset($resp);

         if (empty($c)) return false;
         else return true;           
}


if ($interface == 'cli'OR $push) {

foreach($SITES as $site) {
         
         echo "<pre>\n".$site." in process...<hr>\n\n";
         $availability = checkAvailability($site);
         if (!$availability) continue; 
         $urls = '';
         $sitemapFile = $SITEMAPSDIR.$site.".xml";
         $sitemap     = fopen("$sitemapFile", 'r');
         $urls = tempToArray($sitemap);
         fclose($sitemap);
         
         if ($interface != 'cli'){
         ob_flush();
         flush();
         }
         
         foreach($urls as $url) {
                 
                #echo "on URL: $url\n"; 
                exec("curl -s $url", $content);
                $tokens = preg_grep("/.*token.*/", $content);
                 
                if (empty($tokens) !== true) {
                   foreach($tokens as $index => $value) {
                           
                           unset($content[$index]); 
                   }
                }
                 
                 
                $hash = md5(implode($content));
                $key  = "sites:".substr($url,0,-1);
                unset($content);           
         
         if ((isset($argv[1]) AND $argv[1] == "push") OR $push) {
                       
            # Install new Trusted hashes
            echo "[$count] Make hash for: $url <br>\n";
            ++$count;
            $redis->del($key);            
            $redis->set($key,$hash);
            exec('cat /dev/null > content-error.log');   
            
            }
            
            # Check content
            $redHash = $redis->get($key);                      
            if ($hash != $redHash) {
               $logfile = fopen("content-error.log", 'a');
               $report = "NOTICE: ".@date(DATE_COOKIE)." $url - this page was changed, need to check or create new pool of hashes.\n";
               #echo $report;
               fwrite($logfile, $report);
               fclose($logfile);
               ++$CHANGEDP;
               
            }
                     
        }
        echo "</pre>";
}      
   if ($interface != 'cli') require 'footer.php';
   else {
   # Output to Solar Wind
   echo $CHANGEDP > 0 ? "\nStatistic.NumOfChangedPages:$CHANGEDP" : "\nStatistic.NumOfChangedPages:0";   
   }
}
else {
     header("cache-control: no-cache, must-revalidate, max-age=0");
     echo "\n<b>$script</b> - provide check of page contents of all monitored domains.<br>\n
         In any warning alerts see the <a href=\"/sw/content-error.log\">LOG FILE</a><br>\n
         Script allows you to Push a new hashes to storage, but first ensure that all warning URLs from Log is alright.<br>\n
         <form method='POST'>
         <input type='hidden' name='push' value='True'>
         <input type='submit' value='PUSH'>
         </form><br>\n";

     require 'footer.php';
}

?>


