<!DOCTYPE html>
<html>
<head>
    <title>Draggable, Moveable and Resizable DIV using jQuery</title>
    <script type="text/javascript" 
  src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" 
  src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" 
  href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/themes/base/jquery-ui.css"/>
</head>
<body style="height: 100vh">
    
       <div class="resizeDiv" style="background-color: orange;overflow: hidden;">resize me
        <div class="resizeDiv" style="background-color: green">resize me</div>
       </div>

        <div class="resizeDiv" style="background-color: purple">resize me</div>
  
</body>

<script>
    $('.resizeDiv')
  .draggable({containment: "parent"})
  .resizable({containment: "parent"});
</script>
</html>