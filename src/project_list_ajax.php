<?php include( "htmlheader.inc.php" );?>
<?php include( "visual_calendar-view.inc.php" );?>

<?php
if (isset($_GET["page"])){ $_page = $_GET["page"]; }else{ $_page = '1'; }
if ( isset( $_GET["_query"] ) ) { 
	$_query = $_GET["_query"];
	if ( $_query == "undefined" ){ $_query = "*"; }
}

// for Ajax
echo "<script language='JavaScript1.2' type='text/javascript' src='project_list_ajax.js'> </script>\n";
echo "<body onload='sorgu(ajax_form._query.value,\"$_page\");'>\n";
?>

<script>
 function changeTreemapToForks() {
   document.getElementById("treemap").src = "iframe_treemap-projects.php?type=forks";
   document.getElementById("buttontreemapforks").style.fontWeight = "bold";
   document.getElementById("buttontreemapcommits").style.fontWeight = "normal";
   document.getElementById("buttontreemapdevelopers").style.fontWeight = "normal";
 }
 function changeTreemapToCommits() {
   document.getElementById("treemap").src = "iframe_treemap-projects.php?type=commits";
   document.getElementById("buttontreemapforks").style.fontWeight = "normal";
   document.getElementById("buttontreemapcommits").style.fontWeight = "bold";
   document.getElementById("buttontreemapdevelopers").style.fontWeight = "normal";
 }
 function changeTreemapToDevelopers() {
   document.getElementById("treemap").src = "iframe_treemap-projects.php?type=developers";
   document.getElementById("buttontreemapforks").style.fontWeight = "normal";
   document.getElementById("buttontreemapcommits").style.fontWeight = "normal";
   document.getElementById("buttontreemapdevelopers").style.fontWeight = "bold";
 }
</script>

<?php
echo "<br>";
fieldsetHeader("Projects Overview ");
  echo "<table><tr><td  valign='top' width=\"180\">";

    fieldsetHeader2("PROJECT LANGUAGES");
      echo "<iframe class=\"iframe-gray\" src=\"iframe_wordcloud-projects-language.php\" frameborder=0 width=\"180\" height=\"320\" scrolling=\"no\"></iframe>";
    fieldsetFooter();

  echo "</td>";
  echo "<td  valign='top' width=\"50%\">";


  fieldsetHeader2("PROJECTS ");
  echo "<iframe id=\"treemap\" class=\"iframe-gray\" src=\"iframe_treemap-projects.php?type=commits\" frameborder=0 width=\"520\" height=\"320\" scrolling=\"no\"></iframe>";
  echo "<br>";
  echo "<button id=\"buttontreemapcommits\" style=\"font-weight: bold;\" class='button-small-styled' onclick='javascript:changeTreemapToCommits()'>Commits</button>";
  echo "<button id=\"buttontreemapforks\" class='button-small-styled' onclick='javascript:changeTreemapToForks()'>Forks</button>";
  echo "<button id=\"buttontreemapdevelopers\" class='button-small-styled' onclick='javascript:changeTreemapToDevelopers()'>Developers</button>";
  fieldsetFooter();
  echo "</td>";
  echo "<td  valign='top' width=\"50%\">";
?>

<?php
$link = dbconnect();

if ( $_page != 'singlepage' ){ $_ajax_page = 1; }else{ $_ajax_page = 'singlepage'; }

    fieldsetHeader2("PROJECT SEARCH ");
    // ----- Search - Begin
	echo "\n<table class='aramaAlan' border=0 cellpadding=3 cellspacing=0 >";
	echo "\n\t<tbody><tr><td class='normal-text' valign='middle'>";
	echo "\n\t<form valign='middle' class='aramaForm' method = 'GET' name='ajax_form'>";
	echo "\n\tSearch: <input class='form-metin' type=text name=_query id='input_query' size=40 value='$_query' autocomplete='off' onload=\"sorgu(this.valuei,'$_ajax_page');\" onKeyUp=\"sorgu(this.value,'$_ajax_page');\">\n";

	echo "\n</form>";
	echo "\n</td></tr></tbody>";
	echo "\n</table>\n";
    // ----- Search - End

    // Liste
    echo "<div id='txtHint'>";
		// Results will be here by Ajax
    echo "</div>";

    fieldsetFooter(); // Project Search
  echo "\n</td></tr>";
  echo "\n</table>\n";

fieldsetFooter(); // Project Overview

mysql_close($link); // close db connection 
?>

<?php include( "htmlfooter.inc.php" );?>

