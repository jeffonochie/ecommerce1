<?php session_start();  
//You add the session to any page associated with one that has the session variable
		include 'includes/db.php';

		//up_chk_qty is the variable sent on the URL argument of xmlhttp.send() in the cart page
		if (isset($_REQUEST['up_chk_qty'])) {
			$up_chk_qty_sql = "UPDATE checkout SET chkout_qty = '$_REQUEST[up_chk_qty]' WHERE chkout_id = '$_REQUEST[up_chk_id]'";
			$up_chk_qty_run = mysqli_query($conn, $up_chk_qty_sql);

		}

		if (isset($_REQUEST['chkout_del_id'])) {
			//variable is passed in ajax open URL
			$chkout_del_sql = "DELETE FROM checkout WHERE chkout_id = '$_REQUEST[chkout_del_id]'";
			$chkout_del_run = mysqli_query($conn, $chkout_del_sql);
		}

		$c = 1;
		$i = 1;
		$sub_total = 0;
		$delivery_charges = 0;
		setlocale(LC_MONETARY, "en_NG.UTF-8");


						echo "
									<table class='table'>
										<thead class='table-dark'>
											<tr>
												<th>SN</th>
												<th>Item Name</th>
												<th>Quantity</th>
												<th>Delete</th>
												<th>Price</th>
												<th>Total</th>
											</tr>
										</thead>
										<tbody>

						";
		
						

						$chkout_sel_sql = "SELECT * FROM checkout c JOIN items i ON c.chkout_item=i.sku WHERE c.chkout_ref = '$_SESSION[ref]'";
						//NOTE becos this is a JOIN, it returns rows from both items and checkout table
						$chkout_sel_run = mysqli_query($conn, $chkout_sel_sql);
						$count = 1;
						while($chkout_sel_rows = mysqli_fetch_assoc($chkout_sel_run)) {

							$discounted_price = ($chkout_sel_rows['item_price'] - $chkout_sel_rows['item_discount']);
							$line_total = $discounted_price * $chkout_sel_rows['chkout_qty'];
							$sub_total += $line_total;
							$delivery_charges = 1500;
							$total = $sub_total + $delivery_charges;
							$vat = 0.05 * $total;
							$line_total = money_format("%#2n", $line_total);
							$discounted_price = money_format("%#2n", $discounted_price);
							
							
							
							echo "
									<tr>
										<td>$count</td>
										<td>$chkout_sel_rows[item_name]</td>
										
								"; ?>

										<!-- COMMENT here we call a function once any click or tab away even happens after entering a quantity, this.value holds the value typed into textbox -->

										<td><input type="number" style="width:50px;" min="1" value="<?php echo $chkout_sel_rows['chkout_qty']; ?>" onblur="up_chkout_qty_func(this.value, <?php echo $chkout_sel_rows['chkout_id']; ?>);"></td>
										<td><a href="#" onclick="chkout_del_func(<?php echo $chkout_sel_rows['chkout_id']; ?>);"><i style="color:#de0f17"; class="fas fa-trash-alt"></i></a></td>
					<?php	echo "					
										<td><strong>$discounted_price</strong></td>
										<td><strong>$line_total</strong></td>
										
										
									</tr>

							";
									$count++;
						}

				//we make grandtotal a session so that we can receive its value in cart.php and be able to send it to the DB. 
						$_SESSION['grand_total'] = $grand_total = $total + $delivery_charges + $vat;
						$vat = money_format("%#2n", $vat);
						$delivery_charges = money_format("%#2n", $delivery_charges);
						$sub_total = money_format("%#2n", $sub_total);
						$grand_total = money_format("%#2n", $grand_total);

						echo "
								</tbody>
							</table>

							<table class='table'>
								<thead class='text-center'>
									<tr>
										<th colspan='2'>Order Summary</th>
										</tr>
								</thead> 
								<tbody>		
										<tr>
											<td>Sub-Total</td>
											<td class='text-right'><strong>$sub_total</strong></td>
										</tr>
										<tr>
											<td>Shipping Fee</td>
											<td class='text-right'><strong>$delivery_charges</strong></td>
										</tr>
										<tr>
											<td>Tax/VAT (5%)</td>
											<td class='text-right'><strong>$vat</strong></td>
										</tr>
										<tr>
											<td>GRAND TOTAL</td>
											<td class='text-right'><strong>$grand_total</strong></td>
										</tr>
									</tbody>
								</table>
							
						";  
?>

