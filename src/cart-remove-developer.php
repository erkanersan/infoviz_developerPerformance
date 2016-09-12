<?php
session_start();

$id = intval($_POST["id"]);


$_SESSION['cartdev'] = array_diff($_SESSION['cartdev'], array($id));
$_SESSION['cartdev'] = array_unique($_SESSION['cartdev']);

//echo "Developer removed from the Comparison List.\n\n"; 
//echo var_dump($_SESSION['cartdev']);
echo count($_SESSION['cartdev']);
?>
