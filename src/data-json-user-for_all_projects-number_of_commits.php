<?php include("infovis.inc.php");?>
<?php
$_userid = $_GET["id"];

$query = "select p.id, p.name, p.language, count(c.id) size from users u, projects p, commits c where u.id=c.committer_id and p.id=c.project_id and u.id=$_userid group by p.name order by p.name";

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
