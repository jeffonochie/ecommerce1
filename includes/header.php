<nav class="navbar navbar-expand-md navbar-light bg-light sticky-top justify-content-between">
	
				<div class="navbar-header">
					<a class="navbar-brand" href="index.php"><img src="../img/exchangepoint_logo.png" alt="UfitBuy" width="117px" height="34px"></a>
				</div>
				
				<!-- Toggler/Collapsible Button -->
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar" aria-controls="collapsibleNavbar" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					
			<!-- Navbar Links ml means margin-left-->
			<div class="collapse navbar-collapse ml-5" id="collapsibleNavbar">
				<ul class="navbar-nav ml-5 mt-2 mt-lg-0">
					<li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
					<!-- Dropdown Menu -->
					<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >Men</a>
						<div class="dropdown-menu" aria-labelledby="navbardrop">
							<a class="dropdown-item" href="#">School Portal</a>
							<a class="dropdown-item" href="#">Zenter</a>
							<a class="dropdown-item" href="#">xpDOC</a>
							<a class="dropdown-item" href="#">RetailPOS</a>
							<a class="dropdown-item" href="#">Mobinfo</a>
							<a class="dropdown-item" href="#">ExchangeLMS</a>
						</div>
					</li>
					<?php

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
							echo "<li class='nav-item'><a class='nav-link' href='category.php?category=$cat_slug'> $cat_name </a></li>";
						}

					?>
					<li class="nav-item"><a class="nav-link" href="#">Contact Us</a></li>
				</ul>
				
			</div>
			<form class="form-inline" action="/action_page.php">
				<div class="input-group">
					<input class="form-control  my-2 my-sm-2" type="search" placeholder="Search" aria-label="Search">
					<div class="input-group-btn">
						<button class="btn btn-outline-primary my-2 my-sm-2" type="submit"><i class="fas fa-search"></i></button>
					</div>
				</div>
			</form>
				<ul class="navbar-nav navbar-right">
					<li class="nav-item"><a class="nav-link" href="#">Sign Up</a></li>
					<li class="nav-item"><a class="nav-link" href="#">Sign In</a></li>
				</ul>
			
		</nav>