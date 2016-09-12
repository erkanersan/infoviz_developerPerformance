<?php include("infovis.inc.php");?>
<?php
// RESULT: | id | name | size 
$query = "select u.id id, u.name name, count(*) size from users u, commits c  where u.id=c.committer_id and u.name is not null and u.name!='' group by u.name order by size desc limit 1000";

$link = dbconnect();
$result = mysqli_query($link, $query );

$rows = array();
while($row = mysqli_fetch_assoc($result)) {
    //print str_normalize($row["name"]);
    $rows[] = $row;
}
print "{\"name\": \"ROOT\",\"children\":";
print json_encode($rows);
print "}";

mysqli_close($link); //close db connection
?>
