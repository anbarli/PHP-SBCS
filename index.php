<?php
// Report All PHP Errors
error_reporting(E_ALL);

// Session start
session_start();

// Currency symbol, you can change it
$currency = "$";

$msg = "";
$v = "1.6.1";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PHP Session Based Cart System is pretty simple and fast way for listing small amount of products. This script doesn't include any payment method or payment page. This script lists manually added products, you can add that products to your shopping cart, remove them, change quantity via sessions.">
    <meta name="author" content="anbarli.org">

    <title>PHP-SBCS / Session Based Cart System</title>

    <!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

	<script language="Javascript">
	<!-- Allows only numeric chars -->
	function isNumberKey(evt)
	{
		var charCode=(evt.which)?evt.which:event.keyCode
		if(charCode>31&&(charCode<48||charCode>57))
		return false;return true;
	}
	</script>

	<style>
	.quantity { width: 20px; float: left; margin-right: 10px; height: 23px; font-size: 12px; padding: 5px; }
	</style>

  </head>

  <body>

	<?php
	// Add item to cart
	if (empty($_POST['item']) || empty($_POST['price']) || empty($_POST['quantity']))
	{ } else {

		# Take values
		$SBCSprice = $_POST['price'];
		$SBCSitem = $_POST['item'];
		$SBCSquantity = $_POST['quantity'];
		$SBCSuniquid = rand();
		$SBCSexist = false;
		$SBCScount = 0;
		// If SESSION Generated?
		if($_SESSION['SBCScart']!="")
		{
			// Look for item
			foreach($_SESSION['SBCScart'] as $SBCSproduct)
			{
				// Yes we found it
				if($SBCSitem == $SBCSproduct['item']) {
					$SBCSexist = true;
					break;
				}
				$SBCScount++;
			}
		}
		// If we found same item
		if($SBCSexist)
		{
			// Update quantity
			$_SESSION['SBCScart'][$SBCScount]['quantity'] += $SBCSquantity;
			// Write down the message and then we open in modal at the bottom
			$msg = "
			<script type=\"text/javascript\">
				$(document).ready(function(){
					$('#myDialogText').text('".$SBCSitem." quantity updated..');
					$('#modal-cart').modal('show');
				});
			</script>
			";

		} else {

			// If we do not found, insert new
			$SBCSmycartrow = array(
				'item' => $SBCSitem,
				'unitprice' => $SBCSprice,
				'quantity' => $SBCSquantity,
				'id' => $SBCSuniquid
			);

			// If session not exist, create
			if (!isset($_SESSION['SBCScart']))
				$_SESSION['SBCScart'] = array();

			// Add item to cart
			$_SESSION['SBCScart'][] = $SBCSmycartrow;

			// Write down the message and then we open in modal at the bottom
			$msg = "
			<script type=\"text/javascript\">
				$(document).ready(function(){
					$('#myDialogText').text('".$SBCSitem." added to your cart');
					$('#modal-cart').modal('show');
				});
			</script>
			";

		}
	}

	// Clear cart
	if(isset($_GET["clear"]))
	{
		session_unset();
		session_destroy();
		// Write down the message and then we open in modal at the bottom
		$msg = "
		<script type=\"text/javascript\">
			$(document).ready(function(){
				$('#myDialogText').text('Your cart is empty now..');
				$('#modal-cart').modal('show');
			});
		</script>
		";
	}

	// Remove item from cart (Updating quantity to 0)
	$remove = isset($_GET['remove']) ? $_GET['remove'] : '';
	if($remove!="")
	{
		$_SESSION['SBCScart'][$_GET["remove"]]['quantity'] = 0;
	}
	?>

    <div class="navbar navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">PHP-SBCS</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
			<li class="active"><a href="/" target="blank">Who Am I</a></li>
			<li class="active"><a href="https://github.com/ganbarli/PHP-SBCS" target="blank">GitHub Project Page</a></li>
          </ul>
        </div><!-- /.nav-collapse -->
      </div><!-- /.container -->
    </div><!-- /.navbar -->

    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-8">
          <p class="pull-right visible-xs">
            <button type="button" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-shopping-cart"></span> My Cart</button>
          </p>
          <div class="jumbotron">
            <p>PHP Session Based Cart System is pretty simple and fast way for listing small amount of products. This script lists manually added products, you can add that products to your shopping cart, remove them, change quantity via sessions. This script doesn't include any payment method or payment page.</p>
          </div><!-- /.jumbotron -->
          <div class="col-sm-13">
			<?php if(isset($_GET["pay"])) { ?>
			<div class="panel panel-success">
			  <div class="panel-heading"><span class="glyphicon glyphicon-shopping-cart"></span> Well done!</div>
			  <div class="panel-body">
				Payment options for <b><?php echo $_POST["payment"];?></b>, here you can code, or simply change the forms action to another script page.<br><br>
				If you wish, you can write session variables into database (do not forget to clean the variables, for example you can use mysql_real_escape_string) or simply you can mail the form values. After  And then destroy & unset the session "SBCScart".
				<br><br>
				<b>Order Details</b>
				<br><br>
				<?php echo $_POST["OrderDetail"];?>
			  </div>
			</div><!-- /.panel -->
			<?php } ?>
			<!-- Products Group -->
			<div class="panel panel-default">
			  <div class="panel-heading"><span class="glyphicon  glyphicon-cutlery"></span> Products Group</div>
			  <ul class="list-group">
				<!-- Product 1 -->
				<li class="list-group-item">
					<form action="?" method="post">
						<input type="submit" name="ok" value="+" class="btn btn-success btn-xs">
						<input class="form-control quantity" name="quantity" type="text" onkeypress="return isNumberKey(event)" maxlength="2" value="1"> Product 1
						<span class="pull-right">1.10 <?php echo $currency;?></span>
						<input type="hidden" name="item" value="Product 1" />
						<input type="hidden" name="price" value="1.10" />
					</form>
				</li>
				<!-- Product 2 -->
				<li class="list-group-item">
					<form action="?" method="post">
						<input type="submit" name="ok" value="+" class="btn btn-success btn-xs">
						<input class="form-control quantity" name="quantity" type="text" onkeypress="return isNumberKey(event)" maxlength="2" value="1"> Product 2
						<span class="pull-right">1.20 <?php echo $currency;?></span>
						<input type="hidden" name="item" value="Product 2" />
						<input type="hidden" name="price" value="1.20" />
					</form>
				</li>
				<!-- Product 3 -->
				<li class="list-group-item">
					<form action="?" method="post">
						<input type="submit" name="ok" value="+" class="btn btn-success btn-xs">
						<input class="form-control quantity" name="quantity" type="text" onkeypress="return isNumberKey(event)" maxlength="2" value="1"> Product 3
						<span class="pull-right">1.30 <?php echo $currency;?></span>
						<input type="hidden" name="item" value="Product 3" />
						<input type="hidden" name="price" value="1.30" />
					</form>
				</li>
			  </ul>
			</div>
			<!-- // Products Group -->

			<!-- Products List W/Thumbs -->
			<div class="row">
				<!-- Product 4 -->
				<div class="col-xs-6 col-md-3">
					<div class="thumbnail text-center">
						<img src="https://placehold.it/150x150" class="img-responsive" alt="Product 4">
						<div class="caption text-center">
							<h3>Product 4</h3>
							<span class="label label-warning">2.10 <?php echo $currency;?></span>
						</div>
						<form action="?" method="post">
							<div class = "input-group">
							<input class="form-control" name="quantity" type="text" onkeypress="return isNumberKey(event)" maxlength="2" value="1">
							<span class = "input-group-btn"><input type="submit" class="btn btn-success" type="button" value="Add To Basket"></span>
							</div>
							<input type="hidden" name="item" value="Product 4" />
							<input type="hidden" name="price" value="2.10" />
						</form>
					</div>
				</div>
				<!-- Product 5 -->
				<div class="col-xs-6 col-md-3">
					<div class="thumbnail text-center">
						<img src="https://placehold.it/150x150" class="img-responsive" alt="Product 4">
						<div class="caption text-center">
							<h3>Product 5</h3>
							<span class="label label-warning">2.20 <?php echo $currency;?></span>
						</div>
						<form action="?" method="post">
							<div class = "input-group">
							<input class="form-control" name="quantity" type="text" onkeypress="return isNumberKey(event)" maxlength="2" value="1">
							<span class = "input-group-btn"><input type="submit" class="btn btn-success" type="button" value="Add To Basket"></span>
							</div>
							<input type="hidden" name="item" value="Product 5" />
							<input type="hidden" name="price" value="2.20" />
						</form>
					</div>
				</div>
				<!-- Product 6 -->
				<div class="col-xs-6 col-md-3">
					<div class="thumbnail text-center">
						<img src="https://placehold.it/150x150" class="img-responsive" alt="Product 4">
						<div class="caption text-center">
							<h3>Product 6</h3>
							<span class="label label-warning">2.30 <?php echo $currency;?></span>
						</div>
						<form action="?" method="post">
							<div class = "input-group">
							<input class="form-control" name="quantity" type="text" onkeypress="return isNumberKey(event)" maxlength="2" value="1">
							<span class = "input-group-btn"><input type="submit" class="btn btn-success" type="button" value="Add To Basket"></span>
							</div>
							<input type="hidden" name="item" value="Product 6" />
							<input type="hidden" name="price" value="2.30" />
						</form>
					</div>
				</div>
				<!-- Product 7 -->
				<div class="col-xs-6 col-md-3">
					<div class="thumbnail text-center">
						<img src="https://placehold.it/150x150" class="img-responsive" alt="Product 4">
						<div class="caption text-center">
							<h3>Product 7</h3>
							<span class="label label-warning">2.40 <?php echo $currency;?></span>
						</div>
						<form action="?" method="post">
							<div class = "input-group">
							<input class="form-control" name="quantity" type="text" onkeypress="return isNumberKey(event)" maxlength="2" value="1">
							<span class = "input-group-btn"><input type="submit" class="btn btn-success" type="button" value="Add To Basket"></span>
							</div>
							<input type="hidden" name="item" value="Product 7" />
							<input type="hidden" name="price" value="2.40" />
						</form>
					</div>
				</div>
			</div>
			<!-- // Products List W/Thumbs -->

          </div><!--/row-->
        </div><!--/span-->

        <div class="col-xs-6 col-sm-4" id="sidebar" role="navigation">
          <div class="sidebar-nav">
			<?php
			// If cart is empty
			if (!isset($_SESSION['SBCScart']) || (count($_SESSION['SBCScart']) == 0)) {
			?>
				<div class="panel panel-default">
				  <div class="panel-heading">
					<h3 class="panel-title"><span class="glyphicon glyphicon-shopping-cart"></span> My Cart</h3>
				  </div>
				  <div class="panel-body">Your cart is empty..</div>
				</div>
			<?php
			// If cart is not empty
			} else {
			?>
				<div class="panel panel-default">
					<div class="panel-heading" style="margin-bottom:0;">
						<h3 class="panel-title"><span class="glyphicon glyphicon-shopping-cart"></span> My Cart</h3>
					</div>
					<div class="table-responsive">
					<table class="table">
						<tr class="tableactive"><th>Product</th><th>Price</th><th>Qty.</th><th>Tot.</th></tr>
						<?php
						// List cart items
						// We store order detail in HTML
						$OrderDetail = '
						<table border=1 cellpadding=5 cellspacing=5>
							<thead>
								<tr>
									<th>Product</th>
									<th>Price</th>
									<th>Quantity</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>';

						// Equal total to 0
						$total = 0;

						// For finding session elements line number
						$linenumber = 0;

						// Run loop for cart array
						foreach($_SESSION['SBCScart'] as $SBCSitem)
						{
							// Don't list items with 0 qty
							if($SBCSitem['quantity']!=0) {

							// For calculating total values with decimals
							$pricedecimal = str_replace(",",".",$SBCSitem['unitprice']);
							$qtydecimal = str_replace(",",".",$SBCSitem['quantity']);

							$pricedecimal = (float)$pricedecimal;
							$qtydecimal = (float)$qtydecimal;
							$qtydecimaltotal = $qtydecimaltotal + $qtydecimal;

							$totaldecimal = $pricedecimal*$qtydecimal;

							// We store order detail in HTML
							$OrderDetail .= "<tr><td>".$SBCSitem['item']."</td><td>".$SBCSitem['unitprice']." ".$currency."</td><td>".$SBCSitem['quantity']."</td><td>".$totaldecimal." ".$currency."</td></tr>";

							// Write cart to screen
							echo
							"
							<tr class='tablerow'>
								<td><a href=\"?remove=".$linenumber."\" class=\"btn btn-danger btn-xs\" onclick=\"return confirm('Are you sure?')\">X</a> ".$SBCSitem['item']."</td>
								<td>".$SBCSitem['unitprice']." ".$currency."</td>
								<td>".$SBCSitem['quantity']."</td>
								<td>".$totaldecimal." ".$currency."</td>
							</tr>
							";

							// Total
							$total += $totaldecimal;

							}
							$linenumber++;
						}

						// We store order detail in HTML
						$OrderDetail .= "<tr><td>Total</td><td></td><td></td><td>".$total." ".$currency."</td></tr></tbody></table>";

						?>
						<tr class='tableactive'>
							<td><a href='?clear' class='btn btn-danger btn-xs' onclick="return confirm('Are you sure?')">Empty Cart</a></td>
							<td class='text-right'>Total</td>
							<td><?php echo $qtydecimaltotal;?></td>
							<td><?php echo $total;?> <?php echo $currency;?></td>
						</tr>
					</table>
					</div>
				</div>
				<!-- // Cart -->

				<!-- Address -->
				<div class="panel panel-default">
				  <div class="panel-heading">
					<h3 class="panel-title"><span class="glyphicon glyphicon-phone-alt"></span> Address</h3>
				  </div>
				  <div class="panel-body">
					<form role="form" method="post" action="?pay">
					  <div class="form-group">
						<label for="inputEmail1">Name</label>
						<div>
						  <input type="text" name="name" class="form-control" id="inputEmail1" placeholder="Name">
						</div>
					  </div>
					  <div class="form-group">
						<label for="inputEmail2">Email</label>
						<div>
						  <input type="email" name="email" class="form-control" id="inputEmail2" placeholder="mail@domain.com">
						</div>
					  </div>
					  <div class="form-group">
						<label for="inputEmail3">Phone</label>
						<div>
						  <input type="text" name="phone" class="form-control" id="inputEmail3" placeholder="Phone" onkeypress="return isNumberKey(event)" >
						</div>
					  </div>
					  <div class="form-group">
						<label for="inputEmail4">Address</label>
						<div>
						  <textarea class="form-control" name="address" id="inputEmail4" style="height:50px;"></textarea>
						</div>
					  </div>
					  <div class="form-group">
						<label for="optionsRadios1">Payment</label>
						<div style="margin-top: 6px;">
							<select class="form-control selectEleman" name="payment">
							  <option value="Credit Card">Credit Card</option>
							  <option value="PayPal">PayPal</option>
							</select>
						</div>
					  </div>
					  <div class="form-group">
						<div>
						  <button type="submit" class="btn btn-success pull-right">Give Order</button>
						</div>
					  </div>
					<input type="hidden" name="total" value="<?php echo $total;?>">
					<input type="hidden" name="OrderDetail" value="<?php echo htmlentities($OrderDetail);?>">
					</form>
				  </div>
				</div>
				<!-- // Address -->

			<?php } # End Cart Listing ?>
          </div><!--/.well -->
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p><a href="https://github.com/ganbarli/PHP-SBCS" target="blank">PHP-SBCS</a> (<?php echo $v; ?>) is coded with <i class="glyphicon glyphicon-heart"></i> in İstanbul, <a href="http://www.turkeydiscoverthepotential.com/" target="blank">Türkiye</a></p>
      </footer>

    </div><!--/.container-->

	<div id="modal-cart" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<p class="text-center" id="myDialogText"></p>
				</div>
			</div>
		</div>
	</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
	<script src="//code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<?php if($msg != "") { echo $msg; } ?>

	<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-928914-3', 'anbarli.org');
	ga('send', 'pageview');

	</script>

  </body>
</html>