<?php 
session_start();
$_SESSION['id'] = uniqid();
?>

<html>
<head>
<script type='text/javascript' src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

</head>
<body>

<div class="jumbotron" style="background-repeat: no-repeat; background-color: #E0EEEE; height: auto;">
<h1>Rock Paper Scissors</h1>
<p> funfunfun</p>
</div>


<div id="left" style="margin-left:10%">
	<div id="buttons">

		<div class="row" style="padding-bottom: 20px;">
			<button type="button" id="rock" >Rock</button>
			<button type="button" id="paper" >Paper</button>
			<button type="button" id="scissors" >Scissors</button>
		</div>
	</div>

	<div id="result">
		<!-- <?php include 'rps.php'; ?> -->
	</div>
</div>


<div id="right" style="float:right; margin-right: 10%;">
<!-- <?php include 'stats.php'; ?> -->
</div>




</body>
</html>



<script type='text/javascript'>
    $(document).ready(function()
    {
      //  alert("JQuery is working! :D");
       // $("div").css("border", "3px solid red");

		$("#buttons").find("button").click(function(event){
			event.preventDefault();

			//if (request) {
		   //     request.abort();
		  //  }

		    $("#result").html('');
 
		    var $data =  this.id + "=1";

		    $.ajax({
		    	url: 'rps.php',
		    	type: 'POST',
		    	datatype: 'http',
		    	data: $data,
		    	success: function(message){
		        	$("#result").hide().html(message).fadeIn(1000);}
		    });

		    $.ajax({
		    	url: 'stats.php',
		    	type: 'GET',
		    	datatype: 'http',
		    	success: function(message){
		    		$("#right").html(message);}
		    });
		    
		});

    
		// $(window).onunload(function{
		// 	<?php unload(); ?>
		// });

	});


</script>
