<?php

require 'sites.php';

$DOWN=0;
$OUT   = array ();

function curlRequest ($s) {

         $respHTTP    = @explode(" ",exec("curl -sI http://$s|head -1"))[1];
         $respHTTPs   = @explode(" ",exec("curl -sI https://$s|head -1"))[1];
         if ($respHTTP == '200' || $respHTTPs == '200') return "OK";
         elseif ($respHTTP == FALSE && $respHTTPs == FALSE) return "has a null HTTP(s) responses, could not to resolve.\n";
         else return "has a bad HTTP: (".$respHTTP.")/HTTPS: (".$respHTTPs.") responses\n";

}


if ($interface == 'cli') {
foreach ($SITES as $site) {


         $check = curlRequest($site);

         if ($check != 'OK') {
             $logfile = fopen("response-error.log", 'a');
             $report = "WARNING: ".@date(DATE_COOKIE)." ".strtoupper($site)." - ".$check;
             #echo $report;
             fwrite($logfile, $report);
             fclose($logfile);

             ++$DOWN;
             }

}

/*
 Purge log file if all responses is passed successfully
*/
if ($DOWN === 0) exec('cat /dev/null > response-error.log');
 

/*
 Output to Solar Wind
*/
echo $DOWN > 0 ? "Statistic.SitesIsDown:$DOWN" : "Statistic.SitesIsDown:0";
}
else {

header("cache-control: no-cache, must-revalidate, max-age=0");

echo "<b>$script</b> - provide checks of all http(s) responses of monitored domains.<br>\n
      In any warning alerts see the <a href=\"/sw/response-error.log\">LOG FILE</a>, which will be empty if all responses is OK.<br>\n";

require 'footer.php';

}


?>

