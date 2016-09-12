<?php include("infovis.inc.php");?>
<?php
// RESULT: | projectId | projectName | Forks |
$query = "select p.id, UPPER(p.name) name, p.language, count(c.cid) as size from projects p inner join (select distinct committer_id as cid, project_id from commits) c on p.id=c.project_id where p.forked_from is null group by p.id, p.name, p.language";

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
