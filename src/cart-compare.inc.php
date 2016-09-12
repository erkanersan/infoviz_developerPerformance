<?php
session_start();

$cartdev = array();

if (!isset($_SESSION['cartdev'])) {
  $_SESSION['cartdev'] = $cartdev;
} else {
  $cartdev = $_SESSION['cartdev'];
}


function cartstatus($_userid) {
  $cartnum = count($_SESSION['cartdev'])+1;
  $devid=$_userid;
  if (in_array(intval($devid), $_SESSION['cartdev'])) {
    $cartstatus = "<a id='anchor$devid' href='javascript:removeDeveloperFromCartDevelopers(this, $devid, $cartnum)'><img id='img$devid' src='images/cart-added.gif' vertical-align='middle'></a>";
  } else {
    $cartstatus = "<a id='anchor$devid' href='javascript:addDeveloperToCartDevelopers(this, $devid, $cartnum)'><img id='img$devid' src='images/cart-removed.gif' vertical-align='middle'></a>";
  }

  return $cartstatus;
}

?>
