<?php echo "date,count\n";?>
<?php include("infovis.inc.php");?>
<?php
$_id = $_GET["id"];
$_type = $_GET["type"];

switch ($_type) {
    case "commits":
      $query  = "select DATE(created_at) as date, count(*) as countx from commits where 1=1";
      if ($_id)
        $query .= " and committer_id=$_id";
      $query .= " group by date";
      break;

    case "pullrequests":
      $query  = "select DATE(ph.created_at) as date, count(p.user_id) as countx from pull_requests p, pull_request_history ph where p.pullreq_id=ph.pull_request_id";
      if ($_id)
        $query .= " and p.user_id=$_id";
      $query .= " and ph.action = 'opened' group by date";
      break;

    case "issues":
      $query  = "select DATE(created_at) as date, count(*) as countx from issues where 1=1";
      if ($_id)
        $query .= " and reporter_id=$_id";
      $query .= " group by date";
      break;

    case "comments":
      $query  = "select DATE(created_at) as date, count(*) as countx from commit_comments where 1=1";
      if ($_id)
        $query .= " and user_id=$_id";
      $query .= " group by date";
      break;
}

echo $query;

$link = dbconnect();
$result = mysqli_query($link, $query );

while ( $row = mysqli_fetch_assoc($result) ) {
	echo $row["date"] . "," . $row["countx"] ."\n";
}
mysqli_close($link); //close db connection
?>
