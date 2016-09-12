<?php include( "infovis.inc.php" );?>
<?php
session_start();

$_projectid = intval($_POST["id"]);

$link = dbconnect();

$query  = "select users.id as userid, users.name as username, count(commits.id) as numofcommits";
$query .= " from commits, users";
$query .= " where users.id=commits.committer_id and commits.project_id=$_projectid";
$query .= " group by username";
$query .= " order by numofcommits desc";

$result = mysqli_query($link, $query );

while ( $row = mysqli_fetch_assoc($result) ) {
        $id= intval($row["userid"]);
	$_SESSION['cartdev'] = array_diff($_SESSION['cartdev'], array($id));
	$_SESSION['cartdev'] = array_unique($_SESSION['cartdev']);
}

echo count($_SESSION['cartdev']);
?>
