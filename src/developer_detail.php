<?php include( "htmlheader.inc.php" );?>
<?php include( "visual_bullet-charts.inc.php" );?>
<?php include( "visual_calendar-view.inc.php" );?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="cart-compare.js"></script>
<?php
$_userid = $_GET["id"];

$_type=$_GET["type"];
if(!$_type) {$_type="commits";}


$link = dbconnect();

// Number Information
$query  = "select *";
$query .= " from users where 1=1";
$query .= " and users.id='$_userid'";

$result = mysqli_query($link, $query );

while ( $row = mysqli_fetch_assoc($result) ) {
	$login= $row["login"];
	$name = $row["name"];
	$location = $row["location"];
}

?>
<script>
 var iframefile = "iframe_timeline-focus-content-user.php";

 function changeTimelineToCommits(userid) {
   document.getElementById("timeline").src = iframefile+"?type=commits&id="+userid;
   document.getElementById("button-timeline-commits").style.fontWeight = "bold";
   document.getElementById("button-timeline-pullrequests").style.fontWeight = "normal";
   document.getElementById("button-timeline-issues").style.fontWeight = "normal";
   document.getElementById("button-timeline-comments").style.fontWeight = "normal";
 }

 function changeTimelineToPullRequests(userid) {
   document.getElementById("timeline").src = iframefile+"?type=pullrequests&id="+userid;
   document.getElementById("button-timeline-commits").style.fontWeight = "normal";
   document.getElementById("button-timeline-pullrequests").style.fontWeight = "bold";
   document.getElementById("button-timeline-issues").style.fontWeight = "normal";
   document.getElementById("button-timeline-comments").style.fontWeight = "normal";
 }

 function changeTimelineToIssues(userid) {
   document.getElementById("timeline").src = iframefile+"?type=issues&id="+userid;
   document.getElementById("button-timeline-commits").style.fontWeight = "normal";
   document.getElementById("button-timeline-pullrequests").style.fontWeight = "normal";
   document.getElementById("button-timeline-issues").style.fontWeight = "bold";
   document.getElementById("button-timeline-comments").style.fontWeight = "normal";
 }

 function changeTimelineToComments(userid) {
   document.getElementById("timeline").src = iframefile+"?type=comments&id="+userid;
   document.getElementById("button-timeline-commits").style.fontWeight = "normal";
   document.getElementById("button-timeline-pullrequests").style.fontWeight = "normal";
   document.getElementById("button-timeline-issues").style.fontWeight = "normal";
   document.getElementById("button-timeline-comments").style.fontWeight = "bold";
 }
</script>

<?php
echo "<br>";
fieldsetHeaderHideable("Developer : " . $name . "  (". $login .") " );
  echo "<table class='formTable' border=0>";
  echo "<tr><td>";
    fieldsetHeader3("Contributions Timeline ");
      echo "<table class='formTable' border=0>";
      $backButton = "<img src='images/surfing-back.png' onClick='history.go(-1)'>";
      displayText( "Compare", cartstatus($_userid). " " . $backButton  );
      echo "</table>";
      echo "<iframe id=\"timeline\" class=\"iframe-gray\" src=\"iframe_timeline-focus-content-user.php?type=commits&id=$_userid\" frameborder=1 width=\"950\" height=\"220\" scrolling=\"no\"></iframe>";
      echo "<br>";
      echo "<button id=\"button-timeline-commits\" style=\"font-weight: bold;\" class='button-small-styled' onclick='javascript:changeTimelineToCommits($_userid)'>Commits</button>";
      echo "<button id=\"button-timeline-pullrequests\" class='button-small-styled' onclick='javascript:changeTimelineToPullRequests($_userid)'>Pull Requests</button>";
      echo "<button id=\"button-timeline-issues\" class='button-small-styled' onclick='javascript:changeTimelineToIssues($_userid)'>Issues</button>";
      echo "<button id=\"button-timeline-comments\" class='button-small-styled' onclick='javascript:changeTimelineToComments($_userid)'>Comments</button>";
    fieldsetFooter();

  echo "</td><td>";
    fieldsetHeader3("Projects ");
     //echo "<iframe class=\"iframe-gray\" src=\"iframe_treemap-user-by_projects.php?id=$_userid\" frameborder=1 width=\"300\" height=\"264\" scrolling=\"no\"></iframe>";
     echo "<iframe class=\"iframe-gray\" src=\"iframe_bubblechart-number_of_commits_of_projects_by_user.php?type=commits&id=$_userid\" frameborder=0 width=\"300\" height=\"264\" scrolling=\"no\"></iframe>";
    // echo "<iframe class=\"iframe-gray\" src=\"iframe_piechart-number_of_commits_of_projects_by_user.php?type=commits&id=$_userid\" frameborder=0 width=\"300\" height=\"264\" scrolling=\"no\"></iframe>";
    fieldsetFooter();

  echo "</td></tr></table>";
fieldsetFooter();

echo "<br>";
?>

<script>
 function myFunction(id, type) {
        window.parent.location.href="/infovis/developer_detail.php?id="+id+"&type="+type;
 }
</script>

<?php
fieldsetHeaderHideable("Working Patterns" );
  $array_type_options = array("Commits",
                              "Pull Requests",
                              "Issues",
                              "Comments"
                         );
  echo "<select class='select-styled' onchange='myFunction($_userid, this.value)'>";
    for ($i=0; $i < count($array_type_options); $i++){
      $select_value = str_replace(" ", "", strtolower($array_type_options[$i]));
      if ( $select_value == $_type ) $sel = " selected "; else $sel = " ";
      echo "\t\t\t<option class='option-styled' value=\"". $select_value ."\" $sel>" . $array_type_options[$i] . "</option>\n";
    }
  echo "</select><br><br>";

  echo "<table class='formTable' border=0>";
  echo "<tr><td>";
    fieldsetHeader3("Working Pattern Dot Chart");
      echo "<img src='images/legend-dotschart.jpg'><br>";
      echo "<iframe class=\"iframe-gray\" src=\"visual_gRaphael-dot-chart.php?type=$_type&id=$_userid\" frameborder=1 width=\"650\" height=\"562\" scrolling=\"no\"></iframe>";
    fieldsetFooter();
  echo "</td><td>";
    fieldsetHeader3("Activity Calendar");
      echo "<img src='images/legend-calendar.jpg'><br>";
      echo "<iframe class=\"iframe-gray\" src=\"iframe_calendar-view-commits_by_a_user.php?type=$_type&cellSize=10&id=$_userid&startdate=2013\" frameborder=1 width=\"600\" height=\"130\" scrolling=\"no\"></iframe>";
      echo "<iframe class=\"iframe-gray\" src=\"iframe_calendar-view-commits_by_a_user.php?type=$_type&id=$_userid&cellSize=10&startdate=2012\" frameborder=1 width=\"600\" height=\"130\" scrolling=\"no\"></iframe>";
      echo "<iframe class=\"iframe-gray\" src=\"iframe_calendar-view-commits_by_a_user.php?type=$_type&id=$_userid&cellSize=10&startdate=2011\" frameborder=1 width=\"600\" height=\"130\" scrolling=\"no\"></iframe>";
      echo "<iframe class=\"iframe-gray\" src=\"iframe_calendar-view-commits_by_a_user.php?type=$_type&id=$_userid&cellSize=10&startdate=2010\" frameborder=1 width=\"600\" height=\"130\" scrolling=\"no\"></iframe>";
      echo "<iframe class=\"iframe-gray\" src=\"iframe_calendar-view-commits_by_a_user.php?type=$_type&id=$_userid&cellSize=10&startdate=2009\" frameborder=1 width=\"600\" height=\"130\" scrolling=\"no\"></iframe>";
    fieldsetFooter();
  echo "</td></tr></table>";

fieldsetFooter();

echo "<br>";
?>

<?php include( "htmlfooter.inc.php" );?>
