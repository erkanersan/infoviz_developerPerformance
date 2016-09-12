<?php include("infovis.inc.php");?>
<?php
// RESULT: | projectId | projectName | Forks |
$query = "select p1.forked_from id, UPPER(p2.name) name, p2.language language, count(*) size from projects p1 inner join  ( select id, name, language  from projects where forked_from is null) p2 on p2.id=p1.forked_from where forked_from is not null  group by p1.forked_from, p2.name having count(*) > 1";

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
