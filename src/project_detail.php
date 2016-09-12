<?php include( "htmlheader.inc.php" );?>
<?php include( "visual_bullet-charts.inc.php" );?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="cart-compare.js"></script>
<?php
$_projectid = $_GET["id"];
$link = dbconnect();

// Number Information
$query  = "select *";
$query .= " from projects where 1=1";
$query .= " and projects.id='$_projectid'";

$result = mysqli_query($link, $query);

while ( $row = mysqli_fetch_assoc($result) ) {
  $owner_id= $row["owner_id"];
  $name = $row["name"];
  $description = $row["description"];
  $language = $row["language"];
}

$query = "select count(*) as forkcount";
$query .= " from projects where 1=1";
$query .= " and projects.forked_from='$_projectid'";
$result = mysqli_query($link, $query);
while ( $row = mysqli_fetch_assoc($result) ) {
        $forkcount= $row["forkcount"];
}
?>

<script>
 var iframefile = "iframe_timeline-focus-content-user.php";
 
 function changeTimelineToCommits(projectid) {
   //document.getElementById("timeline").src = iframefile+"?type=commits&id="+projectid;
   document.getElementById("timeline").src = "iframe_focus-content-type02-timeline_commentsbyproject.php?id="+projectid;
   document.getElementById("button-timeline-commits").style.fontWeight = "bold";
   document.getElementById("button-timeline-pullrequests").style.fontWeight = "normal";
   document.getElementById("button-timeline-issues").style.fontWeight = "normal";
   document.getElementById("button-timeline-comments").style.fontWeight = "normal";
 }

 function changeTimelineToPullRequests(projectid) {
   document.getElementById("timeline").src = iframefile+"?type=pullrequests&id="+projectid;
   document.getElementById("button-timeline-commits").style.fontWeight = "normal";
   document.getElementById("button-timeline-pullrequests").style.fontWeight = "bold";
   document.getElementById("button-timeline-issues").style.fontWeight = "normal";
   document.getElementById("button-timeline-comments").style.fontWeight = "normal";
 }

 function changeTimelineToIssues(projectid) {
   document.getElementById("timeline").src = iframefile+"?type=issues&id="+projectid;
   document.getElementById("button-timeline-commits").style.fontWeight = "normal";
   document.getElementById("button-timeline-pullrequests").style.fontWeight = "normal";
   document.getElementById("button-timeline-issues").style.fontWeight = "bold";
   document.getElementById("button-timeline-comments").style.fontWeight = "normal";
 }

 function changeTimelineToComments(projectid) {
   document.getElementById("timeline").src = iframefile+"?type=comments&id="+projectid;
   document.getElementById("button-timeline-commits").style.fontWeight = "normal";
   document.getElementById("button-timeline-pullrequests").style.fontWeight = "normal";
   document.getElementById("button-timeline-issues").style.fontWeight = "normal";
   document.getElementById("button-timeline-comments").style.fontWeight = "bold";
 }
</script>

<?php
echo "<br>";
fieldsetHeaderHideable("Project - " . $name);
  echo "<table class='formTable' border=0>";
  echo "<tr><td valign='top'>";

	echo "<table class='formTable' border=0>";	
		displayText( "Description", $description);
		displayText( "Language", $language);
		//displayText( "Owner ID", $owner_id);
		displayText( "Info", $forkcount . " projects forked from $name" );
	echo "</table>";

  echo "</td><td>";

	echo "<iframe id=\"timeline\" class=\"iframe-gray\" src=\"iframe_focus-content-type02-timeline_commentsbyproject.php?id=$_projectid\" frameborder=1 width=\"1020\" height=\"130\" scrolling=\"no\"></iframe>";
echo "<br>";
      echo "<button id=\"button-timeline-commits\" style=\"font-weight: bold;\" class='button-small-styled' onclick='javascript:changeTimelineToCommits($_projectid)'>Commits</button>";
      //echo "<button id=\"button-timeline-pullrequests\" class='button-small-styled' onclick='javascript:changeTimelineToPullRequests($_projectid)'>Pull Requests</button>";
      //echo "<button id=\"button-timeline-issues\" class='button-small-styled' onclick='javascript:changeTimelineToIssues($_projectid)'>Issues</button>";
      //echo "<button id=\"button-timeline-comments\" class='button-small-styled' onclick='javascript:changeTimelineToComments($_projectid)'>Comments</button>";

  echo "</td></tr></table>";
fieldsetFooter();

echo "<br>";
$query  = "select users.id as userid, users.name as username, count(commits.id) as numofcommits";
$query .= " from commits, users";
$query .= " where users.id=commits.committer_id and commits.project_id=$_projectid";
$query .= " group by username";
$query .= " order by numofcommits desc";
$num_row = recordCount($query);
$height = 50 + ($num_row*50);

fieldsetHeaderHideable("Project Developers ($num_row)");
  echo "<table class='formTable' border=0>";
  echo "<tr><td valign=\"top\" width=\"20\">";
  echo "<div class=\"dolgu\"></div>";
  echo "<section class=\"section-styled\">";

  $query  = "select users.id as id, users.name as username, count(commits.id) as numofcommits";
  $query .= " from commits, users";
  $query .= " where users.id=commits.committer_id and commits.project_id=$_projectid";
  $query .= " group by username";
  $query .= " order by numofcommits desc";
  //$link = dbconnect();
  $result = mysqli_query($link, $query );

  while ( $row = mysqli_fetch_assoc($result) ) {
    echo "<div class=\"cart-column\">";
      $userid = $row["id"];
      echo cartstatus($userid);
    echo "</div>";
  }

  echo "</section>";
  echo "</td><td valign=\"top\" width=\"100%\">";
  echo "<button class='button-add-all-styled' onclick='addAllProjectDevelopersToCartDevelopers($_projectid)'>ADD ALL</button>";
  echo "&nbsp;<button class='button-remove-all-styled' onclick='removeAllProjectDevelopersFromCartDevelopers($_projectid)'>REMOVE ALL</button>";
  echo "<br>";
    bulletCharts("commits", $_projectid);
  echo "</td><td valign=\"top\">";
  echo "<div class=\"frame-gray-noborder\">";
  echo "<iframe src=\"visual_journals.inc.php?id=$_projectid&h=$height\" frameborder=0 width=\"500\" height=\"$height\" scrolling=\"no\"></iframe>";
  echo "</div>";
  echo "</td></tr>";
  echo "</table>";
fieldsetFooter();

echo "<br>";
echo "<br>";
?>

<?php include( "htmlfooter.inc.php" );?>
