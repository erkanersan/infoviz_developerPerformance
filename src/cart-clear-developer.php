<?php
session_start();

$cartdev = array();
$_SESSION['cartdev'] = $cartdev;

echo "CART has been cleared";
echo $_SESSION['cartdev'];

?>
