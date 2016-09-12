<?php include( "infovis.inc.php" );?>

<?php
session_start();
// for get all record at first initilization
if ( isset( $_GET["_query"] ) ) {
	$_query = $_GET["_query"];
	if ( $_query == "undefined" ){ $_query = "*"; }
}else{
	$_query = "*";
}

if (isset($_GET["page"])){
	$_page = $_GET["page"];
	if ( $_page == "undefined" ){ $_page = "*"; }
;
}else {
	$_page = 1;
}
if ( $_page != 'singlepage' ){
	$offset = $limit * ( $_page - 1 );
}
//echo " page:" . $_page;

echo "<div id='page-header'>";
	//echo " ";
echo "</div>";

$limit = 10;
$offset = $limit * ( $_page - 1 );

$bulunan=0;
$index=0;

// numOfRecord
$query = "select count(*) as numberofrecords from ( select count(*) as num from users, commits where 1=1 and users.id=commits.committer_id and name!='' and type='USR'";
if ( $_query != "*" and $_query != ""){
//if ( $_query != "*" ){
        	$query .= " and users.name LIKE '%" . $_query . "%'";
}
$query .= " GROUP BY users.id) t";

$link = dbconnect();
$result = mysqli_query($link, $query );
while ( $row = mysqli_fetch_assoc($result) ) {
  $numOfRecord = $row["numberofrecords"];
}

// USERS
$query = "select users.id as id, users.login, users.name, count(c.committer_id) as countc from users inner join commits c on users.id = c.committer_id where 1=1 and users.name!='' and users.type='USR'";
if ( $_query != "*" and $_query != ""){
//if ( $_query != "*" ){
                $query .= " and users.name LIKE '%" . $_query . "%'";
}

$query .= " GROUP BY id, users.login, users.name ORDER BY countc desc";

if ( $numOfRecord > $limit ){
	if ( $_page != 'singlepage' ){
		$query .= "  limit $offset, $limit";
		$index = $offset;
	}
	$toplam_sayfa = ceil( $numOfRecord / $limit );
}else{
	$toplam_sayfa = 1;
	$_page = 'singlepage';
}

$result = mysqli_query($link, $query );
//echo $query;

if (mysqli_num_rows($result) > 0) {
//$numOfRecord = $numOfRecord + mysqli_num_rows($result);

echo "<table class='searchResultList' width='90%' cellspacing=3 border=0 cellpadding=1>\n";

// List Headers - Begin
echo "\t<tr class='searchResultHeader'>\n";
echo "\t\t<th nowrap>&nbsp;" . "#" . "&nbsp;</th>\n";
echo "\t\t<th width='30'nowrap>&nbsp;" . "CMP" . "&nbsp;</th>\n";
echo "\t\t<th nowrap>&nbsp;" . "Developer Login" . "&nbsp;</th>\n";
echo "\t\t<th width='100%' nowrap>&nbsp;" . "Developer Name" . "&nbsp;</th>\n";
echo "\t</tr>\n";
// List Headers - End

// List - Begin
while ( $row = mysqli_fetch_assoc($result) ) {

        // to use different color each row
        if ($flag == "on" ) {$trbgclr = "#f5f5f5"; $flag="off";} else{$trbgclr = "#e9e9e9"; $flag="on";}
	echo "<tr " . $_class_ . " bgcolor=\"$trbgclr\">"; // setting row color

        // Index no
        echo "<td nowrap>&nbsp;".++$index."&nbsp;</td>";

	// Shopping Cart
        $cartnum = count($_SESSION['cartdev'])+1;
        $devid = $row["id"];
        if (in_array(intval($devid), $_SESSION['cartdev'])) {
                displayList("<a id='anchor$devid' href='javascript:removeDeveloperFromCartDevelopers(this, $devid, $cartnum)'><img id='img$devid' src='images/cart-added.gif' vertical-align='middle'></a>" );
        } else {
                displayList("<a id='anchor$devid' href='javascript:addDeveloperToCartDevelopers(this, $devid, $cartnum)'><img id='img$devid' src='images/cart-removed.gif' vertical-align='middle'></a>" );
        }

	// Login name
	$alt_msg = "Please click to get detailed information about " . $row["login"];
	$alt_msg = " title=\"" . $alt_msg . "\" alt=\"" . $alt_msg . "\" ";
	if ( strlen( $row["login"] ) > 18 ) {
                 $login = substr( $row["login"], 0, 15 ) . "...";
        }else{
                 $login = $row["login"];
        }
	$loginname = "<a $alt_msg href='developer_detail.php?id=" . $row["id"] . "'>" . $login . "</a>";
	displayList( $loginname );

        // name
	if ( strlen( $row["name"] ) > 60 ) {
		 $name = substr( $row["name"], 0, 59 ) . ".....";
	}else{ 
		 $description = $row["name"]; 
	}
	if ( $_query != ""){
                $description = highlight_yellow( $description, $_query);
        }
        displayList( $description );

}
// List - End


echo "</table>";
}
displayTotalCount( "$_query", $numOfRecord );

// Page numbers - Begin
echo	"<div id='sayfa-baglari'>";

	// Limiting number of page number box as 15
	$box_limit = 7;
	if ( $_page == 'singlepage' ){ 
		$ilk_sayfa = 1;
		$son_sayfa = $box_limit;
		if ( $son_sayfa > $toplam_sayfa ) {
			$son_sayfa = $toplam_sayfa;
		}
	}else{
		if ( $_page < $box_limit-1){
			$ilk_sayfa = 1;
			$son_sayfa = $box_limit;
			if ( $son_sayfa > $toplam_sayfa ) {
				$son_sayfa = $toplam_sayfa;
			}
		}else{
			$ilk_sayfa = $_page - (($box_limit-1)/2);
			$son_sayfa = $_page + (($box_limit-1)/2);
			if ( $son_sayfa > $toplam_sayfa ) {
				$ilk_sayfa = $toplam_sayfa - ($box_limit-1);
				$son_sayfa = $toplam_sayfa;
			}
		}

	}


	if ( $_query != "" ){ $_query_aktar = "&_query=" . $_query; } // adding query information

	if ( $_page == 'singlepage' ){
		if ( $toplam_sayfa > 1 ){ echo "All records in one page&nbsp;"; }
	}else{
		echo "Page $_page/$toplam_sayfa&nbsp;&nbsp;";
		if ( $ilk_sayfa != 1 ){
			echo " <a href='developer_list_ajax.php?page=1$_query_aktar' class='sayfanumarasi_bagi'>1</a>";
			echo "&nbsp;-";
		}
		for ($i = $ilk_sayfa; $i <= $son_sayfa; $i++) {
			if ( $i == $_page ){
				echo " <span class='bag_yok'>$i</span>";
			}else{
				echo " <a href='developer_list_ajax.php?page=$i$_query_aktar' class='sayfanumarasi_bagi'>$i</a>";
			}
		}
		if ( $son_sayfa != $toplam_sayfa ){
			echo "&nbsp;-";
			echo " <a href='developer_list_ajax.php?page=$toplam_sayfa$_query_aktar' class='sayfanumarasi_bagi'>$toplam_sayfa</a>";			
		}
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	}

	if ( $_page == 'singlepage' ){
		if ( $toplam_sayfa > 1 ){
			echo "&nbsp;-&nbsp;";
			echo "&nbsp;<a href='developer_list_ajax.php?page=1$_query_aktar' class='sayfanumarasi_bagi'>Show in multiple pages</a>";
		}
	}else{
		echo "&nbsp;<a href='developer_list_ajax.php?page=singlepage$_query_aktar' class='sayfanumarasi_bagi'>Show in one page</a>";
	}
echo	"</div>";
// Page numbers - End

?>

