<?php include "../includes/db.php"; 

	if (isset($_POST["add_item"])) {
		$item_name = mysqli_real_escape_string($conn, strip_tags($_POST["item_name"]));
		$item_description = mysqli_real_escape_string($conn, $_POST["item_description"]);
		$item_category = mysqli_real_escape_string($conn, strip_tags($_POST["item_category"]));
		$item_qty = mysqli_real_escape_string($conn, strip_tags($_POST["item_qty"]));
		$item_cost = mysqli_real_escape_string($conn, strip_tags($_POST["item_cost"]));
		$item_price = mysqli_real_escape_string($conn, strip_tags($_POST["item_price"]));
		$item_discount = mysqli_real_escape_string($conn, strip_tags($_POST["item_discount"]));
		$item_delivery_charges = mysqli_real_escape_string($conn, strip_tags($_POST["item_delivery_charges"]));

		if (isset($_FILES["item_image"]["name"])) {
			$file_status = 1;	//this means everything is good while 0 mean something is wrong
			$file_name = basename($_FILES["item_image"]["name"]);
			$file_dir = "../images/items/$file_name";	//file name should be included in the path
			$file_dir_db = "images/items/$file_name";
			$file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

				//If file already exist
				if (file_exists($file_name)) {
					$file_status = 0;
					echo "Sorry, file already exist";
				}

				//Check if file size is great than 2MB
				if ($_FILES["item_image"]["size"] > 200000) {	
					$file_status = 0;
					echo "Files size is larger than 2MB";
				}

				//Allow certain file formats
				if ($file_type != "jpg" && $file_type != "jpeg" && $file_type != "png" && $file_type != "gif") {
					$file_status = 0;
					echo "Sorry, only JPG, JPEG, PNG and GIF files allowed.";
				}

				//Check if $file_status is set to 0 by an error
				if ($file_status == 0) {
					echo "Sorry, You file was not uploaded.";
				}else{
					if (move_uploaded_file($_FILES["item_image"]["tmp_name"], $file_path)) {
						$item_ins_sql = "INSERT INTO items (item_name, item_description, item_cat, item_image, item_qty, item_cost, item_price, item_discount, item_delivery_charge) VALUES ('$item_name', '$item_description', '$item_category', '$file_dir_db', '$item_qty', '$item_cost', '$item_price', '$item_discount', $item_delivery_charges)";
						$item_ins_run = mysqli_query($conn, $item_ins_sql);
					}
				}
		}else {
			echo "Sorry, you file was not uploaded successfully.";
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Item List | Admin Panel</title>
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/all.css">
	<link rel="stylesheet" href="../css/admin-style.css">
	<script src="../js/jquery.js" type="text/javascript"></script>
	<script src="../js/popper.1.14.3.min.js" type="text/javascript"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/tinymce/tinymce.min.js"></script>
	<script>tinymce.init({ selector: "textarea"});</script>
	<script>

		function get_item_list_data() {
			xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("get_item_list_data").innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "item_list_process.php", true);
			xmlhttp.send();
		}

		function del_item(item_id) {
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("get_item_list_data").innerHTML = xmlhttp.responseText;
				}
			}

			xmlhttp.open("GET", "item_list_process.php?del_item_id="+item_id, true);
			xmlhttp.send();
		}

		//Edit Items
		function edit_items(itemId) {
			alert(itemId);
			// var myId = itemId;
			//edit_id = document.getElementById("edit_update_item"+item_id).value;
			var edit_name = document.getElementById("edit_item_name"+itemId).value,
			edit_description = document.getElementById("edit_item_description"+itemId).value,
			edit_category = document.getElementById("edit_item_category"+itemId).value,
			edit_qty = document.getElementById("edit_item_qty"+itemId).value,
			edit_cost = document.getElementById("edit_item_cost"+itemId).value,
			edit_price = document.getElementById("edit_item_price"+itemId).value,
			edit_discount = document.getElementById("edit_item_discount"+itemId).value,
			edit_delivery_charge = document.getElementById("edit_item_delivery_charges"+itemId).value,
			edit_item_image = document.getElementById("edit_item_image"+itemId).value;

	/*		xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("get_item_list_data").innerHTML = xmlhttp.responseText;
				}
			}
*/
			xmlhttp.open("GET", "item_list_process.php?edit_item=yes&edit_id="+itemId+"&item_name="+edit_name+"&edit_image="+edit_item_image+"&item_desc="+edit_description+"&item_cat="+item_category+"&item_qty="+edit_qty+"&item_cost="+edit_cost+"&item_price="+edit_price+"&item_discount="+edit_discount+"&item_delivery="+edit_delivery_charge, true);
			xmlhttp.send();
			
			$(document).ready(function() {
				$('#edit_modal'+itemId).modal('hide');
			})
			
			//$('#edit_form'+itemId).hide();
			
			return false;
		}
		

	</script>
</head>
<body onload="get_item_list_data();">
	<?php include "includes/header.php"; ?>

	<div class="container"><br>

		<button type="button" class="btn btn-danger btn-custom" data-toggle="modal" data-target="#add_new_item" data-backdrop="static" data-keyboard="false">Add New Item</button>

		<div class="modal fade" id="add_new_item" tabindex="-1" role="dialog" aria-labelledby="add_new_item_title">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header bg-success">
						<h4 class="modal-title text-white" id="add_new_item_title">Add New Item</h4>
						<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="modal-body">
						<form method="post" enctype="multipart/form-data">
							<div class="form-group">
								<label for="item_name">Name</label>
								<input type="text" name="item_name" class="form-control" placeholder="Item name" required>
							</div>
							<div class="form-group">
								<label for="item_description">Description</label>
								<textarea name="item_description" class="form-control" placeholder="Item description" required></textarea>
							</div>
							<div class="custom select">
								<label for="item_category">Category</label>
								<select name="item_category" class="custom-select" required>
									<option>Select Category</option>
									<?php 
										$cat_sql = "SELECT * FROM item_cat";
										$cat_run = mysqli_query($conn, $cat_sql);
										while ($cat_rows = mysqli_fetch_assoc($cat_run)) {
										//	$cat_name = ucwords($cat_rows['cat_name']);
											if ($cat_rows['cat_slug'] == "") {
												$cat_slug = $cat_rows['cat_name'];
											}
											else {
												$cat_slug = $cat_rows['cat_slug'];
											}
											echo "<option style='text-transform:capitalize;' value='$cat_slug'>$cat_rows[cat_name]</option>";
										}
									 ?>
								</select>
							</div>	
							<div class="form-group">
								<label for="item_qty">Quantity</label>
								<input type="number" name="item_qty" class="form-control" placeholder="Item quantity" required>
							</div>
							<div class="form-group">
								<label for="item_cost">Cost Price</label>
								<input type="number" name="item_cost" class="form-control" placeholder="Supply price" required>
							</div>
							<div class="form-group">
								<label for="item_price">Item Price</label>
								<input type="number" name="item_price" class="form-control" placeholder="Item price" required>
							</div>
							<div class="form-group">
								<label for="item_discount">Item Discount</label>
								<input type="number" name="item_discount" class="form-control" placeholder="Item discount" required>
							</div>
							<div class="form-group">
								<label for="item_delivery_charges">Delivery Charges</label>
								<input type="number" name="item_delivery_charges" class="form-control" placeholder="Delivery charges" required>
							</div>
							<label for="product_image">Item Image</label>
							<div class="custom-file">
								<input type="file" id="product_image" name="item_image" class="custom-file-input" required>
								<label class="custom-file-label" for="product_image">Upload Image</label>
							</div>
							<br><br>
							<div class="form-group">
								<button type="submit" name="add_item" class="btn btn-success btn-block">Add Item</button>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
					</div>
				</div>
			</div>
		</div>
		<div id="">
			<!--Area to get the processed item list data -->
			<table class="table table-bordered table-striped">
			<thead>
				<tr class="item-header text-center">
					<th>S.no</th>
					<th>Item Name</th>
					<th>Thumbnail</th>
					<th>Item Description</th>
					<th>Item Category</th>
					<th>Item Quatity</th>
					<th>Cost Price</th>
					<th>Item Discount</th>
					<th>Selling Price</th>
					<th>Item Delivery</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody id="get_item_list_data" >

			</tbody>
		</table>
		</div>
	</div><br><br>

	<?php include "includes/footer.php";  ?>
</body>
</html>