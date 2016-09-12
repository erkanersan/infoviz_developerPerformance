<?php include("infovis.inc.php");?>
<?php
$q = $_GET["q"];
$q = strtolower_tr( $q );
if (!$q) return;

$query  = "select users.id as userid, users.name as username, count(commits.id) as numofcommits";
$query .= " from commits, users";
$query .= " where users.id=commits.committer_id and commits.project_id=$q";
$query .= " group by username";
$query .= " order by numofcommits desc";

$link = dbconnect();
$result = mysqli_query($link, $query );
$num_rows = mysqli_num_rows($result);
$current_row = 0;

echo "[";

while ( $row = mysqli_fetch_assoc($result) ){
	$userid = $row["userid"];
	$username  = str_Ereplace('"', '', $row["username"]);
	$username  = stripslashes($username);
	$numofcommits = $row["numofcommits"];
	$current_row++;

	echo "{\"articles\": [";

	$query02  = "select users.name as username, YEAR(commits.created_at) as year, count(commits.id) as year_numofcommits";
	$query02 .= " from commits, users";
	$query02 .= " where users.id=commits.committer_id and users.id=$userid and commits.project_id=$q";
	$query02 .= " group by username, year";
	$query02 .= " order by username, year";

	$result02 = mysqli_query($link, $query02 );
	$num_rows02 = mysqli_num_rows($result02);
	$current_row02 = 0;
	while ( $row02 = mysqli_fetch_assoc($result02) ) {
        	$year = $row02["year"];
        	$year_numofcommits = $row02["year_numofcommits"];
		$current_row02++;
        	echo "[$year, $year_numofcommits]";
        	if ($current_row02 != $num_rows02) { echo ",";}
	}

	echo "], \"total\": $numofcommits, \"id\": $userid, \"name\": \"$username\"}";
	if ($current_row != $num_rows) { echo ",";}
}

echo "]";

mysqli_close($link); //close db connection
?>
