<?php include( "infovis.inc.php" );?>
<?php include( "cart-compare.inc.php" );?>

<html>
<head>
	<title>CSC 511 - Final Project - Visualizing Performance of Software Developers on GitHub</title>
	<style type="text/css"> 
		body{background-repeat:no-repeat; background-color:#ffffff;}
	</style>
	<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="css_infovis.css">
	<link rel="stylesheet" type="text/css" href="css_infovis-font.css">
</head>

<script src="lib/journals/jquery.js"></script>
<script type="text/javascript">
        $(function(){
                $('legend').click(function(){
                        $(this).parent().find('.fieldsetcontent').slideToggle("fast");
                });
        });
</script>

<body>

<?php
/*	echo "<div id='cart-info'>";
	echo "Compare Cart: "; echo var_dump($cartdev);
	echo "</div>";
*/
?>

<div class='site-header'>
	Visualizing Performance of Software Developers on GitHub</b>
</div>
<div class='menu-top'>
	<table cellpadding="0" cellspacing="0" border="0" width="99%">
		<tr >
                        <td width="50" nowrap>&nbsp;&nbsp;<a href="project_list_ajax.php">PROJECTS</a>&nbsp;</td>
			<td width="10" nowrap>&nbsp;&nbsp;|&nbsp;</td>
			<td width="60" nowrap>&nbsp;<a href="developer_list_ajax.php">DEVELOPERS</a>&nbsp;</td>
			<td width="10" nowrap>&nbsp;&nbsp;</td>
			<td align="right" nowrap><a href="developer_compare.php"><small>COMPARE DEVELOPERS</small></a>&nbsp;</td>
		</tr>
	</table>
</div>
