<?php include( "htmlheader.inc.php" );?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="cart-compare.js"></script>
<?php include( "visual_bullet-charts.inc.php" );?>
<?php include( "visual_horizontal-bar-charts.inc.php" );?>
<?php

function username($userid) {
$link = dbconnect();

// Number Information
$query  = "select *";
$query .= " from users where 1=1";
$query .= " and users.id='$userid'";

$result = mysqli_query($link, $query );

while ( $row = mysqli_fetch_assoc($result) ) {
        $login= $row["login"];
        $name = $row["name"];
        $location = $row["location"];
}

return $name;
}

$id=$_GET["id"];
if(!$id) {$id=0;}

$_type=$_GET["type"];
if(!$_type) {$_type="commits";}

$_startdate=$_GET["startdate"];
if(!$_startdate) {$_startdate=2013;}
?>

<script>
 function myFunction(id, type, startdate) {
        window.parent.location.href="/infovis/developer_compare.php?id="+id+"&type="+type+"&startdate="+startdate;
 }
</script>

<?php
echo "<br>";
fieldsetHeader("Developers Comparison: ");

echo "<table><tr><td>";
$array_comparition_options = array("List - Developers in the Cart",
				   "Compare - Developers' performance  (Bullet chart)", 
				   "Compare - Developers' performance  (Timeline)",
				   "Compare - Developers' performance  (Dot chart)",
                                   "Compare - Developers' performance  (Calendar view)",
                                   "Compare - Developers' Success Rate (Bar chart)"
			      );
echo "<select class='select-styled' onchange='myFunction(this.value, \"$_type\", $_startdate)'>";
for ($i=0; $i < count($array_comparition_options); $i++){
  if ( $i == $id ) $sel = " selected "; else $sel = " ";
  echo "\t\t\t<option class='option-styled' value=\"$i\" $sel>" . $array_comparition_options[$i] . "</option>\n";
}
echo "</select>";
echo "</td><td>";

$array_type_options = array("Commits",
                            "Pull Requests",
                            "Issues",
                            "Comments"
                       );
echo "<select class='select-styled' onchange='myFunction($id, this.value, $_startdate)'>";
  for ($i=0; $i < count($array_type_options); $i++){
    $select_value = str_replace(" ", "", strtolower($array_type_options[$i]));
    if ( $select_value == $_type ) $sel = " selected "; else $sel = " ";
    echo "\t\t\t<option class='option-styled' value=\"". $select_value ."\" $sel>" . $array_type_options[$i] . "</option>\n";
  }
echo "</select>";
echo "</td><td>";

echo "<button class='button-refresh-styled' onClick='history.go(0)'>REFRESH</button>";
echo "</td></tr></table>";
echo "<br>";

switch ($id) {
    case 0:
        listDevelopers(); //List - Developers in the Cart
        break;
    case 1:
        showBulletCharts($id, $_type, $_startdate);//Bullet charts
        break;
    case 2:
	showTimelineType02Charts($id, $_type, $_startdate);//Timeline charts
        break;
    case 3:
	showDotCharts($id, $_type, $_startdate);//Dot charts
        break;
    case 4:
	calendarDevelopersByCommits($id, $_type, $_startdate);//Calendar by commits
        break;
    case 5:
	listDevelopersBySuccessRates();//Success Rate
        break;
}

fieldsetFooter();
?>

<?php
function listDevelopers() {
  fieldsetHeader2("THE DEVELOPERS LIST in CART (" . count($_SESSION['cartdev']) . ")");
  if (count($_SESSION['cartdev']) < 1) {
    echo "<br>There is not any developer on the list. Please";
    echo " <a href='developer_list_ajax.php'>add</a> ";
    echo " at least two developers to the list to compare them.";
  }
  echo "<section class=\"section-styled\">";
  foreach ($_SESSION['cartdev'] as $userid) {
    $username="<a href='/infovis/developer_detail.php?id=$userid'>" . username($userid). "</a>";
    echo "<div class=\"sidebyside-gray\">";
    echo "<table><tr>";
    echo "<td>" . cartstatus($userid) . "</td>";
    echo "<td height=\"30\" >$username</td>";
    echo "</tr></table>";
    echo "</div>";
  }
  echo "</section>";
  fieldsetFooter();
}

function showBulletCharts($caseid, $type, $startdate) {
  fieldsetHeader2("THE DEVELOPERS LIST by THE NUMBER of THEIR ".strtoupper($type)." to PROJECTS");
    switch ($type) {
      case "commits":
	bulletCharts("commits",0); //Compare cart list.
        break;
      case "pullrequests":
	bulletCharts("pullrequests",0); //Compare cart list.
        break;
      case "issues":
	bulletCharts("issues",0); //Compare cart list.
        break;
      case "comments":
	bulletCharts("comments",0); //Compare cart list.
        break;
   }
  fieldsetFooter();
}

function listDevelopersBySuccessRates() {
  fieldsetHeader2("THE DEVELOPERS LIST by THE SUCCESS RATE");
    horizontalBarCharts(0);
  fieldsetFooter();
}

function showTimelineType02Charts($caseid, $type, $startdate) {
  fieldsetHeader2("THE TIMELINE VIEW by THE NUMBER of THE DEVELOPERS' ".strtoupper($type)." to PROJECTS");
    foreach ($_SESSION['cartdev'] as $userid) {
      $username="<a href='/infovis/developer_detail.php?id=$userid'>" . username($userid). "</a>";
      echo "<table><tr><td class=\"frame-gray\" width=\"200\" height=\"130\">$username";

      echo "<br>" . cartstatus($userid);

      echo "</td><td class=\"frame-gray\">";
      echo "<iframe class=\"iframe-gray\" src=\"iframe_timeline-focus-content-type02-user.php?type=$type&id=$userid\" frameborder=1 width=\"1000\" height=\"130\" scrolling=\"no\"></iframe>";
      echo "</td></tr></table>";
    }
  fieldsetFooter();
}


function showDotCharts($caseid, $type, $startdate) {
  fieldsetHeader2("THE DOT CHART VIEW by THE DEVELOPER'S ".strtoupper($type)." to PROJECTS");
    echo "<img src='images/legend-dotschart.jpg'>";
    foreach ($_SESSION['cartdev'] as $userid) {
      $username="<a href='/infovis/developer_detail.php?id=$userid'>" . username($userid). "</a>";
      echo "<table><tr><td class=\"frame-gray\" width=\"200\" height=\"130\">$username";
      echo "<br>" . cartstatus($userid);
      echo "</td><td class=\"frame-gray\">";
      echo "<iframe class=\"iframe-gray\" src=\"visual_gRaphael-dot-chart.php?type=$type&id=$userid\" frameborder=1 width=\"650\" height=\"300\" scrolling=\"no\"></iframe>";
      echo "</td></tr></table>";
    }
  fieldsetFooter();
}


function calendarDevelopersByCommits($caseid, $type, $startdate) {
  fieldsetHeader2("THE CALENDAR VIEW by THE NUMBER of THE DEVELOPER'S ".strtoupper($type)." to PROJECTS");

    echo "<table><tr><td width=\"210\">";
    $array_year_options = array("2013",
                                "2012",
                                "2011",
                                "2010",
                                "2009"
                          );
    echo "<select class='select-styled' onchange='myFunction($caseid, \"$type\", this.value)'>";
    for ($i=0; $i < count($array_year_options); $i++){
      if ( $array_year_options[$i]  == $startdate ) $sel = " selected "; else $sel = " ";
      echo "\t\t\t<option class='option-styled' value=\"". $array_year_options[$i] ."\" $sel>" . $array_year_options[$i] . "</option>\n";
    }
    echo "</select>";
    echo "</td><td>";
    echo "<img src='images/legend-calendar.jpg'>";
    echo "</td></tr></table>";

    foreach ($_SESSION['cartdev'] as $userid) {
      $username="<a href='/infovis/developer_detail.php?id=$userid'>" . username($userid). "</a>";
      echo "<table><tr><td class=\"frame-gray\" width=\"200\" height=\"130\">$username";
      echo "<br>" . cartstatus($userid);
      echo "</td><td class=\"frame-gray\">";
      echo "<iframe class=\"iframe-gray\" src=\"iframe_calendar-view-commits_by_a_user.php?type=$type&id=$userid&startdate=$startdate\" frameborder=1 width=\"1000\" height=\"130\" scrolling=\"no\"></iframe>";
      echo "</td></tr></table>";
    }
  fieldsetFooter();
}

?>


<?php include( "htmlfooter.inc.php" );?>
