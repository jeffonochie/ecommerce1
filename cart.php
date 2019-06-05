<?php session_start(); //the session needs to be created at the very top

	include 'includes/db.php'; 
	
	if (isset($_GET["chkout_item_id"])) {
		$date = date("Y-m-d h:i:s");
		$random_num = mt_rand();		//this generates a random number
		

				if 	(isset($_SESSION["ref"])) {	

					 /* Checks if the session is already created and then, do nothing; don't create it again the session variable should be same for thesame user and same browser */
				}
				else {
					
					$_SESSION["ref"] = $date."_".$random_num;
					
				}	
				$chkout_sql = "INSERT INTO checkout (chkout_item, chkout_ref, chkout_time, chkout_qty) VALUES('$_GET[chkout_item_id]', '$_SESSION[ref]', '$date', 1)";
				if ($chkout_run = mysqli_query($conn, $chkout_sql)) {
					/* If the record is inserted successfully, we close PHP then add JS code to prevent the item_ID from showing on the URL, this has a problem of creating the record again once page is refresh since we usually send item_ID with buy button. ie we redirect users to their cart once they add an item. remember their cart is streamlined by their ref number */
					?>
						<script type="text/javascript">window.location="cart.php";</script>
					<?php
				}
	}
//Submit order Modal Form recieved here
	if (isset($_POST['submit_order'])) {
		$firstname = mysqli_real_escape_string($conn, strip_tags($_POST['fname']));
		$lastname = mysqli_real_escape_string($conn, strip_tags($_POST['lname']));
		$email = mysqli_real_escape_string($conn, strip_tags($_POST['email']));
		$phone = mysqli_real_escape_string($conn, strip_tags($_POST['phone']));
		$state = mysqli_real_escape_string($conn, strip_tags($_POST['state']));
		$delivery_address = mysqli_real_escape_string($conn, strip_tags($_POST['delivery_address']));

		$order_ins_sql = "INSERT INTO orders (order_firstname, order_lastname, order_email, order_contact, order_state, order_delivery_address, order_ref, order_total, order_status, order_return_status) VALUES ('$firstname', '$lastname', '$email', '$phone', '$state', '$delivery_address', '$_SESSION[ref]', '$_SESSION[grand_total]', 0, 0)";
		if (mysqli_query($conn, $order_ins_sql)) { ?>
			<script>alert("Record Inserted successfully!");</script> 
		<?php }
		else {
			echo "Error " . $order_ins_sql . "<br>" . mysqli_error($conn);
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Shopping Cart</title>
		<meta charset="utf-8">
		<meta name="viewpoint" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/bootstrap.min.css">

		<link rel="stylesheet" href="css/all.css">

		<link rel="stylesheet" href="css/custom.css">

		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/popper.1.14.3.min.js" type="text/javascript"></script>

		<script src="js/bootstrap.min.js"></script>

		<script type="text/javascript">
			function ajax_call_to_process() {
				xmlhttp = new XMLHttpRequest();
				
				xmlhttp.onreadystatechange = function() { //If anything changes on the page, if any actions is taken
					//is everything is fine:4, is the page found and all okay: 200
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) { 
						document.getElementById("get_process_data").innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "cart_process.php", true);
				xmlhttp.send();
			}

			function chkout_del_func (chkout_id) { //Here we recieve sent argument from the onclick function when delete is clicked in cart_process.php NOTE look at both pages as one
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("get_process_data").innerHTML = xmlhttp.responseText;
					}
				}
				xmlhttp.open("GET", "cart_process.php?chkout_del_id="+chkout_id, true);
				xmlhttp.send();
			}

			function up_chkout_qty_func (chk_qty, chk_id) {
				xmlhttp.onreadystatechange = function () {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("get_process_data").innerHTML = xmlhttp.responseText;
					}
					
				}
				xmlhttp.open("GET", "cart_process.php?up_chk_qty="+chk_qty+"&up_chk_id="+chk_id, true);
				xmlhttp.send();

			}
		</script>
	</head>
	<body onload="ajax_call_to_process();">
		<?php include 'includes/header.php'; ?>

		<div class="container">
			
			<div class="page-header">
				
			</div>
			<div class="card">
				<div class="card-header">Order Details - Checkout &nbsp;<i class="fas fa-shopping-cart"></i><div class="float-right"><button class="btn btn-outline-success" data-toggle="modal" data-target="#proceed_modal" data-backdrop="static" data-keyboard="false">Proceed</button></div></div>
				<div class="card-body table-responsive-md">
					
						<div id="get_process_data"></div>

					
				</div> <!--Close of card body that houses get_process_data  -->
				<div class="card-footer">
					<button class="btn btn-success float-right" data-toggle="modal" data-target="#proceed_modal" data-backdrop="static" data-keyboard="false">Proceed</button>
				</div>
			</div>


			<!--The Proceed Button Modal -->
		<div class="modal fade" id="proceed_modal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<form method="post"> <!-- No need for action= since we are posting the form to same page. SQL above Line 27 -->
							<div class="form-group">
								<label for="firstname">Firstname</label>
								<input type="text" id="firstname" name="fname" class="form-control" placeholder="Firstname">
							</div>
							<div class="form-group">
								<label for="lastname">Lastname</label>
								<input type="text" id="lastname" name="lname" class="form-control" placeholder="Lastname">
							</div>
							<div class="form-group">
								<label for="email">Email</label>
								<input type="email" id="email" name="email" class="form-control" placeholder="Email">
							</div>
							<div class="form-group">
								<label for="phone">Phone Number</label>
								<input type="text" id="phone" name="phone" class="form-control" placeholder="Phone Number">
							</div>
							<div class="form-group">
								<label for="state">State</label>
								<input list="state" name="state" class="form-control">
								<datalist id="state">
									<option>Ajah</option>
									<option>Lekki</option>
									<option>Ibeju Lekki</option>
									<option>Victoria Island</option>
									<option>Surulere</option>
									<option>Ikorodu</option>
									<option>Ikeja</option>
									<option>Ikoyi</option>
								</datalist>
							</div>
							<div class="form-group">
								<label for="address">Delivery Address</label>
								<textarea id="address" name="delivery_address" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<button name="submit_order" class="btn btn-success btn-block">Submit Order</button>
							</div>
						</form>
						
					</div>
					<div class="modal-footer">
						<button data-dismiss="modal" class="btn btn-danger">Close</button>
					</div>
				</div>
			</div>
		</div> <!-- Proceed Modal Ends here -->

		</div>

		<?php include 'includes/footer.php'; ?>
	</body>
</html>