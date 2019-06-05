<!DOCTYPE html>
<html>
<head>
	<title>Order | Admin Panel | Online Shopping</title>
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/all.css">
	<link rel="stylesheet" href="../css/admin-style.css">
	<script src="../js/jquery.js" type="text/javascript"></script>
	<script src="../js/popper.1.14.3.min.js" type="text/javascript"></script>
	<script src="../js/bootstrap.min.js"></script>

	<script>

		//Loads the page data
		function get_order_list() {
			xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("get_order_list_data").innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "order_process.php", true);
			xmlhttp.send();
		}

		//Toggle Order Return Status
		function order_status(order_id,order_status) {
			//this will toggle the value of order status
			if (order_status == 0) {
				order_status = 1;
			}else {
				order_status = 0;
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("get_order_list_data").innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "order_process.php?ord_id="+order_id+"&ord_status="+order_status, true);
			xmlhttp.send();
		}

		//Toggle Order Return Status
		function order_return_status (order_id, return_status) {
			if (return_status == 0) {
				return_status = 1;
			}else {
				return_status = 0;
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("get_order_list_data").innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "order_process.php?order_id="+order_id+"&return_status="+return_status, true);
			xmlhttp.send();
		}
	</script>
</head>
<body onload="get_order_list();">
	<?php include "includes/header.php"; ?>

	<div class="container">
		<div class="row">
			<div id="get_order_list_data">
				<!--We are getting the order list data from order_process.php here -->
				
			</div>
		</div>
	</div>

	<?php include "includes/footer.php"; ?>

</body>
</html>