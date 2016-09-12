<?php include("infovis.inc.php");?>
<?php
session_start();

//user list
$user_list .= " (user_id=-1 ";
foreach ($_SESSION['cartdev'] as $userid) {
        $user_list .= " or user_id=$userid";
}
$user_list .= ") ";

//committer list
$committer_list .= " (committer_id=-1 ";
foreach ($_SESSION['cartdev'] as $userid) {
	$committer_list .= " or committer_id=$userid";
}
$committer_list .= ") ";

$query = "select co.cid as id, co.username, co.login, co.total_commits, ifnull(pr.total_requests,0) as pullrequests, ifnull(pr.total_merged,0) as merged, 100*(ifnull(pr.total_merged,0)/ifnull(pr.total_requests,1)) success_rate from ( select c.committer_id as cid, u.name as username, u.login, count(c.id) total_commits from commits c, users u where c.committer_id=u.id and $committer_list group by c.committer_id, u.name, u.login ) co left join ( select m1.user_id, m1.total_merged, m2.total_requests from ( select user_id, count(merged) total_merged from pull_requests p where merged = 1 and $user_list group by user_id ) m1 inner join( select user_id, count(merged) total_requests from pull_requests p where $user_list  group by user_id ) m2 on m1.user_id = m2.user_id ) pr on pr.user_id = co.cid"; 

$link = dbconnect();
$result = mysqli_query($link, $query );

$labels = array();
$successrate_label = array();

while ( $row = mysqli_fetch_assoc($result) ) {
	$userid    = $row["id"];

	$username  = $row["username"];
	$username  = str_Ereplace('"', '', $username);
	$username  = stripslashes($username);
	array_push($labels, $username);

	$successrate   = round($row["success_rate"]);
	array_push($successrate_label, $successrate);
}

echo "{ \"labels\": [\"".implode("\",\"",$labels)."\"], ";
echo "\"series\": [";
echo "{ \"label\": \"Success Rate\", \"values\": [\"".implode("\",\"",$successrate_label)."\"] }"; 
echo "] }";

mysqli_close($link); //close db connection
?>
