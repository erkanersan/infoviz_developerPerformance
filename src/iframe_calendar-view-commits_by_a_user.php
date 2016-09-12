<?php
$_id   = $_GET["id"];
$_type = $_GET["type"];

$_startdate = $_GET["startdate"];
$_enddate = $_startdate + 1;

$data_file="data-cvs-calendar-all-daily.php?type=$_type&id=". $_id;

$_cellSize = $_GET["cellSize"];
if(!$_cellSize) { $_cellSize = 12; };

$width = 585;
$height = 85;

if ($_cellSize == 12) {
  $width = 665;
  $height = 90;
}

echo <<<EOL
<!DOCTYPE html>
<meta charset="utf-8">
<style>

div.calendar {
  font: 10px sans-serif;
  shape-rendering: crispEdges;
}

.day {
  fill: #F3E7C4;
  stroke: #ccc;
}

.month {
  fill: none;
  stroke: #000;
  stroke-width: 2px;
}

.RdYlGn .q0-11{fill:rgb(247,251,255)}
.RdYlGn .q1-11{fill:rgb(222,235,247)}
.RdYlGn .q2-11{fill:rgb(198,219,239)}
.RdYlGn .q3-11{fill:rgb(158,202,225)}
.RdYlGn .q4-11{fill:rgb(107,174,214)}
.RdYlGn .q5-11{fill:rgb(66,146,198)}
.RdYlGn .q6-11{fill:rgb(33,113,181)}
.RdYlGn .q7-11{fill:rgb(8,81,156)}
.RdYlGn .q8-11{fill:rgb(8,48,107)}
.RdYlGn .q9-11{fill:rgb(5,36,82)}
.RdYlGn .q10-11{fill:rgb(5,36,82)}

</style>
<script src="http://d3js.org/d3.v3.min.js"></script>
<div class="calendar" id="div-calendar-view"></div>
<script>

var width = $width,
    height = $height,
    cellSize = $_cellSize; // cell size

var day = d3.time.format("%w"),
    week = d3.time.format("%U"),
    percent = d3.format(".1%"),
    format = d3.time.format("%Y-%m-%d");

var color = d3.scale.quantize()
    .domain([0, 40])
    .range(d3.range(11).map(function(d) { return "q" + d + "-11"; }));

var svg = d3.select("div.calendar").selectAll("svg")
    .data(d3.range($_startdate, $_enddate))
  .enter().append("svg")
    .attr("width", width)
    .attr("height", height)
    .attr("class", "RdYlGn")
  .append("g")
    .attr("transform", "translate(" + ((width - cellSize * 53) / 2) + "," + (height - cellSize * 7 - 1) + ")");

svg.append("text")
    .attr("transform", "translate(-6," + cellSize * 3.5 + ")rotate(-90)")
    .style("text-anchor", "middle")
    .text(function(d) { return d; });

var rect = svg.selectAll(".day")
    .data(function(d) { return d3.time.days(new Date(d, 0, 1), new Date(d + 1, 0, 1)); })
  .enter().append("rect")
    .attr("class", "day")
    .attr("width", cellSize)
    .attr("height", cellSize)
    .attr("x", function(d) { return week(d) * cellSize; })
    .attr("y", function(d) { return day(d) * cellSize; })
    .datum(format);

rect.append("title")
    .text(function(d) { return d; });

svg.selectAll(".month")
    .data(function(d) { return d3.time.months(new Date(d, 0, 1), new Date(d + 1, 0, 1)); })
  .enter().append("path")
    .attr("class", "month")
    .attr("d", monthPath);

d3.csv("$data_file", function(error, csv) {
  var data = d3.nest()
    .key(function(d) { return d.date; })
    .rollup(function(d) { return d[0].count; })
    .map(csv);

  rect.filter(function(d) { return d in data; })
      .attr("class", function(d) { return "day " + color(data[d]); })
    .select("title")
      .text(function(d) { return d + ", count: " + data[d]; });
});

function monthPath(t0) {
  var t1 = new Date(t0.getFullYear(), t0.getMonth() + 1, 0),
      d0 = +day(t0), w0 = +week(t0),
      d1 = +day(t1), w1 = +week(t1);
  return "M" + (w0 + 1) * cellSize + "," + d0 * cellSize
      + "H" + w0 * cellSize + "V" + 7 * cellSize
      + "H" + w1 * cellSize + "V" + (d1 + 1) * cellSize
      + "H" + (w1 + 1) * cellSize + "V" + 0
      + "H" + (w0 + 1) * cellSize + "Z";
}

d3.select(self.frameElement).style("height", "110px");

</script>
EOL;

?>
