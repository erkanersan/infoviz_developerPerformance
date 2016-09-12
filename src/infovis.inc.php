<?php
mb_internal_encoding("UTF-8");

$_host = "localhost";
$_db = "msr14";
$_user = "msr14";
$_pass = "msr14";
$_trMonthName = array ("-","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$_shorttrMonthName = array ("-","Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
$_special_sort_word = "0> ";

function str_normalize ($string) {
    $table = array(
        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
    );

    return strtr($string, $table);
}

function substr_utf8($str,$start)
{
	preg_match_all("/./su", $str, $ar);

	if(func_num_args() >= 3) { $end = func_get_arg(2); return join("",array_slice($ar[0],$start,$end));
	} else { return join("",array_slice($ar[0],$start)); }
}

function strtolower_tr($string){
	$low=array( "Ü"=>"ü", "Ö"=>"ö", "Ğ"=>"ğ", "Ş"=>"ş", "Ç"=>"ç", "İ"=>"i", "I"=>"ı");
	return strtolower( strtr( $string, $low ) );
}

function displayText($title, $content){
	global $_special_sort_word;
        $content = str_replace($_special_sort_word,"",$content );
	echo "<tr bgcolor=\"$bgcolor\"><td valign=\"top\"><b><font color=\"#030A87\">$title</font></b>&nbsp;</td><td>$content </td></tr>";
}

function dbconnect(){
	global $_host, $_db, $_user, $_pass;
	$_connection = mysqli_connect($_host, $_user, $_pass, $_db) or die ("<br>Connection failed : " . mysql_error());
	return $_connection;
}

function dbinsert($link, $query){
	$result = pg_exec($query) or die("Query failed !!!");
	if ( $result == FALSE ) { echo $query; $return_value = 1;} else $return_value = 0;
	return $return_value;
}

function displayList($content){
	global $_special_sort_word;
	$content = str_replace($_special_sort_word,"",$content );
	//$bgcolor = "#DFF3F6";
	echo "<td nowrap>&nbsp;$content&nbsp;&nbsp;&nbsp;</td>";
}

function displayList_wrap($content){
	global $_special_sort_word;
	$content = str_replace($_special_sort_word,"",$content );
	//$bgcolor = "#DFF3F6";
	echo "<td>&nbsp;$content&nbsp;&nbsp;&nbsp;</td>";
}

function str_Ereplace($search, $replace, $subject) {
  if (is_array($search)) {
   foreach ($search as $word) {
   $words[] = "/".$word."/i";
   }
  }
  else {
   $words = "/".$search."/i";
  }
  return preg_replace($words, $replace, $subject);
}


function highlight_yellow($content, $_query){
	if ( $_query == "*" ) {
		 $content = $content;
	}	else{
		 $content = str_Ereplace($_query,"<span class=\"highlight-yellow\">\\0</span>",$content );
	}
	return $content;
}


function displayWarning($content){
  echo "<div class='warning-message'>";
  echo $content;
	echo "</div>";				 									
}

function fieldsetHeader($label){
  echo "<FIELDSET class='fieldset'>\n";
  echo "<LEGEND ALIGN=LEFT>\n";
	echo "&nbsp;&nbsp;$label&nbsp;&nbsp;\n";
	echo "</LEGEND>\n";
  echo "<div class=\"nothideable\">";
}

function fieldsetHeader2($label){
  echo "<FIELDSET class='fieldset2'>\n";
  echo "<LEGEND class='legend2' ALIGN=LEFT>\n";
        echo "&nbsp;&nbsp;$label&nbsp;&nbsp;\n";
        echo "</LEGEND>\n";
  echo "<div class=\"nothideable\">";
}

function fieldsetHeader3($label){
  echo "<FIELDSET class='fieldset3'>\n";
  echo "<LEGEND class='legend2' ALIGN=LEFT>\n";
        echo "&nbsp;&nbsp;$label&nbsp;&nbsp;\n";
        echo "</LEGEND>\n";
  echo "<div class=\"nothideable\">";
}

function fieldsetHeaderHideable($label){
  echo "<FIELDSET class='fieldset'>\n";
  echo "<LEGEND ALIGN=LEFT>\n";
        echo "&nbsp;&nbsp;";
	echo "<img class='fieldsetimage' align='middle' src='images/hide-blue.png'>";
	echo "&nbsp;&nbsp;$label&nbsp;&nbsp;\n";
        echo "</LEGEND>\n";
  echo "<div class=\"fieldsetcontent\">";
}

function fieldsetHeaderHideable2($label){
  echo "<FIELDSET class='fieldset2'>\n";
  echo "<LEGEND class='legend2' ALIGN=LEFT>\n";
        echo "&nbsp;&nbsp;";
        echo "<img class='fieldsetimage' align='middle' src='images/hide-blue.png'>";
        echo "&nbsp;&nbsp;$label&nbsp;&nbsp;\n";
        echo "</LEGEND>\n";
  echo "<div class=\"fieldsetcontent\">";
}

function fieldsetFooter(){
  echo "</div>";
  echo "</FIELDSET>\n";
}

function displayTotalCount($content, $_pg_numrows){
  if ($content!="") {$content=" for <i>" . $content. "</i>";}
//  if ($_pg_numrows != 0 ) {
	echo "<div class='totalRecordCount'>";
	echo "$_pg_numrows results found $content";
	echo "</div>";
//  }
}

function textbox($fname, $label, $size, $content, $table, $valuetype){
	if ( $table == "on" ) echo "\n\t<td class=\"form-text\">\n";
	echo "\t\t<b>" . $label . "</b>\n";
	if ( $table == "on" ) echo "\t</td>\n\t<td>\n";
	if ($valuetype == "numbersonly" ) $vtype=" onKeyPress=\"return numbersonly(event)\" ";
	if ($valuetype == "ajax_sorgu" ) $vtype=" autocomplete=\"off\" onload='sorgu(this.value);' onKeyUp='sorgu(this.value);' ";
	if ($valuetype == "ajax_suggest" ) $vtype=" id='suggest_$fname' ";
	echo "\t\t<input class=\"form-text\" type=text name=$fname size=$size value=\"$content\" $vtype>\n";
	if ( $table == "on" ) echo "\t</td>\n";
}

function hiddentextbox($fname, $label, $size, $content, $table){
if ( $table == "on" ) echo "<tr>\n\t<td>\n";
echo "\t\t<b>" . "</b>\n";
if ( $table == "on" ) echo "\t</td>\n\t<td>\n";
echo "\t\t<input type=hidden name=$fname size=$size value=\"$content\">\n";
if ( $table == "on" ) echo "\t</td>\n</tr>\n";
}

function button($fname, $value, $type){
echo "\t\t<input class='form-text' type=$type name=\"$fname\" value=\"$value\">\n";
}

function checkbox( $fname, $label, $value, $sel ){
echo "\t\t<input class='form-metin' align=\"middle\" type=\"checkbox\" name=\"$fname\" value=\"". $value . "\" $sel>$label\n";
}

//sadece YYYY-AA-GG biçiminde tarih girilebilecek text alanı basar
function textbox_dateValidate ($tdname, $name, $size, $value, $comment)
{
	echo "<tr><td><b>$tdname</b></td><td><input type=\"text\" name=\"$name\" value=\"$value\" SIZE=\"10\" MAXLENGTH=\"10\" onBlur=\"dateValidate(this.value)\">&nbsp;$comment</td>\n";
}

// function searchRecord($label, $link, $sta)
// $label: Başlık, $link: formun nereye yönlendirileceği, $statu:iptal edilmiş kayıtları göster
function searchRecord($label, $link, $sta, $_status){
	echo "<table class='aramaAlan' border=0 cellpadding=3 cellspacing=0 width='100%'>";
	echo "<tbody><tr><td valign='middle'>";	
	echo "<form class='aramaForm' action = \"$link\"  method = \"POST\">";
  textbox("_query", $label, "40", "", "off", "mixed");
	button("okey", "Gönder", "submit");
	if ( $sta == "on" ){
		 checkbox("_status", "<i>İptal edilmiş kayıtları da göster</i>", "IPTAL", $_status);
	}	
	echo "</form>";
	echo "</td></tr></tbody>";
	echo "</table>";
}

//gonderilen query den kaç kayıt geldiğini gönderir.
function recordCount($query){
	$link = dbconnect();
	$result = mysqli_query($link, $query );
	$num_rows = mysqli_num_rows($result);	
	mysqli_close($link);
	return $num_rows;
}

?>
