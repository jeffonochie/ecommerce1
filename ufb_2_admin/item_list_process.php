<?php include "../includes/db.php"; 

	//Recieve an process del_item() here
		
		if (isset($_REQUEST["del_item_id"])) {
			$del_sql = "DELETE FROM items WHERE sku = '$_REQUEST[del_item_id]'";
			mysqli_query($conn, $del_sql);
		}
		


		//Update Item from #edit_modal

		if (isset($_REQUEST['edit_item'])) {
			$edit_name = mysqli_real_escape_string($conn, strip_tags($_REQUEST['item_name']));
			$edit_description = mysqli_real_escape_string($conn, $_REQUEST['item_desc']);
			$edit_category = mysqli_real_escape_string($conn, strip_tags($_REQUEST['item_cat']));
			$edit_qty = mysqli_real_escape_string($conn, strip_tags($_REQUEST['item_qty']));
			$edit_cost = mysqli_real_escape_string($conn, strip_tags($_REQUEST['item_cost']));
			$edit_price = mysqli_real_escape_string($conn, strip_tags($_REQUEST['item_price']));
			$edit_discount = mysqli_real_escape_string($conn, strip_tags($_REQUEST['item_discount']));
			$edit_delivery_charge = mysqli_real_escape_string($conn, strip_tags($_REQUEST['item_delivery']));
			$edit_image = mysqli_real_escape_string($conn, strip_tags($_REQUEST['edit_image']));
				
				$item_update_sql = "UPDATE items SET item_delivery_charge='$edit_delivery_charge' WHERE sku ='$_REQUEST[edit_id]'";
				mysqli_query($conn, $item_update_sql);
					
/* if (mysqli_query($conn, $item_update_sql)) { ?>
			<script>alert("Record Inserted successfully!");</script> 
		<?php }
		else {
			echo "Error " . $item_update_sql . "<br>" . mysqli_error($conn);
		} */
					


		}
?>

<!--<table class="table table-bordered table-striped">
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
			<tbody> -->
				<!-- strip_tags(str) shouldn't be inside a quote (line 46) -->
				<?php
					$count = 1;
					$sel_sql = "SELECT * FROM items";
					$sel_run = mysqli_query($conn, $sel_sql);
					while ($rows = mysqli_fetch_assoc($sel_run)) {
						$discounted_price = $rows['item_price'] - $rows['item_discount'];
						echo "
								<tr>
									<td>$count</td>
									<td><img src='../$rows[item_image]' style='width:60px;'></td>
									<td>$rows[item_name]</td>
									<td><div class='td-height'>"; echo strip_tags($rows['item_description']); echo "</div></td>
									<td>$rows[item_cat]</td>
									<td>$rows[item_qty]</td>
									<td>$rows[item_cost]</td>
									<td>$rows[item_discount]</td>
									<td>$discounted_price ($rows[item_price])</td>
									<td>$rows[item_delivery_charge]</td>
									<td>

										<div class='dropdown'>
											<button type='button' class='btn btn-danger dropdown-toggle btn-custom' data-toggle='dropdown'>Actions</button>
											<ul class='dropdown-menu'> 
												<li><a class='dropdown-item' data-toggle='modal' href='#edit_modal$rows[sku]' >Edit</a></li> 
	<!--We added some view code of the JS so that the <a> tag will not go to a link when clicked, rather it runs the JS function onclick() -->
												<li><a class='dropdown-item' href='javascript:;' onclick='del_item($rows[sku]);'>Delete</a></li>
									
											</ul>

										</div>
				
				<div class='modal fade' id='edit_modal$rows[sku]' tabindex='-1' role='dialog' aria-labelledby='edit_modal_title$rows[sku]' data-backdrop='static'>
					<div class='modal-dialog' role='document'>
						<div class='modal-content'>
							<div class='modal-header bg-success'>
								<h4 class='modal-title text-white' id='edit_modal_title$rows[sku]'>Edit Item</h4>
								<button type='button' class='close text-white' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
							</div>
							<div class='modal-body'>	"; ?>
								<form onsubmit="edit_items(<?php echo $rows['sku']; ?>)" id="edit_form<?php echo $rows['sku'];?>">
				<?php echo "					<div class='form-group'>
										<label>Name</label>
										<input type='text' id='edit_item_name$rows[sku]' class='form-control' value='$rows[item_name]' required> 
									</div>  "; ?>
									
									<!--I have remove the below form element from the echo/php bcos while inside the complete data does not show on the modal when it's fired up for editing-->
									<div class='form-group'>
										<label>Description</label>
										<textarea required id="edit_item_description<?php echo $rows['sku'];?>" class="form-control" value=<?php echo $rows['item_description'];?> ></textarea>
									</div>
		<?php echo "							<div class='custom select'>
										<label>Category</label>
										<select id='edit_item_category$rows[sku]' class='custom-select' required>
											<option>Select Category</option>

											";
											
												$cat_sql = "SELECT * FROM item_cat";
												$cat_run = mysqli_query($conn, $cat_sql);
												while ($cat_rows = mysqli_fetch_assoc($cat_run)) {
													$cat_name = ucwords($cat_rows['cat_name']);
													if ($cat_rows['cat_slug'] == "") {
														$cat_slug = $cat_rows['cat_name'];
													}
													else {
														$cat_slug = $cat_rows['cat_slug'];
													}
													if ($cat_slug == $rows['item_cat']) { //check if user supplied value is in the DB
														echo "<option selected value='$cat_slug'>$cat_name</option>";
													}else {
														echo "<option value='$cat_slug'>$cat_name</option>";
													}
												}
								
				echo "					</select>
									</div>	
									<div class='form-group'>
										<label>Quantity</label>
										<input type='number' id='edit_item_qty$rows[sku]' class='form-control' value='$rows[item_qty]' required>
									</div>
									<div class='form-group'>
										<label>Cost Price</label>
										<input type='number' id='edit_item_cost$rows[sku]' class='form-control' value='$rows[item_cost]' required>
									</div>
									<div class='form-group'>
										<label>Item Price</label>
										<input type='number' id='edit_item_price$rows[sku]' class='form-control' value='$rows[item_price]' required>
									</div>
									<div class='form-group'>
										<label>Item Discount</label>
										<input type='number' id='edit_item_discount$rows[sku]' class='form-control' value='$rows[item_discount]' required>
									</div>
									<div class='form-group'>
										<label>Delivery Charges</label>
										<input type='number' id='edit_item_delivery_charges$rows[sku]' class='form-control' value='$rows[item_delivery_charge]' required>
									</div>
									<div class='form-group'>
										<input type='hidden' id='edit_update_item_id$rows[sku]' value='$rows[sku]'>
										<input type='hidden' id='edit_item_image$rows[sku]' value='$rows[item_image]'>  ";	?>

										<button type='submit' class='btn btn-success btn-block'>Save Changes</button>
									</div>
								</form> <!--#edit_form ending-->
							</div>
							<div class='modal-footer'>
								<button data-dismiss='modal' class='btn btn-secondary'>Close</button>
							</div>
						</div>
					</div>
				</div>

									</td>
								</tr>
								
			<?php					
								
								$count++;
								}
						
					
		?>
	<!--		</tbody>
		</table> -->
			<!--since the modal cannot work inside a dropdown, we create it here
			//We also remove enctype because we are not editing the image -->
		 