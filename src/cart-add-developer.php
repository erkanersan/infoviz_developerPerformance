<?php
session_start();

$id = intval($_POST["id"]);

array_push($_SESSION['cartdev'], $id);
$_SESSION['cartdev'] = array_unique($_SESSION['cartdev']);

//echo "Developer added into the Comparison List.\n\n"; 
//echo var_dump($_SESSION['cartdev']);
echo count($_SESSION['cartdev']);
?>
