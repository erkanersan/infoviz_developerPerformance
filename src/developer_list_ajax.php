<?php include( "htmlheader.inc.php" );?>

<?php
if (isset($_GET["page"])){ $_page = $_GET["page"]; }else{ $_page = '1'; }
if ( isset( $_GET["_query"] ) ) { 
	$_query = $_GET["_query"];
	if ( $_query == "undefined" ){ $_query = "*"; }
}

// for Ajax
echo "<script language='JavaScript1.2' type='text/javascript' src='developer_list_ajax.js'> </script>\n";
echo "<body onload='sorgu(ajax_form._query.value,\"$_page\");'>\n";

echo "<br>";
fieldsetHeader("Developer Overview ");
  echo "<table><tr><td  valign='top' width=\"10%\">";
  fieldsetHeader2("DEVELOPER CONTRIBUTIONS ");
  echo "<iframe class=\"iframe-gray\" src=\"iframe_bubblechart-top1000committers.html\" frameborder=0 width=\"340\" height=\"320\" scrolling=\"no\"></iframe>";
  echo "<button id=\"button-bubblechart-commits\" style=\"font-weight: bold;\" class='button-small-styled' onclick=''>Commits</button>";
  echo "</td>";
  fieldsetFooter();
  echo "<td  valign='top' width=\"90%\">";
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="cart-compare.js"></script>

<?php
$link = dbconnect();

if ( $_page != 'singlepage' ){ $_ajax_page = 1; }else{ $_ajax_page = 'singlepage'; }

    fieldsetHeader2("DEVELOPER SEARCH ");
    // ----- Search - Begin
	echo "\n<table class='aramaAlan' border=0 cellpadding=3 cellspacing=0 >";
	echo "\n\t<tbody><tr><td class='normal-text' valign='middle'>";
	echo "\n\t<form valign='middle' class='aramaForm' method = 'GET' name='ajax_form'>";
	echo "\n\tSearch: <input class='form-metin' type=text name=_query id='input_query' size=40 value='$_query' autocomplete='off' onload=\"sorgu(this.valuei,'$_ajax_page');\" onKeyUp=\"sorgu(this.value,'$_ajax_page');\">\n";
	echo "\n</form>";
	echo "\n</td>";
	if (count($_SESSION['cartdev'])) {
		echo "<td><button id='buttonclear'   onclick='clearCartDevelopers()'>CLEAR CART</button></td>";
	} else { 
		echo "<td><button style='display: none' id='buttonclear'   onclick='clearCartDevelopers()'>CLEAR CART</button></td>";
	}
	if (count($_SESSION['cartdev']) > 1) {
                echo "<td><button id='buttoncompare' onclick='compareDevelopers()'>COMPARE (" . count($_SESSION['cartdev']) . ")</button></td>";
        } else {
                echo "<td><button style='display: none' id='buttoncompare' onclick='compareDevelopers()'>COMPARE (" . count($_SESSION['cartdev']) . ")</button></td>";
        }
	echo "</tr></tbody>";
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

