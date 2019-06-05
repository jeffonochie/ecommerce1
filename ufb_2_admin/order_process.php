<?php
	include "../includes/db.php";
	setlocale(LC_MONETARY, "en_NG.UTF-8");

	if (isset($_REQUEST["ord_status"])) {
		$up_sql = "UPDATE orders SET order_status = '$_REQUEST[ord_status]' WHERE order_id = '$_REQUEST[ord_id]'";
		$up_run = mysqli_query($conn, $up_sql);
	}

	if (isset($_REQUEST["return_status"])) {
		$up_sql = "UPDATE orders SET order_return_status = '$_REQUEST[return_status]' WHERE order_id = '$_REQUEST[order_id]'";
		mysqli_query($conn, $up_sql);
	}

	?>
<table class='table table-striped table-bordered'>
				<thead>
					<tr class='item-header'>
							<th>SN</th>
							<th>Buyer Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th>State</th>
							<th>Delivery Address</th>
							<th>Order Ref</th>
							<th class='text-right'>Total</th>
							<th>Status</th>
							<th>Return Status</th>
					</tr>
				</thead>
			<tbody>
<?php
	$count = 1;
	$orders_sql = "SELECT * FROM orders";
	$orders_run = mysqli_query($conn, $orders_sql);
	while ($orders_rows = mysqli_fetch_assoc($orders_run)) {
		$buyer_name = $orders_rows["order_firstname"]." ".$orders_rows["order_lastname"];
		$order_total = money_format("%i", $orders_rows["order_total"]);
		if ($orders_rows["order_status"] == 0) {
			$status_btn_class = "btn-warning";
			$status_btn_value = "Pending";
		}else {
			$status_btn_class = "btn-success";
			$status_btn_value = "Processed";
		}
		if ($orders_rows["order_return_status"] == 0) {
			$return_btn_class = "btn-primary";
			$return_btn_value = "No Return";
		}else {
			$return_btn_class = "btn-danger";
			$return_btn_value = "Returned";
		}
		echo "
					<tr>
							<td>$count</td>
							<td>$buyer_name</td>
							<td>$orders_rows[order_email]</td>
							<td>$orders_rows[order_contact]</td>
							<td>$orders_rows[order_state]</td>
							<td>$orders_rows[order_delivery_address]</td>
							<td>
								<button class='btn btn-info' data-toggle='modal' data-target='#order_info_modal$orders_rows[order_id]'>$orders_rows[order_ref]</button>

								<div class='modal fade' id='order_info_modal$orders_rows[order_id]'>
									<div class='modal-dialog'>
										<div class='modal-content'>
											<div class='modal-header'>Order Details</div>
											<div class='modal-body'>
												<table class='table'>
													<thead>
														<tr>
															<th>SN</th>
															<th>Name</th>
															<th>Qty</th>
															<th>Price</th>
															<th>Sub-Total</th>
														</tr>
													</thead>
													<tbody>		";

														$chkCount = 1;
														$chk_sql = "SELECT * FROM checkout c JOIN items i ON c.chkout_item = i.sku WHERE c.chkout_ref = '$orders_rows[order_ref]'";
														$chk_run = mysqli_query($conn, $chk_sql);
														while ($chk_rows = mysqli_fetch_assoc($chk_run)) {
															$sub_total = $orders_rows["order_total"] * $chk_rows["chkout_qty"];
															//this is done incase an item has been deleted from the DB
															if ($chk_rows["item_name"]=="") {
																$item_name = "Sorry Item Deleted";
															}else {
																$item_name = $chk_rows["item_name"];
															}
															echo "
																	<tr>
																		<td>$chkCount</td>
																		<td>$item_name</td>
																		<td>$chk_rows[chkout_qty]</td>
																		<td>$chk_rows[item_price]</td>
																		<td>$sub_total</td>
																	</tr>
															";
															$chkCount++;
														}
														
									echo "
													</tbody>
												</table>
												<table class='table'>
													<thead>
														<tr>
															<th colspan='2' class='text-center'><h4>Order Summary</h4></th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td><h5>Grand Total</h5></td>
															<td class='text-right'>1234567</td>
														</tr>
													</tbody>
												</table>
											</div>
											<div class='modal-footer'></div>
										</div>
									</div>
								</div>
							</td>
							<td class='text-right'>$order_total</td> 
							<td>
								<button onclick='order_status($orders_rows[order_id],$orders_rows[order_status]);' class='btn $status_btn_class btn-block text-white'>$status_btn_value</button></td>
							<td> 
							"; ?>
							<!-- In the onclick function, if the 2 parameters are separated using just a comma(,) without changing the data type of the comma (', ') and concatinating with the two parameter, eg 10 will be returned to the function instead of 1,0  -->
								<button onclick="order_return_status(<?php echo $orders_rows['order_id'].", ".$orders_rows['order_return_status']; ?>);" class="btn btn-block text-white <?php echo $return_btn_class; ?>"><?php echo $return_btn_value; ?></button>
	<?php		echo "		</td>
						</tr>
		";
		$count++;
	}
  ?>
  </tbody>
</table>
