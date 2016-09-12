<?php
$_type = $_GET["type"];

switch ($_type) {
    case "forks":
      $datafile = "/infovis/data-json-projects_and_their_forks_num.php";
      break;
    case "commits":
      $datafile = "/infovis/data-json-projects_and_their_commits_num.php";
      break;
    case "developers":
      $datafile = "/infovis/data-json-projects_and_their_developer_num.php";
      break;
}

echo <<<EOL
<!DOCTYPE html>
<meta charset="utf-8">
<style>

body {
  font-family: Absolut, "Helvetica Neue", Helvetica, Arial, sans-serif;
  position: relative;
  width: 520px;
}

a {text-decoration:none}
.completed {text-decoration: line-through;}

A:link{COLOR: #000055; TEXT-DECORATION: none}
A:visited {COLOR: #000055; TEXT-DECORATION: none}
A:hover {COLOR: #FF0000; TEXT-DECORATION: none}

a.fill-div {
    display: block;
    height: 100%;
    width: 100%;
    text-decoration: none;
}

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
    width = 520 - margin.left - margin.right,
    height = 300 - margin.top - margin.bottom;

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
function myColor(language) {
  var languages = {
    'C':      "#a6cee3",
    'C#':     "#1f78b4",
    'C++':    "#b2df8a",
    'CSS':    "#33a02c",
    'Go':     "#6a3d9a",
    'Java':   "#e31a1c",
    'JS':     "#fdbf6f",
    'PHP':    "#ff7f00",
    'Python': "#fb9a99",
    'R':      "#b15928",
    'Ruby':   "#6a51a3",
    'Scala':  "#9e9ac8",
    'TypeScript': "#bf812d"
  };
  
  return languages[language]; 
};

  var node = div.datum(root).selectAll(".node")
      .data(treemap.nodes)
      .enter().append("div")
      .attr("class", "node")
      .call(position)
      .style("background", function(d) { return d.children ? myColor(d.language) : myColor(d.language); })
      .html(function(d) { return d.children ? null : "<b><a class=\"fill-div\" title=\""+d.name+" has "+d.size+" $_type, "+d.language+" project.\" target=\"_parent\" href='/infovis/project_detail.php?id="+d.id+"'>"+d.name+"</a></b>"; });

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
