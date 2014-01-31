<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>VIM - Help Index</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="http://twitter.com/favicons/favicon.ico">
    <link rel="stylesheet" href="static/examples.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="static/cover.css">
    <link rel="stylesheet" href="static/styles.css">
  </head>
  <body>
    <div class="container">
      <div class="example example-twitter-oss">
        <h2 class="example-name"><img src="static/64.png"> VIM - Help Index</h2>
        <p class="example-description">Online Help for various VIM Options</p>
        <div class="demo">
          <input id="search" class="typeahead" type="text" placeholder="Search for an option...">
        </div>
	<div id="output_params">
	</div>
	<div class="gist" id="output">
	</div>
      </div>
    </div>
  <script src="static/hogan-2.0.0.js"></script>
  <script src="static/jquery-1.9.1.min.js"></script>
  <script src="static/typeahead.min.js"></script>
  <script>
var template = Hogan.compile([
	'<div class="panel panel-info">',
	'<div class="panel-heading"><div class="panel-title">Help For: {{option}}</div></div>',
	'<div class="panel-body">',
	'<table class="table table-bordered">',
	'<tr><th>Short Description</th><td>{{short_desc}}</td></tr>',
	'<tr><th>Scope: Global</th><td>{{{is_global}}}</td></tr>',
	'<tr><th>Scope: Local To Buffer</th><td>{{{local_to_buffer}}}</td></tr>',
	'<tr><th>Scope: Local To Window</th><td>{{{local_to_window}}}</td></tr>',
	'<tr><th>Required Features</th><td>{{req_feature}}</td></tr>',
	'<tr><th>Datatype</th><td>{{data_type}}</td></tr>',
	'<tr><th>Datatype Values</th><td>{{data_type_values}}</td></tr>',
	'<tr><th>Long Description</th><td>{{{long_desc}}}</td></tr>',
	'</table>',
	'</div>',
	'</div>',
].join(''));
$('.example-twitter-oss .typeahead').typeahead({
  name: 'twitter-oss',
  prefetch: 'repo.php',
  template: [
    '<p class="repo-language">{{language}}</p>',
    '<p class="repo-name">{{name}}</p>',
    '<p class="repo-description">{{description}}</p>'
  ].join(''),
  engine: Hogan
}).bind('typeahead:selected typeahead:autocompleted', function(obj, datum) {
	$.getJSON('repo.php?id='+datum.name, function(data) {
		$('#output').html(template.render(data));
	});
});
  </script>
  </body>
</html>
