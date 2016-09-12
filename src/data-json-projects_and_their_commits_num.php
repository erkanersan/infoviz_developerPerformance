<?php include("infovis.inc.php");?>
<?php
$query = "select p.id, UPPER(p.name) as name, p.language, count(c.id) size from projects p, commits c where p.forked_from is null and p.id=c.project_id group by p.name,p.language order by size desc;";

$link = dbconnect();
$result = mysqli_query($link, $query );

$rows = array();
while($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}
print "{\"name\": \"ROOT\",\"children\":";
print json_encode($rows);
print "}";

mysqli_close($link); //close db connection
?>
