<?php

function bulletCharts($type, $projectid){

if ($projectid==0) {
  switch ($type) {
      case "commits":
	$datafile = "/infovis/data-json-listofdevelopersofprojectwithcommits-COMPARE.php";
        break;
      case "pullrequests":
	$datafile = "/infovis/data-json-listofdevelopersofprojectwithpullrequests-COMPARE.php";
        break;
      case "issues":
	$datafile = "/infovis/data-json-listofdevelopersofprojectwithissues-COMPARE.php";
        break;
      case "comments":
	$datafile = "/infovis/data-json-listofdevelopersofprojectwithcomments-COMPARE.php";
        break;
  }


} else {
  switch ($type) {
      case "commits":
	$datafile = "/infovis/data_json_listofdevelopersofprojectwithcommits.php?q=$projectid";
        break;
      case "pullrequests":
	$datafile = "/infovis/data_json_listofdevelopersofprojectwithpullrequests.php?q=$projectid";
        break;
      case "issues":
	$datafile = "/infovis/data_json_listofdevelopersofprojectwithissues.php?q=$projectid";
        break;
      case "comments":
	$datafile = "/infovis/data_json_listofdevelopersofprojectwithcomments.php?q=$projectid";
        break;
  }
}


echo <<<EOL
<!DOCTYPE html>
<meta charset="utf-8">
<style>
div.bullet-charts {
  font-family: Absolut, "Helvetica Neue", Helvetica, Arial, sans-serif;
  width: 735px;
  cursor:pointer;
}
.bullet { font: 10px sans-serif; }
.bullet .marker { stroke: #000; stroke-width: 2px; }
.bullet .tick line { stroke: #666; stroke-width: .5px; }
.bullet .range.s0 { fill: #eee; }
.bullet .range.s1 { fill: #ddd; }
.bullet .range.s2 { fill: #ccc; }
.bullet .measure.s0 { fill: lightsteelblue; }
.bullet .measure.s1 { fill: steelblue; }
.bullet .title { font-size: 14px; font-weight: bold; }
.bullet .subtitle { fill: #999; }
</style>
<script src="http://d3js.org/d3.v3.min.js"></script>
<script src="lib/bullet-charts/bullet.js"></script>

<div class="bullet-charts"></div>

<script>

var margin = {top: 5, right: 15, bottom: 15, left: 122},
    width = 735 - margin.left - margin.right,
    height = 50 - margin.top - margin.bottom;

var chart = d3.bullet()
    .width(width)
    .height(height);

  d3.json("$datafile", function(error, data) {
  var svg = d3.select("div.bullet-charts").selectAll("svg")
      .attr("width", 200)
      .attr("height", 200)
      .data(data)
    .enter().append("svg")
      .on("click", function(d) { window.parent.location.href="/infovis/developer_detail.php?id="+d.id; } )
      .attr("class", "bullet")
      .attr("width", width + margin.left + margin.right )
      .attr("height", height + margin.top + margin.bottom)
    .append("g")
      .attr("transform", "translate(" + margin.left + "," + margin.top + ")")
      .call(chart);

  var title = svg.append("g")
      .style("text-anchor", "end")
      .attr("transform", "translate(-6," + height / 2 + ")");

  title.append("text")
      .on("click", function(d) { window.parent.location.href="/infovis/developer_detail.php?id="+d.id; } )
      .attr("class", "title")
      .text(function(d) { return d.title.substring(0, 14); });

  title.append("text")
      .on("click", function(d) { window.parent.location.href="/infovis/developer_detail.php?id="+d.id; } )
      .attr("class", "subtitle")
      .attr("dy", "1em")
      .text(function(d) { return d.subtitle; });
});

</script>
EOL;
}

?>
