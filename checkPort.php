<?php

require 'sites.php';

# List of the most important services on localhost


$PORTS = array (
           'FTP'       => '21',
           'SMTP'      => '25',
           'DNS'       => '53',
           'POP3'      => '110',
           'SMTPS'     => '465',
           'IMAP'      => '143',
           'IMAPS'     => '993',
           'POP3S'     => '995',
           'MySQL'     => '3306',
           'REDIS'     => '6379',
           'PLESK'     => '8443'  
               );

if ($interface == 'cli') {

if (isset($argv[1]) AND in_array($argv[1],$PORTS)) {

$port    = $argv[1];
$service = array_search($port, $PORTS);
$check   = exec("netstat -lnt|grep -w $port");
$string  = $service."state";

/*
  Output to Solar Winds 
*/

if ($check != "") {
    echo "Statistic.$string:1 \n";
} 
else {
    echo "Statistic.$string:0 \n";
}

}
else {
echo "Usage: php checkPorts.php port\n
Port need to specify like a num\n
Permit a next arguments:\n
 - 21 (ftp)\n
 - 25 (smtp)\n
 - 53 (dns)\n
 - 110 (pop3)\n
 - 465 (smtp-ssl)\n
 - 143 (imap)\n
 - 993 (imap-ssl)\n
 - 995 (pop3-ssl)\n
 - 3306 (Mysql)\n
 - 6379 (redis image)\n
 - 8443 (Plesk panel)\n\n";
}
}
else {

header("cache-control: no-cache, must-revalidate, max-age=0");

echo "<b>$script</b> - in http mode provide a summary info about all TCP service ports.<br><pre>\n";

foreach ($PORTS as $service => $port) {
        echo "$service state: \n";
        passthru("netstat -ltn|grep -w $port");
        echo "---\n";
}
echo "</pre>";

require 'footer.php';

}


?>
