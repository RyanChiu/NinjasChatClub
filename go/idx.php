<?php
include "../app/vendors/extrakits.inc.php";
//echo print_r($_SERVER, true);
$tmp = explode("/", $_SERVER['PHP_SELF']);
$url = "http://" . $_SERVER['SERVER_ADDR'] . "/" . $tmp[1];
//echo "<hr/><br/>";

if (isset($_GET['to'])) {
    //echo $url . "/accounts/go/" . decrypt($_GET['to'], LINKGENKEY);
    header("Location: " . $url . "/accounts/go/" . decrypt($_GET['to'], LINKGENKEY));
} else {
    header("Location: " . $url);
}
exit(0);
?>
