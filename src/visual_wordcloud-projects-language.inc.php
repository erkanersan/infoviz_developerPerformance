<?php
function wordcloudLanguageGithub(){

$datafile = "/infovis/data-json-projects-language.php";

echo <<<EOL
<script src="http://d3js.org/d3.v3.min.js"></script>
<script src="lib/wordcloud.d3/d3.layout.cloud.js"></script>
<style>

div.wwordcloud-language-github {
}

</style>
<div class="wwordcloud-language-github" id="wordcloud-language-github"></div>
<script>
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

var data; // a global
d3.json("$datafile", function(error, json) {
  if (error) return console.warn(error);

  var fill = d3.scale.category20c();

  d3.layout.cloud().size([180, 320])
      .words(json.map(function(d) { return {text: d.language, size: 5+d.count *3 }; }))
      .padding(3)
      .rotate(function() { return ~~(Math.random() * 2) * 40; })
      .font("Impact")
      .fontSize(function(d) { return d.size; })
      .on("end", draw)
      .start();

  function draw(words) {
    d3.select("#wordcloud-language-github").append("svg")
        .attr("width", 180)
        .attr("height", 320)
      .append("g")
        .attr("transform", "translate(80,155)")
      .selectAll("text")
        .data(words)
      .enter().append("text")
        .style("font-size", function(d) { return d.size + "px"; })
        .style("font-family", "Impact")
        .style("fill", function(d, i) { return myColor(d.text); })
        .attr("text-anchor", "middle")
        .attr("transform", function(d) {
          return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
        })
        .text(function(d) { return d.text; });
  }
});
</script>

EOL;
}

?>
