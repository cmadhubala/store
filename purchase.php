<?php
	session_start();
	$_SESSION['err'] = 1;
	foreach($_POST as $key => $value){
		if(trim($value) == ''){
			$_SESSION['err'] = 0;
		}
		break;
	}

	if($_SESSION['err'] == 0){
		header("Location: checkout.php");
	} else {
		unset($_SESSION['err']);
	}


	$_SESSION['ship'] = array();
	foreach($_POST as $key => $value){
		if($key != "submit"){
			$_SESSION['ship'][$key] = $value;
		}
	}
	require_once "./functions/database_functions.php";
	// print out header here
	$title = "Purchase";
	require "./template/header.php";
	// connect database
	if(isset($_SESSION['cart']) && (array_count_values($_SESSION['cart']))){
?>
	<table class="table">
		<tr>
			<th>Item</th>
			<th>Price</th>
	    	<th>Quantity</th>
	    	<th>Total</th>
	    </tr>
	    	<?php
			    foreach($_SESSION['cart'] as $isbn => $qty){
					$conn = db_connect();
					$book = mysqli_fetch_assoc(getBookByIsbn($conn, $isbn));
			?>
		<tr>
			<td><?php echo $book['book_title'] . " by " . $book['book_author']; ?></td>
			<td><?php echo "Rs" . $book['book_price']; ?></td>
			<td><?php echo $qty; ?></td>
			<td><?php echo "Rs" . $qty * $book['book_price']; ?></td>
		</tr>
		<?php } ?>
		<tr>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th><?php echo $_SESSION['total_items']; ?></th>
			<th><?php echo "Rs" .( $_SESSION['total_price']+0.00); ?></th>
		</tr>
		<tr>
			<td>Shipping</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>20.00</td>
		</tr>
		<tr>
			<th>Total Including Shipping</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th><?php echo "Rs" . ($_SESSION['total_price'] + 20.00); ?></th>
		</tr>
	</table>

        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
              	<form method="post" action="PaytmKit/pay.php">
              	<button type="reset" class="btn btn-default">Cancel</button>
              	<button type="submit" class="btn btn-primary">Purchase</button>
              </form>
            </div>
        </div>
    </form>
	<p class="lead">Please press Purchase to confirm your purchase, or Continue Shopping to add or remove items.</p>
<?php
	} else {
		echo "<p class=\"text-warning\">Your cart is empty! Please make sure you add some books in it!</p>";
	}
	if(isset($conn)){ mysqli_close($conn); }
	require_once "./template/footer.php";
?>