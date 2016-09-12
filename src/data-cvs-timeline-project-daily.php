<?php echo "date,count\n";?>
<?php include("infovis.inc.php");?>
<?php
$id = $_GET["id"];

$query = "select ca.datefield as date, ifnull(cp.id,$id) as pid, count(cp.committer_id) as countx from (select datefield from calendar where year(datefield) between 2005 and 2013) ca left join (select date(co.created_at) as cdate, co.committer_id, pr.id from commits co inner join projects pr on co.project_id=pr.id  where pr.id=$id) cp on ca.datefield = cp.cdate group by datefield, pid";

$link = dbconnect();
$result = mysqli_query($link, $query );
while ( $row = mysqli_fetch_assoc($result) ) {
	echo $row["date"] . "," . $row["countx"] ."\n";
}
mysqli_close($link); //close db connection
?>
