<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>ACE in Action</title>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/3.0/css/font-awesome.css" />
<style>
#editor {
	width: 300px;
	height: 400px;
}
.ace_editor {
	position: relative;
	overflow: hidden;
	font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', 'Consolas', 'source-code-pro', monospace;
	font-size: 12px;
	line-height: normal;
	color: black;
}
</style>
</head>
<body>
<div class="container">
<div id="editor" class='ace_editor'>function foo(items) {
    var x = "All this is syntax highlighted";
    return x;
}</div>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/ace/1.1.01/ace.js" charset="utf-8"></script>
<script>
    var editor = ace.edit("editor");
    //editor.setTheme("ace/theme/monokai");
    //editor.getSession().setMode("ace/mode/javascript");
</script>
</body>
</html>
