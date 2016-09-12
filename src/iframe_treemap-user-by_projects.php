<?php
$_id = $_GET["id"];

$datafile = "/infovis/data-json-user-for_all_projects-number_of_commits.php?id=$_id";

echo <<<EOL
<!DOCTYPE html>
<meta charset="utf-8">
<style>

body {
  font-family: Absolut, "Helvetica Neue", Helvetica, Arial, sans-serif;
  position: relative;
  width: 180px;
}

a {text-decoration:none}
.completed {text-decoration: line-through;}

A:link{COLOR: #000099; TEXT-DECORATION: none}
A:visited {COLOR: #000099; TEXT-DECORATION: none}
A:hover {COLOR: #FF0000; TEXT-DECORATION: none}

form {
  position: absolute;
  right: 10px;
  top: 10px;
}

.node {
  border: solid 1px white;
  font: 10px sans-serif;
  line-height: 12px;
  overflow: hidden;
  position: absolute;
  top: 1px;
  text-indent: 2px;
  padding-top: 2px;
  pppadding-left: 2px;
}

</style>
<form>
</form>
<script src="http://d3js.org/d3.v3.min.js"></script>
<script>

var margin = {top: 1, right: 1, bottom: 1, left: 1},
    width = 180 - margin.left - margin.right,
    height = 225 - margin.top - margin.bottom;

var color = d3.scale.category20c();

var treemap = d3.layout.treemap()
    .size([width, height])
    .sticky(true)
    .value(function(d) { return d.size; });

var div = d3.select("body").append("div")
    .style("position", "relative")
    .style("width", (width + margin.left + margin.right) + "px")
    .style("height", (height + margin.top + margin.bottom) + "px")
    .style("left", margin.left + "px")
    .style("top", margin.top + "px");

d3.json("$datafile", function(error, root) {
  var node = div.datum(root).selectAll(".node")
      .data(treemap.nodes)
      .enter().append("div")
      .attr("class", "node")
      .call(position)
      .style("background", function(d) { return d.children ? color(d.language) : color(d.language); })
      .html(function(d) { return d.children ? null : "<b><a title=\""+d.name+" has "+d.size+" forks, "+d.language+" project.\" target=\"_parent\" href='/infovis/project_detail.php?id="+d.id+"'>"+d.name+"</a></b>"; });

  d3.selectAll("input").on("change", function change() {
    var value = this.value === "count"
        ? function() { return 1; }
        : function(d) { return d.size; };

    node
        .data(treemap.value(value).nodes)
      .transition()
        .duration(1500)
        .call(position);
  });
});

function position() {
  this.style("left", function(d) { return d.x + "px"; })
      .style("top", function(d) { return d.y + "px"; })
      .style("width", function(d) { return Math.max(0, d.dx - 1) + "px"; })
      .style("height", function(d) { return Math.max(0, d.dy - 1) + "px"; });
}

</script>
EOL;

?>
