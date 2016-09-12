<?php include("infovis.inc.php");?>
<?php
//$q = $_GET["q"];
//$q = strtolower_tr( $q );
//if (!$q) return;
$query = "select users.name as name, count(*) as count from users, project_members,projects where projects.id=project_members.repo_id and users.id=project_members.user_id and projects.forked_from is null and users.name is not null and users.name!=\"\" group by users.name order by count limit 5";

$link = dbconnect();
$result = mysqli_query($link, $query );

echo "{ \"name\": \"Developers\", \"children\": [";

$row = mysqli_fetch_assoc($result);
$name  = $row["name"];
$name  = str_Ereplace('"', '', $name);
$username  = stripslashes($username);
$count = $row["count"];
echo "\n\t{\"name\": \"$name\", \"size\": $count}";

while ( $row = mysqli_fetch_assoc($result) ) {
	$name  = $row["name"];
	$name  = str_Ereplace('"', '', $name);
	$username  = stripslashes($username);
	$count = $row["count"];
	echo ", \n\t{\"name\": \"$name\", \"size\": $count}";
}

echo "] }";

mysqli_close($link); //close db connection
?>
