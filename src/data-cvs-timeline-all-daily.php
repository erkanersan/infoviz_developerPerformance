<?php echo "date,count\n";?>
<?php include("infovis.inc.php");?>
<?php
$_id   = $_GET["id"];
$_type = $_GET["type"];

switch ($_type) {
    case "commits":
      $query = "select ca.datefield as date, ifnull(co.committer_id,$_id) as cid, count(co.committer_id) as countx from (select datefield from calendar where year(datefield) between 2005 and 2013) ca left join (select date(created_at) as cdate, committer_id from commits co where year(created_at) between 2005 and 2013 and committer_id = $_id) co on ca.datefield = co.cdate group by date, cid";
      break;
    case "pullrequests":
      $query = "select datefield as date, ifnull(ppu.id,$_id) cid, ifnull(ppu.pull_count,0) countx from (select datefield from calendar where year(datefield) between 2005 and 2013) ca left join ( select date(ph.created_at) cdate, u.id, u.name as username, u.login, count(p.pullreq_id) pull_count from pull_requests p, users u, pull_request_history ph where u.id=p.user_id and p.pullreq_id=ph.pull_request_id and ph.action = 'opened' and u.id = $_id group by cdate, username, u.login, u.id ) ppu on ca.datefield = ppu.cdate";
      break;
    case "issues":
      $query = "select datefield as date, ifnull(iss.id,$_id) cid, ifnull(iss.size,0) countx from (select datefield from calendar where year(datefield) between 2005 and 2013) ca left join ( select date(i.created_at) cdate, users.id as id, users.name as username, count(i.reporter_id) as size from issues i right join users on users.id=i.reporter_id where users.id=$_id group by cdate, users.name, users.id) iss on ca.datefield = iss.cdate";
      break;
    case "comments":
      $query = "select datefield as date, ifnull(c.id,$_id) cid, ifnull(c.size,0) countx from (select datefield from calendar where year(datefield) between 2005 and 2013) ca left join ( select date(cc.created_at) cdate, users.id as id, users.name as username, count(cc.user_id) as size from commit_comments cc right join users on cc.user_id=users.id where users.id=$_id  group by cdate, users.name, users.id) c on ca.datefield = c.cdate";
      break;
}


$link = dbconnect();
$result = mysqli_query($link, $query );
while ( $row = mysqli_fetch_assoc($result) ) {
	echo $row["date"] . "," . $row["countx"] ."\n";
}
mysqli_close($link); //close db connection
?>
