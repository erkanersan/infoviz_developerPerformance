<?php include("infovis.inc.php");?>
<?php
session_start();

$query  = "select users.id as id, users.name as username, count(commits.id) as numofcommits";
$query .= " from commits, users";
$query .= " where users.id=commits.committer_id";
$query .= " and (users.id=-1 ";
foreach ($_SESSION['cartdev'] as $userid) {
	$query .= " or users.id=$userid";
}
$query .= ") ";
$query .= " group by username";
$query .= " order by numofcommits desc";


$query  = "select  users.id as id, users.name as username, count(p.user_id) size";
$query .= " from pull_requests p";
$query .= " right join users on p.user_id = users.id";
$query .= " where (users.id=-1 ";
foreach ($_SESSION['cartdev'] as $userid) {
        $query .= " or users.id=$userid";
}
$query .= ") ";
$query .= " group by id, username";
$query .= " order by size desc";

$link = dbconnect();
$result = mysqli_query($link, $query );

$numOfRecord = mysqli_num_rows($result);
$totalsize = 0;
while ( $row = mysqli_fetch_assoc($result) ) {
        $totalsize += $row["size"];
}
$average = round($totalsize/$numOfRecord);

$result = mysqli_query($link, $query );

echo "[";
$row = mysqli_fetch_assoc($result);
$userid    = $row["id"];
$username  = $row["username"];
$username  = str_Ereplace('"', '', $username);
$username  = stripslashes($username);
$size = $row["size"];
$max = $size;
$maxrange = $max * 1.1;
$minrange = $average * 0.2;
$markers = $average * 1.2;
echo "\n\t{\"id\":$userid,\"title\":\"$username\",\"subtitle\":\"$size pull requests\",\"ranges\":[$minrange,$average,$maxrange],\"measures\":[$size,$size],\"markers\":[$average]}";

while ( $row = mysqli_fetch_assoc($result) ) {
	$userid    = $row["id"];
	$username  = $row["username"];
	$username  = str_Ereplace('"', '', $username);
	$username  = stripslashes($username);
	$size = $row["size"];
	echo ", \n\t{\"id\":$userid,\"title\":\"$username\",\"subtitle\":\"$size pull requests\",\"ranges\":[$minrange,$average,$maxrange],\"measures\":[$size,$size],\"markers\":[$average]}";
}
echo "]";

mysqli_close($link); //close db connection
?>
