<?php include( "infovis.inc.php" );?>
<?php
$_userid = $_GET["id"];
$link = dbconnect();

// Number Information
$query  = "select *";
$query .= " from users where 1=1";
$query .= " and users.id='$_userid'";

$result = mysqli_query($link, $query );

while ( $row = mysqli_fetch_assoc($result) ) {
	$login= $row["login"];
	$name = $row["name"];
	$location = $row["location"];
}

echo $name
?>
