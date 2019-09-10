<?php

/* List of all sites on Plesk hosting platform

         "acino.by",
         "concor.acino.by",
         "euthyrox.acino.by",
         "glucophage.acino.by",
         "acino.kz",
         "bioson.com.ua",
         "estrovel.ru",  - DISABLED (reason: frequent change content)
         "liniderm.com.ua",
         "marimer.ua",
         "moringa.com.ua",
         "moringa.in.ua",
         "mozok.ua",
         "timefaktor.ru",
         "autist.world" - DISABLED (reason: site is not exist)
*/

$SITES = array (
         "acino.by",
         "concor.acino.by",
         "euthyrox.acino.by",
         "glucophage.acino.by",
         "acino.kz",       
         "bioson.com.ua",
         "liniderm.com.ua",
         "marimer.ua",
         "moringa.com.ua",
         "moringa.in.ua",
         "mozok.ua",
         "timefaktor.ru"
         );

/*
 Some global env
*/

$interface = php_sapi_name();             // CLI or CGI
$server = "acino.eukservers.com";
$script = basename($_SERVER['PHP_SELF']); // called script name
?>

