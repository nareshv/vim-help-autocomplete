<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>Inline Snippets</title>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/3.0/css/font-awesome.css" />
<link rel="stylesheet" href="static/cover.css">
<link rel="stylesheet" href="static/styles.css">
<style>
pre {
    text-align: left;
    background-color: #333;
    color: #fff;
    border: 1px dashed #ccc;
}
input {
    color: #333;
}
</style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <label> Github Repository: </label>
                    <input type="text" id="repo" name="repo" value = 'gmarik/vundle/master' />
                    <button type="submit" class="btn btn-primary">Fetch Snippets</button>
                </div>
            </div>
            <div class="col-md-6">
                <div id="snippets"></div>
            </div>
        </div>
    </div>
<!--
<div class="container">
<div id="editor" class='ace_editor'>function foo(items) {
    var x = "All this is syntax highlighted";
    return x;
}</div>
</div>
-->
<script src="static/jquery-1.9.1.min.js"></script>
<script src="static/jquery.sortable.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/ace/1.1.01/ace.js" charset="utf-8"></script>
<script>
// var editor = ace.edit("editor");
//editor.setTheme("ace/theme/monokai");
//editor.getSession().setMode("ace/mode/javascript");
//var repo = 'gmarik/vundle/master';
function get_repo(repo) {
    $.getJSON('extract.php?repo=' + repo, function(data) {
        var items = [];
        var count = 0;
        if (data.r) {
            $.each( data.r, function( key, val ) {
                items.push( "<li><b>Snippet - " + count + "</b><pre id='snip_" + count + "' contenteditable>" + val + "</pre></li>" );
                count++;
            });
            $( "<ul/>", {
                "class": "my-new-list",
                    html: items.join( "" )
            }).appendTo( "#snippets" );
        } else {
            $('#snippets').html('<p class=error>Error fetching information. Please make sure you have correct repository.</p>');
        }
    });
}

$('button.btn-primary').on('click', function() {
    get_repo($('#repo').val());
});
</script>
</body>
</html>
