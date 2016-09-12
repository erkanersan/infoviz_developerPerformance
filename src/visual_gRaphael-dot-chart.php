<?php include("infovis.inc.php");?>

<?php
$_userid = $_GET["id"];
$_type   = $_GET["type"];

horizontalBarCharts($_type, $_userid);

function horizontalBarCharts($type, $userid){

switch ($type) {
    case "commits":
      $query = "select dayname(created_at) DN, hour(created_at) H, committer_id, count(committer_id)  as count from commits where committer_id = $userid group by DN, H, committer_id";
      break;
    case "pullrequests":
      $query = "select dayname(ph.created_at) DN, hour(ph.created_at) H, p.user_id as committer_id, count(p.pullreq_id) as count from pull_requests p, pull_request_history ph where p.pullreq_id=ph.pull_request_id and p.user_id = $userid and ph.action = 'opened' group by DN, H, committer_id";
      break;
    case "issues":
      $query = "select dayname(created_at) DN, hour(created_at) H, reporter_id as committer_id, count(reporter_id)  as count from issues where reporter_id = $userid group by DN, H, committer_id";
      break;
    case "comments":
      $query = "select dayname(created_at) DN, hour(created_at) H, user_id as committer_id, count(user_id)  as count from commit_comments where user_id = $userid group by DN, H, user_id";
      break;
}


$link = dbconnect();
$result = mysqli_query($link, $query );

$sundays = array();
$mondays = array();
$tuesdays = array();
$wednesdays = array();
$thursdays = array();
$fridays = array();
$saturdays = array();

for ($i=0;$i<24;$i++) {
  $hour=$i;
  $size=0;
  
  $sundays[$hour]=$size;
  $mondays[$hour]=$size;
  $tuesdays[$hour]=$size;
  $wednesdays[$hour]=$size;
  $thursdays[$hour]=$size;
  $fridays[$hour]=$size;
  $saturdays[$hour]=$size;
}

while ( $row = mysqli_fetch_assoc($result) ) {

  $dayname  = $row["DN"];
  $hour     = intval($row["H"]);
  $size     = intval($row["count"]);

  switch ($dayname) {
    case "Sunday":
        $sundays[$hour]=$size;
        break;
    case "Monday":
        $mondays[$hour]=$size;
        break;
    case "Tuesday":
        $tuesdays[$hour]=$size;
        break;
    case "Wednesday":
        $wednesdays[$hour]=$size;
        break;
    case "Thursday":
        $thursdays[$hour]=$size;
        break;
    case "Friday":
        $fridays[$hour]=$size;
        break;
    case "Saturday":
        $saturdays[$hour]=$size;
        break;
  }
}

//echo var_dump($sundays);
//echo "<br>" . count($sundays) . " " . implode(", ",$sundays);
//echo "<br>" . count($saturdays);
//echo "<br>" . count($fridays);
//echo "<br>" . count($thursdays);
//echo "<br>" . count($wednesdays);
//echo "<br>" . count($tuesdays);
//echo "<br>" . count($mondays);

$data = "["  . implode(", ",$sundays);
$data = $data .", " .implode(", ",$saturdays);
$data = $data .", " .implode(", ",$fridays);
$data = $data .", " .implode(", ",$thursdays);
$data = $data .", " .implode(", ",$wednesdays);
$data = $data .", " .implode(", ",$tuesdays);
$data = $data .", " .implode(", ",$mondays);
$data = $data . "]";

//$data = "[294, 300, 204, 255, 348, 383, 334, 217, 114, 33, 44, 26, 41, 39, 52, 17, 13, 2, 0, 2, 5, 6, 64, 153, 294, 313, 195, 280, 365, 392, 340, 184, 87, 35, 43, 55, 53, 79, 49, 19, 6, 1, 0, 1, 1, 10, 50, 181, 246, 246, 220, 249, 355, 373, 332, 233, 85, 54, 28, 33, 45, 72, 54, 28, 5, 5, 0, 1, 2, 3, 58, 167, 206, 245, 194, 207, 334, 290, 261, 160, 61, 28, 11, 26, 33, 46, 36, 5, 6, 0, 0, 0, 0, 0, 0, 9, 9, 10, 7, 10, 14, 3, 3, 7, 0, 3, 4, 4, 6, 28, 24, 3, 5, 0, 0, 0, 0, 0, 0, 4, 3, 4, 4, 3, 4, 13, 10, 7, 2, 3, 6, 1, 9, 33, 32, 6, 2, 1, 3, 0, 0, 4, 40, 128, 212, 263, 202, 248, 307, 306, 284, 222, 79, 39, 26, 33, 40, 61, 54, 17, 3, 0, 0, 0, 3, 7, 70, 199]";
//echo "<br>" . $data;

echo <<<EOL
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>gRaphaël Dot Chart</title>
        <script src="/infovis/lib/raphaeljs.com/raphael.js"></script>
        <script src="/infovis/lib/raphaeljs.com/g.raphael.js"></script>
        <script src="/infovis/lib/raphaeljs.com/g.dot.js"></script>
        <script>
            window.onload = function () {
                var r = Raphael("holder"),
                    xs = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23],
                    ys = [7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
                    data = $data,
                    axisy = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                    axisx = ["0am", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11"];

                r.dotchart(10, 10, 620, 260, xs, ys, data, {symbol: "o", max: 10, heat: true, axis: "0 0 1 1", axisxstep: 23, axisystep: 6, axisxlabels: axisx, axisxtype: " ", axisytype: " ", axisylabels: axisy}).hover(function () {
                    this.marker = this.marker || r.tag(this.x, this.y, this.value, 0, this.r + 2).insertBefore(this);
                    this.marker.show();
                }, function () {
                    this.marker && this.marker.hide();
                });
            };
        </script>
    </head>
    <body class="raphael" id="g.raphael.dmitry.baranovskiy.com">
        <div id="holder"></div>
    </body>
</html>
EOL;
}
?>
