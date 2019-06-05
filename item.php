<?php include 'includes/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Product Details</title>
	<meta charset="utf-8">
	<meta name="viewpoint" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/bootstrap.min.css">

	<link rel="stylesheet" href="css/all.css">

	<link rel="stylesheet" href="css/custom.css">

	<script src="js/jquery.js" type="text/javascript"></script>
	<script src="js/popper.1.14.3.min.js" type="text/javascript"></script>

	<script src="js/bootstrap.min.js"></script>
</head>
<body>
	<?php include 'includes/header.php' ?>

		<div class="container">
			<div class="row">
					<ul class="breadcrumb col-md-12">
						<li class="breadcrumb-item"><a href="index.php">Home</a></li>

						<?php

							//If the variable item_id is found in the URL
						if (isset($_GET['item_id'])) {
							$sql = "SELECT * FROM items WHERE sku='$_GET[item_id]'";
							$run = mysqli_query($conn, $sql);
							while ($rows = mysqli_fetch_assoc($run)) {
								$item_cat = ucwords($rows['item_cat']);
								$chkout_item_id = $rows['sku'];
								echo "

									<li class='breadcrumb-item'><a href='category.php?category=$item_cat'>$item_cat</a></li>
									<li class='breadcrumb-item active'>$rows[item_name]</li>

								";
						?>
					</ul>
			</div> <!-- End of first row -->
			<div class="row">

					<?php		
							echo "

									<div class='col-md-8'>
										<h3 class='product-title'> $rows[item_name] </h3>
											<img src=' $rows[item_image]' class='img-fluid'>
											<h4 class='product-desc-heading'>Description</h4>
											<div class='product-desc'> $rows[item_description] </div>
										</div>

							";

						}
					}
					

					?>

					

				<aside class="col-md-4">
					<a href="cart.php?chkout_item_id=<?php echo $chkout_item_id; ?>" class="btn btn-block">BUY NOW</a>
					<br>
					<ul class="list-group">
						<li class="list-group-item">
							<div class="row">
								<div class="col-md-3"><i style="color: #f48924;" class="fas fa-truck fa-2x"></i></div>
								<div class="col-md-9">Delivery within 5 days</div>
							</div>
						</li>
						<li class="list-group-item">
							<div class="row">
								<div class="col-md-3"><i style="color: #f48924;" class="fas fa-money-bill fa-2x"></i></div>
								<div class="col-md-9">Secure &amp; Reliable Payment</div>
							</div>
						</li>
						<li class="list-group-item">
							<div class="row">
								<div class="col-md-3"><i style="color: #f48924;" class="fas fa-undo fa-2x"></i></div>
								<div class="col-md-9">Free 7 days return if eligible</div>
							</div>
						</li>
						<li class="list-group-item">
							<div class="row">
								<div class="col-md-3"><i style="color: #f48924;" class="fas fa-thumbs-up fa-2x"></i></div>
								<div class="col-md-9">Genuine Product</div>
							</div>
						</li>
					</ul>
				</aside>
		</div> <!-- End of second row -->
		
		<div class="product-title">
			<h2>Related Products</h2>
			<hr>
		</div>
		
		<section class="row">

			<?php
			setlocale(LC_MONETARY, "en_NG.UTF-8");
			//Here we Order the result of select by randomizing them and limiting number of items to return to 4
				$rel_sql = "SELECT * FROM items ORDER BY rand() LIMIT 4";
				$rel_run = mysqli_query($conn, $rel_sql);
				while ($rel_rows = mysqli_fetch_assoc($rel_run)) {
					$discount_price = money_format("%#102n", $rel_rows['item_price'] - $rel_rows['item_discount']);
					$item_price =  $rel_rows['item_price'];
					$item_price = money_format("%#10i", $item_price);
					$item_name = str_replace(" ", "-", $rel_rows['item_name']);
					echo "
							<div class='col-md-3'>
									<div class='col-md-12 single-item noPadding'>
										<a href='item.php?item_id=$rel_rows[sku]&item_name=$item_name'>
											<div class='top'>
												<img src='$rel_rows[item_image]' class='img-fluid'>
											</div>
											<div class='bottom'>
											<h3 class='item-name'> $rel_rows[item_name] </h3>
											<div class='float-right real-price text-muted'><del> $item_price </del></div>
											<div class='clearfix'></div>
											<div class='float-right discount-price'> $discount_price </div>
											</div>
										</a>
									</div>
								</div>
					";
				}
			?>
			
		</section>
		</div>

	<?php include 'includes/footer.php' ?>

</body>
</html>