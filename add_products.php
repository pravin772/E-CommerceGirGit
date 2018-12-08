<?php
	session_start();
	require_once 'db.php';
	$results = $con->query("SELECT * FROM products ORDER BY product_id");
	$errors = array();

	// Edit products
	
	if(isset($_GET['edit']) && !empty($_GET['edit'])) {
		$edit_id = (int)$_GET['edit'];
		$edit_id =  mysqli_real_escape_string($con,$edit_id);
		$edit_result = $con->query("SELECT * FROM products WHERE product_id = '{$edit_id}'");
		$eProduct = mysqli_fetch_assoc($edit_result);
	}
	
	// Delete product
	
	if(isset($_GET['delete']) && !empty($_GET['delete'])) {
		echo "<div class='wait overlay'><div class='loader'></div></div>";
		$delete_id = (int)$_GET['delete'];
		$delete_id =  mysqli_real_escape_string($con,$delete_id);
		echo "<div class='wait overlay'><div class='loader'></div></div>";
		$con->query("DELETE FROM products WHERE product_id = '{$delete_id}'");
		header("Location: add_products.php");
	}

	if(isset($_POST['add_submit'])) {
		$product_id = mysqli_real_escape_string($con,$_POST['product_id']);
		// Check if product is blank
		if($product_id == '') {
			$errors[] .= 'You must enter a product!';
		}
		// Check if product exist in database
		$sql = "SELECT * FROM products WHERE product_id = '{$product_id}'";
		if(isset($_GET['edit'])) {
			$sql = "SELECT * FROM products WHERE product_id = '{$product_id}'";
		}
		$result = $con->query($sql);
		$count = mysqli_num_rows($result);
		if($count > 0) {
			$errors[] .= $product_id.' already exist. Please choose another product.';
		}
		// Display errors
		if(!empty($errors)) {
			echo "<div class='error'>Please fix the following errors:\n<ul>";
			foreach ($errors as $error)
				echo "<li>$error</li>\n";

			echo "</ul></div>";
		} else {
			// Add product to database
			$sql = "INSERT INTO products (product_id) VALUES ('{$product_id}')";
			if(isset($_GET['edit'])) {
				echo "<div class='wait overlay'><div class='loader'></div></div>";
				$sql = "UPDATE product SET product_id = '{$product_id}'";
			}
			$con->query($sql);
			header('Location: add_products.php');
		}
	}
?>

<!DOCTYPE html>
<html>
		
		<head>
		<meta charset="UTF-8">
		<title>The GirGit</title>
		<link rel="stylesheet" href="css/bootstrap.min.css"/>
		<script src="js/jquery2.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="main.js"></script>
		<script>function myFunction() {
			var x = document.getElementById("myDIV");
			if (x.style.display === "none") {
				x.style.display = "block";
			} else {
				x.style.display = "none";
			}
		}</script>
		<link rel="stylesheet" type="text/css" href="style.css">
		<style></style>
	</head>
<body>
<div class="wait overlay">
	<div class="loader"></div>
</div>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">	
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapse" aria-expanded="false">
					<span class="sr-only">navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="#" class="navbar-brand">The GirGit</a>
			</div>
		<div class="collapse navbar-collapse" id="collapse">
			<ul class="nav navbar-nav">
				<li><a href="index.php"><span class="glyphicon glyphicon-home"></span>&nbsp Home</a></li>
				<li><a href="index.php"><span class="glyphicon glyphicon-modal-window"></span>&nbsp Product</a></li>
				<li><a href="add_products.php"><span class="glyphicon glyphicon-modal-stat"></span>&nbsp About</a></li>
			</ul>
			<form class="navbar-form navbar-left">
		        <div class="form-group">
		          <input type="text" class="form-control" placeholder="Search" id="search">
		        </div>
		        <button type="submit" class="btn btn-primary" id="search_btn"><span class="glyphicon glyphicon-search"></span></button>
		     </form>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-shopping-cart"></span>&nbsp Cart &nbsp<span class="badge">0</span></a>
					<div class="dropdown-menu" style="width:400px;">
						<div class="panel panel-success">
							<div class="panel-heading">
								<div class="row">
									<div class="col-md-3">Sr.No</div>
									<div class="col-md-3">Product Image</div>
									<div class="col-md-3">Product Name</div>
									<div class="col-md-3">Price in $.</div>
								</div>
							</div>
							<div class="panel-body">
								<div id="cart_product">
								<!--<div class="row">
									<div class="col-md-3">Sl.No</div>
									<div class="col-md-3">Product Image</div>
									<div class="col-md-3">Product Name</div>
									<div class="col-md-3">Price in $.</div>
								</div>-->
								</div>
							</div>
							<div class="panel-footer"></div>
						</div>
					</div>
				</li>
				<li><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span>&nbsp SignIn</a>
					<ul class="dropdown-menu">
						<div style="width:300px;">
							<div class="panel panel-primary">
								<div class="panel-heading">Login</div>
								<div class="panel-heading">
									<form onsubmit="return false" id="login">
										<label for="email">Email</label>
										<input type="email" class="form-control" name="email" id="email" required/>
										<label for="email">Password</label>
										<input type="password" class="form-control" name="password" id="password" required/>
										<p><br/></p>
										<a href="#" style="color:white; list-style:none;">Forgotten Password</a><input type="submit" class="btn btn-success" style="float:right;">
										</br><a href="customer_registration.php" style="color:white; list-style:none;">Register</a>
									</form>
								</div>
							</div>
						</div>
					</ul>
				</li>
			</ul>
		</div>
		</div>
	</div>	
	<p><br/></p>
	<p><br/></p>
	<p><br/></p>
	<h2 class="text-center">Products</h2>
<hr>

<div class="text-center">
	<form class="form-inline" action="add_products.php<?php echo ((isset($_GET['edit']))?'?Edit='.$edit_id : ''); ?>" method="post">
		<div class="form-group">
			<label for="product"><?php echo ((isset($_GET['edit']))?'Edit' : 'Add A'); ?> Product:</label>
			<?php
				$product_value = '';
				if(isset($_GET['edit'])) {
					$product_value = $eProduct['product_id'];
				} else {
					if(isset($_POST['product_id'])) {
						$product_value = mysqli_real_escape_string($con,$_POST['product_id']);
					}
				}
			?>
			<input class="form-control" type="text" name="product_id" id="product_id" value="<?php echo $product_value; ?>">
			<?php if(isset($_GET['edit'])) : ?>
				<a class="btn btn-default" href="add_products.php">Cancel</a>
			<?php endif; ?>
			<input class="btn btn-success" type="submit" name="add_submit" value="<?php echo ((isset($_GET['edit']))?'Edit' : 'Add'); ?> Product">
			<button class="btn btn-success" name="expand" onclick="myFunction()">Expand</button>
				<div id="myDIV" >
				This is my DIV element.
				</div>
		</div>
	</form>
</div>
<hr>
<table style="" class="panel-body table table-bordered table-striped table-auto table-condensed table-responsive table-hover">
	<thead >
		<th></th>
		<th>Product Id</th>
		<th>Product Categories</th>
		<th>Product Brand</th>
		<th>Product Title</th>
		<th>Product Price</th>
		<th>Product Description</th>
		<th>Product Image Name</th>
		<th>Product Keywords</th>
		<th></th>
	</thead>
	<tbody>
		<?php while($product = mysqli_fetch_assoc($results)) : ?>
		<tr>
			<td><a href="add_products.php?edit=<?php echo $product['product_id']; ?>"><span class="glyphicon glyphicon-edit"></span></a></td>
			<td><?php echo $product['product_id']; ?></td>
			<td><?php echo $product['product_cat']; ?></td>
			<td><?php echo $product['product_brand']; ?></td>
			<td><?php echo $product['product_title']; ?></td>
			<td><?php echo $product['product_price']; ?></td>
			<td><?php echo $product['product_desc']; ?></td>
			<td><?php echo $product['product_image']; ?></td>
			<td><?php echo $product['product_keywords']; ?></td>
			<td><a href="add_products.php?delete=<?php echo $product['product_id']; ?>"><span class="glyphicon glyphicon-remove"></span></a></td>
		</tr>
		<?php endwhile; ?>
	</tbody>
</table>	
</body>
</html>
