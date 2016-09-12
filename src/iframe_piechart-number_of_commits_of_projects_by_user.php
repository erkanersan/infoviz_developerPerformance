<?php include("infovis.inc.php");?>
<?php
$_userid = $_GET["id"];
$_type = $_GET["type"];

$query = "select p.id, p.name, p.language, count(c.id) size from users u, projects p, commits c where u.id=c.committer_id and p.id=c.project_id and u.id=$_userid group by p.name order by p.name";

$link = dbconnect();
$result = mysqli_query($link, $query );

$trRows = "";
while($row = mysqli_fetch_assoc($result)) {
    $projectName = $row["name"];
    $size   = intval($row["size"]);
    if ($size<5) {$size=10;}
    $trRows .= "<tr><th scope=\"row\">$projectName</th><td>$size</td></tr>";
}

mysqli_close($link); //close db connection

echo <<<EOL
<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <link rel="stylesheet" href="lib/raphaeljs.com/demo.css" media="screen">
        <script src="lib/raphaeljs.com/raphael.js"></script>
        <script src="lib/raphaeljs.com/jquery.js"></script>
        <script src="lib/raphaeljs.com/pie.js"></script>
        <style media="screen">
            #holder {
                margin: -150px 0 0 -150px;
                width: 300px;
                height: 300px;
            }
        </style>
    </head>
    <body>
        <table style="display: none;">
            <tbody><tr><th scope=\"row\"></th><td>1</td></tr>$trRows</tbody>
        </table>
        <div id="holder" border=1><svg style="overflow: hidden; position: relative;" xmlns="http://www.w3.org/2000/svg" width="200" version="1.1" height="200">
        <desc>Created with Raphaël 2.1.2</desc>
        <defs>
        <linearGradient gradientTransform="matrix(1,0,0,1,0,0)" y2="0" x2="6.123233995736766e-17" y1="1" x1="0" id="090-_ff0000-_ff3f3f"><stop stop-color="#ff0000" offset="0%"></stop><stop stop-color="#ff3f3f" offset="100%"></stop></linearGradient>
        <linearGradient gradientTransform="matrix(1,0,0,1,0,0)" y2="0" x2="6.123233995736766e-17" y1="1" x1="0" id="290-_ff9900-_ffb23f"><stop stop-color="#ff9900" offset="0%"></stop><stop stop-color="#ffb23f" offset="100%"></stop></linearGradient>
        <linearGradient gradientTransform="matrix(1,0,0,1,0,0)" y2="0" x2="6.123233995736766e-17" y1="1" x1="0" id="490-_ccff00-_d8ff3f"><stop stop-color="#ccff00" offset="0%"></stop><stop stop-color="#d8ff3f" offset="100%"></stop></linearGradient>
        <linearGradient gradientTransform="matrix(1,0,0,1,0,0)" y2="0" x2="6.123233995736766e-17" y1="1" x1="0" id="690-_32ff00-_65ff3f"><stop stop-color="#32ff00" offset="0%"></stop><stop stop-color="#65ff3f" offset="100%"></stop></linearGradient>
        <linearGradient gradientTransform="matrix(1,0,0,1,0,0)" y2="0" x2="6.123233995736766e-17" y1="1" x1="0" id="890-_00ff65-_3fff8c"><stop stop-color="#00ff65" offset="0%"></stop><stop stop-color="#3fff8c" offset="100%"></stop></linearGradient>
        <linearGradient gradientTransform="matrix(1,0,0,1,0,0)" y2="0" x2="6.123233995736766e-17" y1="1" x1="0" id="1090-_00ffff-_3fffff"><stop stop-color="#00ffff" offset="0%"></stop><stop stop-color="#3fffff" offset="100%"></stop></linearGradient>
        <linearGradient gradientTransform="matrix(1,0,0,1,0,0)" y2="0" x2="6.123233995736766e-17" y1="1" x1="0" id="1290-_0065ff-_3f8cff"><stop stop-color="#0065ff" offset="0%"></stop><stop stop-color="#3f8cff" offset="100%"></stop></linearGradient>
        <linearGradient gradientTransform="matrix(1,0,0,1,0,0)" y2="0" x2="6.123233995736766e-17" y1="1" x1="0" id="1490-_3200ff-_653fff"><stop stop-color="#3200ff" offset="0%"></stop><stop stop-color="#653fff" offset="100%"></stop></linearGradient>
        <linearGradient gradientTransform="matrix(1,0,0,1,0,0)" y2="0" x2="6.123233995736766e-17" y1="1" x1="0" id="1690-_cb00ff-_d83fff"><stop stop-color="#cb00ff" offset="0%"></stop><stop stop-color="#d83fff" offset="100%"></stop></linearGradient>
        <linearGradient gradientTransform="matrix(1,0,0,1,0,0)" y2="0" x2="6.123233995736766e-17" y1="1" x1="0" id="1890-_ff0099-_ff3fb2"><stop stop-color="#ff0099" offset="0%"></stop><stop stop-color="#ff3fb2" offset="100%"></stop></linearGradient></defs>
        
</body></html>
EOL;

?>
