<?php include("infovis.inc.php");?>
<?php

$q = $_GET["q"];
$q = strtolower_tr( $q );
if (!$q) return;

$query  = "select users.id as id, users.name as username, count(commits.id) as numofcommits";
$query .= " from commits, users";
$query .= " where users.id=commits.committer_id and commits.project_id=$q";
$query .= " group by username";
$query .= " order by numofcommits desc";


$link = dbconnect();
$result = mysqli_query($link, $query );

$numOfRecord = mysqli_num_rows($result);
$totalcommits = 0;
while ( $row = mysqli_fetch_assoc($result) ) {
        $totalcommits += $row["numofcommits"];
}
$average = round($totalcommits/$numOfRecord);

$result = mysqli_query($link, $query );

echo "[";
$row = mysqli_fetch_assoc($result);
$userid    = $row["id"];
$username  = $row["username"];
$username  = str_Ereplace('"', '', $username);
$username  = stripslashes($username);
$numofcommits = $row["numofcommits"];
$maxcommits = $numofcommits;
$maxrange = $maxcommits * 1.1;
$minrange = $average * 0.2;
$markers = $average * 1.2;
echo "\n\t{\"id\":$userid,\"title\":\"$username\",\"subtitle\":\"$numofcommits commits\",\"ranges\":[$minrange,$average,$maxrange],\"measures\":[$numofcommits,$numofcommits],\"markers\":[$average]}";

while ( $row = mysqli_fetch_assoc($result) ) {
	$userid    = $row["id"];
	$username  = $row["username"];
	$username  = str_Ereplace('"', '', $username);
        $username  = stripslashes($username);
	$numofcommits = $row["numofcommits"];
	echo ", \n\t{\"id\":$userid,\"title\":\"$username\",\"subtitle\":\"$numofcommits commits\",\"ranges\":[$minrange,$average,$maxrange],\"measures\":[$numofcommits,$numofcommits],\"markers\":[$average]}";
}
echo "]";

mysqli_close($link); //close db connection
?>
