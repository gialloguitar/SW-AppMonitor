<?php

require 'sites.php';
echo "<b>SITES:</b><hr><pre>";
$count=1;

ob_implicit_flush();

foreach ($SITES as $site) {

echo "$count) $site<br>\n";
++$count;
sleep(1);

#ob_flush();
#flush();
}

echo "</pre>";
require 'footer.php';
?>
