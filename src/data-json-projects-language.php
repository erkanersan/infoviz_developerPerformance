<?php include("infovis.inc.php");?>
<?php
$query = "select language, count(*) as count from projects where forked_from is null and language is not null group by language";

$link = dbconnect();
$result = mysqli_query($link, $query );

$rows = array();
while($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}
print json_encode($rows);

mysqli_close($link); //close db connection
?>
