<?php include 'includes/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>UfitBuy - Online Shopping</title>
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
	
	<?php include 'includes/header.php';?>

		<div class="container">
			<div class="row">

				<?php
					setlocale(LC_MONETARY, "en_NG.UTF-8");

					$sql = "SELECT * FROM items";
					$run = mysqli_query($conn, $sql);
					while ($rows = mysqli_fetch_assoc($run)) {
						
						$discount_price = money_format("%#102n", $rows['item_price'] - $rows['item_discount']);
						$item_price =  $rows['item_price'];
						$item_price = money_format("%#10i", $item_price);
						$item_id = $rows['sku'];
						echo "

								<div class='col-md-3'>
									<div class='col-md-12 single-item noPadding'>
										<a href='item.php?item_id=$item_id'>
											<div class='top'>
												<img src='$rows[item_image]' class='img-fluid'>
											</div>
											<div class='bottom'>
											<h3 class='item-name'> $rows[item_name] </h3>
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


		</div>
	</div>
	<div class="clearfix"></div>
	<!-- FOOTER -->
	<?php include 'includes/footer.php'; ?>
</body>
</html>