<?php
$_id = $_GET["id"];
$_type = $_GET["type"];

switch ($_type) {
    case "commits":
      $datafile = "/infovis/data-json-user-for_all_projects-number_of_commits.php?id=$_id";
      break;
    case "pullrequests":
      $datafile = "/infovis/data-json-user-for_all_projects-number_of_pullrequests.php?id=$_id";
      break;
    case "issues":
      $datafile = "/infovis/data-json-user-for_all_projects-number_of_issues.php?id=$_id";
      break;
    case "comments":
      $datafile = "/infovis/data-json-user-for_all_projects-number_of_comments.php?id=$_id";
      break;
}

echo <<<EOL
<!DOCTYPE html>
<meta charset="utf-8">
<style>

text {
  font: 10px sans-serif;
}

</style>
<div>
<script src="http://d3js.org/d3.v3.min.js"></script>
<script>

var diameter = 265,
    format = d3.format(",d"),
    color = d3.scale.category20c();

var bubble = d3.layout.pack()
    .sort(null)
    .size([diameter, diameter])
    .padding(1.5);

var svg = d3.select("div").append("svg")
    .attr("width", diameter)
    .attr("height", diameter)
    .attr("class", "bubble");

d3.json("$datafile", function(error, root) {
  var node = svg.selectAll(".node")
      .data(bubble.nodes(classes(root))
      .filter(function(d) { return !d.children; }))
      .enter().append("g")
      .attr("class", "node")
      .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });

  node.append("title")
      .text(function(d) { return d.className + ": " + format(d.value) + " commits"; });

  node.append("circle")
      .on("click", function(d) { window.parent.location.href="/infovis/project_detail.php?id="+d.classid; } )
      .attr("r", function(d) { return d.r; })
      .style("fill", function(d) { return color(d.value); });

  node.append("text")
      .attr('pointer-events', 'all')
      .on("click", function(d) { window.parent.location.href="/infovis/project_detail.php?id="+d.classid; } )
      .attr("dy", ".3em")
      .style("text-anchor", "middle")
      .text(function(d) { return d.className.substring(0, d.r / 3); });
});

// Returns a flattened hierarchy containing all leaf nodes under the root.
function classes(root) {
  var classes = [];

  function recurse(name, node) {
    if (node.children) node.children.forEach(function(child) { recurse(node.name, child); });
    else classes.push({packageName: name, className: node.name, value: node.size, classid: node.id});
  }

  recurse(null, root);
  return {children: classes};
}

d3.select(self.frameElement).style("height", diameter + "px");

</script>
EOL;

?>
