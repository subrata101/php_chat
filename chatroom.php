<?php
	ob_start();
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Chat Online</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<select name="users" id="users" style="padding: 1em;border-radius: 8px;font-size: 14px;float: left;">
				<option value="arka">Arka</option>
				<option value="kunal">Kunal</option>
				<option value="rupa">Rupa</option>
				<option value="sachin">Sachin</option>
				<option value="sumalya">Sumalya</option>
				<option value="subrata">Subrata</option>
			</select>
			Welcome <span id="userName" style="color:#424242;"><?php echo $_SESSION['uid']; ?></span>
		</div>
		
		<div id="content">
			<div id="containerMessages"></div>
		</div>
		
		<div id="footer">
			<form id="formChat" type="name">
				<table>
					<tr>
						<td width="90%"><input type="text" placeholder="message" id="msg" autofocus autocomplete="off"></td>
						<td><input type="button" value="Send" id="submit"></td>
					</tr>
				</table>
			</form>
		</div>
	</div>

<script src="js/jquery.js" type="text/javascript"></script>

<script>
	var conn = new WebSocket('ws://192.168.2.126:8080');
	var user = '<?php echo $_SESSION['uid']; ?>';
	var c=0;
	conn.onopen = function(e) {
		var msg = {"new":"y","user":user};
	    console.log("Connection established! "+JSON.stringify(msg));
		conn.send(JSON.stringify(msg));
	};

	conn.onmessage = function(e) {
		var d = JSON.parse(e.data);
		$('#containerMessages').append("<div><span class=\"yourMessage\">"+d.msg+"<br>from "+d.from+"</span></div>");
		console.log(e.data);
		$("#containerMessages div:last-child").get(0).scrollIntoView();
	};

	$(function(){
		$('#submit').on('click',function(){
			var msg = {"new":"n","user":$('#users').val(),"msg":$('#msg').val(),"from":user};
			// console.log($('#msg').val()+" "+$('#users').val());
			console.log(JSON.stringify(msg));
			$('#containerMessages').append("<div><span class=\"meMessage\">"+$('#msg').val()+"</span></div>");
			conn.send(JSON.stringify(msg));
			$("#containerMessages div:last-child").get(0).scrollIntoView();
		});
	});

</script>

</body>
</html>
