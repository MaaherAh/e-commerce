<?php

session_start();
$pageTitle = "Categories";
if(isset($_SESSION["username"])) {
	include "init.php";
	
	$do = isset($_GET['do']) ? $_GET['do'] : 'main';

	if($do == "main") {				// Main page ============================

		// Getting all members into the table of the members.
		$stmt = $conn->prepare("SELECT items.*, categories.Name AS category_name, users.Username
								FROM items
								INNER JOIN categories ON categories.ID = items.Category_ID
								INNER JOIN users ON users.ID = items.Member_ID
								ORDER BY items.ID");
		$stmt->execute();
		$items_rows = $stmt->fetchAll();
		?>
			<h1 class="text-center">Manage Items</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
							<td>ID</td>
							<td>Name</td>
							<td>Price</td>
							<td>Country</td>
							<td>Quality</td>
							<td>Rating</td>
							<td>Insert Date</td>
							<td>Category</td>
							<td>Memeber</td>
							<td>Control</td>
						</tr>
						<?php
							foreach($items_rows as $items_row) {
								echo "<tr>";
									echo "<td>" . $items_row['ID'] . "</td>";
									echo "<td>" . $items_row['Name'] . "</td>";
									echo "<td>" . $items_row['Price'] . "</td>";
									echo "<td>" . $items_row['Country'] . "</td>";
									echo "<td>";  if($items_row['Quality'] == 1){echo 'Used';}else{echo 'New';}  echo "</td>";
									echo "<td>" . $items_row['Rating'] . "/5</td>";
									echo "<td>" . $items_row['Add_Date'] . "</td>";
									echo "<td>" . $items_row['category_name'] . "</td>";
									echo "<td>" . $items_row['Username'] . "</td>";
									echo "<td>
											<a href='items.php?do=edit&id=" . $items_row['ID'] ."' class='btn btn-success'>Edit</a>
											<a href='items.php?do=delete&id=" . $items_row['ID'] ."' class='btn btn-danger confirm'>Delete</a>";
									echo "</td>";
								echo "</tr>";
							}
						?>
					</table>
				</div>
				<a href='items.php?do=add' class="btn btn-primary" style="margin-bottom: 15px"><i class="fa fa-plus"></i> Add item</a>
			</div>
		<?php
	}elseif($do == "edit") { 		// Edit page ============================

		$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

		// Check if a user with a spacific ID exists in the database
		$stmt = $conn -> prepare("SELECT * FROM items WHERE ID = ?");
		$stmt -> execute(array($id));
		$item = $stmt -> fetch();

		$checkItem = checkItem("ID", "items", $id);

		if($checkItem > 0){
			if($_SESSION['groupId'] == 1 || $_SESSION['id'] == $item['Member_ID']){
				?>
					<h1 class="text-center">Edit Item</h1>
					<div class="container">
						<form class="form-horizontal" action="?do=update" method="POST">
							<!-- Start ID -->
								<input type="hidden" name="id" value="<?php echo $id; ?>" />
							<!-- End ID -->
							<!-- Start Name field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Name :</label>
								<div class="col-sm-10 col-md-5">
									<input type="text" name="name" class="form-control" value="<?php echo $item['Name']; ?>" placeholder="Name of the item" required/>
								</div>
							</div>
							<!-- End Name field -->
							<!-- Start Description field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Description :</label>
								<div class="col-sm-10 col-md-5">
									<input type="text" name="description" class="form-control" value="<?php echo $item['Description']; ?>" placeholder="Describe the item" required/>
								</div>
							</div>
							<!-- End Description field -->
							<!-- Start Price field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Price :</label>
								<div class="col-sm-10 col-md-5">
									<input type="text" name="price" class="form-control" value="<?php echo $item['Price']; ?>" placeholder="The price of the item (eg. $100)" required/>
								</div>
							</div>
							<!-- End Price field -->
							<!-- Start Country field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Country :</label>
								<div class="col-sm-10 col-md-5">
									<input type="text" name="country" class="form-control" value="<?php echo $item['Country'] ?>" placeholder="The country the item made in." required/>
								</div>
							</div>
							<!-- End Country field -->
							<!-- Start Quality field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Quality :</label>
								<div class="col-sm-10 col-md-5">
									<select name="quality">
										<option value="1" <?php if($item['Quality'] == 1){echo 'selected';} ?> >Used</option>
										<option value="2" <?php if($item['Quality'] == 2){echo 'selected';} ?> >New</option>

									</select>
								</div>
							</div>
							<!-- End Quality field -->
							<!-- Start Rating field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Rating :</label>
								<div class="col-sm-10 col-md-5">
									<select name="rating">
										<option value="1" <?php if($item['Rating'] == 1){echo 'selected';} ?> >1</option>
										<option value="2" <?php if($item['Rating'] == 2){echo 'selected';} ?> >2</option>
										<option value="3" <?php if($item['Rating'] == 3){echo 'selected';} ?> >3</option>
										<option value="4" <?php if($item['Rating'] == 4){echo 'selected';} ?> >4</option>
										<option value="5" <?php if($item['Rating'] == 5){echo 'selected';} ?> >5</option>
									</select>
								</div>
							</div>
							<!-- End Rating field -->
							<!-- Start Categories field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Category :</label>
								<div class="col-sm-10 col-md-5">
									<select name="category">
										<?php
											$stmt = $conn->prepare("SELECT * FROM categories");
											$stmt->execute();
											$categories = $stmt->fetchAll();

											foreach($categories as $Category) {
												echo "<option value='" . $Category['ID'] . "' ";
													if($item['Category_ID'] == $Category['ID']){echo 'selected';}
													echo ">" . $Category['Name'] . "</optin>";
											}
										?>
									</select>
								</div>
							</div>
							<!-- End Categories field -->
							<!-- Start Users field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">User :</label>
								<div class="col-sm-10 col-md-5">
									<select name="user">
										<?php
											if($_SESSION['groupId'] == 1){
												$stmt = $conn->prepare("SELECT ID, Username FROM users");
												$stmt->execute();
												$users = $stmt->fetchAll();
												foreach($users as $user){
													echo "<option value='" . $user['ID'] . "' ";
														if($item['Member_ID'] == $user['ID']){echo 'selected';}
														echo ">" . $user['Username'] . "</option>";
												}
											}else{
												$stmt = $conn->prepare("SELECT ID, Username FROM users WHERE ID = ?");
												$stmt->execute(array($_SESSION['id']));
												$user = $stmt->fetch();
												echo "<option value='" . $user['ID'] . "'>" . $user['Username'] . "</option>";
											}
										?>
									</select>
								</div>
							</div>
							<!-- End Users field -->
							<!-- Start Add Button field -->
							<div class="form-group form-group-lg">
								<div class="col-sm-10 col-md-5">
									<input type="submit" value="Save" class="btn btn-primary btn-lg" />
								</div>
							</div>
							<!-- End Add Button field -->
						</form>
					</div>
				<?php
			}else{echo "<div class='container alert alert-danger text-center'>Sorry, you could not edit this item, because it's not yours.</div>";}
		}else{echo "<div class='container alert alert-danger text-center'>Sorry, there is no item with such ID.</div>";}
	}elseif($do == "update"){		// Update page ==========================
		echo "<div class='container'>";
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				echo "<h1 class='text-center'>Update Item</h1>";

				$id 			= $_POST['id'];
				$name 			= $_POST['name'];
				$description 	= $_POST['description'];
				$price 			= $_POST['price'];
				$country 		= $_POST['country'];
				$quality 		= $_POST['quality'];
				$rating 		= $_POST['rating'];
				$category 		= $_POST['category'];
				$user 			= $_POST['user'];

				// Update data
				$stmt = $conn->prepare("UPDATE items SET Name=?, Description=?, Price=?, Country=?, Quality=?, Rating=?, Category_ID=?, Member_ID=? WHERE ID=?");
				$stmt->execute(array($name, $description, $price, $country, $quality, $rating, $category, $user, $id));

				$sucMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " records have been updated.</div>";
				homeRedirection($sucMsg, 'back', 4);
			}else {
				$errMsg = "<div class='alert alert-danger'>Sorry you cannot browse this page directly.</div>";
				homeRedirection($errMsg, 'back', '');
			}
		echo "</div>";
	}elseif($do == "add") {			// Add page =============================

			?>
				<h1 class="text-center">Add new Item</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=insert" method="POST">
						<!-- Start Name field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Name :</label>
							<div class="col-sm-10 col-md-5">
								<input type="text" name="name" class="form-control" autocomplete="off" placeholder="Name of the item"/>
							</div>
						</div>
						<!-- End Name field -->
						<!-- Start Description field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Description :</label>
							<div class="col-sm-10 col-md-5">
								<input type="text" name="description" class="form-control" placeholder="Describe the item"/>
							</div>
						</div>
						<!-- End Description field -->
						<!-- Start Price field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Price :</label>
							<div class="col-sm-10 col-md-5">
								<input type="text" name="price" class="form-control" placeholder="The price of the item (eg. $100)"/>
							</div>
						</div>
						<!-- End Price field -->
						<!-- Start Country field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Country :</label>
							<div class="col-sm-10 col-md-5">
								<input type="text" name="country" class="form-control" placeholder="The country the item made in."/>
							</div>
						</div>
						<!-- End Country field -->
						<!-- Start Quality field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Quality :</label>
							<div class="col-sm-10 col-md-5">
								<select name="quality">
									<option value="0">...</option>
									<option value="1">Used</option>
									<option value="2">New</option>

								</select>
							</div>
						</div>
						<!-- End Quality field -->
						<!-- Start Rating field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Rating :</label>
							<div class="col-sm-10 col-md-5">
								<select name="rating">
									<option value="0">....</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
								</select>
							</div>
						</div>
						<!-- End Rating field -->
						<!-- Start Categories field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Category :</label>
							<div class="col-sm-10 col-md-5">
								<select name="category">
									<option value="0">....</option>
									<?php
										$stmt = $conn->prepare("SELECT * FROM categories");
										$stmt->execute();
										$categories = $stmt->fetchAll();
										foreach($categories as $Category) {
											echo "<option value='" . $Category['ID'] . "'>" . $Category['Name'] . "</optin>";
										}
									?>
								</select>
							</div>
						</div>
						<!-- End Categories field -->
						<!-- Start Users field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">User :</label>
							<div class="col-sm-10 col-md-5">
								<select name="member">
									<?php
										if($_SESSION['groupId'] == 1){
											echo "<option value='0'>....</option>";
											$stmt = $conn->prepare("SELECT ID, Username FROM users");
											$stmt->execute();
											$users = $stmt->fetchAll();
											foreach($users as $user){
												echo "<option value='" . $user['ID'] . "'>" . $user['Username'] . "</option>";
											}
										}else{
											$stmt = $conn->prepare("SELECT ID, Username FROM users WHERE ID = ?");
											$stmt->execute(array($_SESSION['id']));
											$user = $stmt->fetch();
											echo "<option value='" . $user['ID'] . "'>" . $user['Username'] . "</option>";
										}
									?>
								</select>
							</div>
						</div>
						<!-- End Users field -->
						<!-- Start Add Button field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-10 col-md-5">
								<input type="submit" value="Add" class="btn btn-primary btn-lg" />
							</div>
						</div>
						<!-- End Add Button field -->
					</form>
				</div>
			<?php
	}elseif($do == "insert") {		// Insert page ==========================

		echo "<div class='container'>";
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				echo "<h1 class='text-center'>Insert Item</h1>";
				
				$name 			= $_POST['name'];
				$description 	= $_POST['description'];
				$price 			= $_POST['price'];
				$country 		= $_POST['country'];
				$quality 		= $_POST['quality'];
				$rating 		= $_POST['rating'];
				$cat_id 		= $_POST['category'];
				$member_id 		= $_POST['member'];

				// Validate input fields
				$errors = array();

				if(empty($name)) {$errors[] = "Name cannot be empty.";}
				if(empty($description)) {$errors[] = "Description cannot be empty.";}
				if(empty($price)) {$errors[] = "Price cannot be empty.";}
				if(empty($country)) {$errors[] = "Country cannot be empty.";}
				if(empty($quality)) {$errors[] = "Quality cannot be empty.";}
				if(empty($rating)) {$errors[] = "Rating cannot be empty.";}
				if(empty($cat_id)) {$errors[] = "Category cannot be empty.";}
				if(empty($member_id)) {$errors[] = "Rating cannot be empty.";}

				if(empty($errors)) {
					// Insert data preparation code
					$stm = $conn->prepare("INSERT INTO items(Name, Description, Price, Country, Quality, Rating, Add_Date, Category_ID, Member_ID)
											VALUES(:name, :description, :price, :country, :quality, :rating, now(), :cat_id, :member_id)");
					// Executing the insert preparation code by the data given from 'POST' request from the form.
					$stm->execute(array(
						'name' 			=> $name,
						'description' 	=> $description,
						'price'			=> $price,
						'country'		=> $country,
						'quality'		=> $quality,
						'rating'		=> $rating,
						'cat_id' 		=> $cat_id,
						'member_id'		=> $member_id
					));

					$sucMsg = "<div class='container alert alert-success'>" . $stm->rowCount() . " records have been inserted.</div>";
					homeRedirection($sucMsg, 'back', '');
				}else{
					foreach($errors as $error) {echo "<div class='alert alert-danger'>" . $error . "</div>";}
					homeRedirection('', 'back', 3);
				}
			}else {
				$errMsg = "<div class='container alert alert-danger'>Sorry you can't browse this page directly.</div>";
				homeRedirection($errMsg, 'back', 3);
			}
		echo "</div>";
	}elseif($do == "delete"){		// Delete page ==========================
		echo "<div class='container'>";
			$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

			// This statement is for getting Member_ID from items table.
			$stmt = $conn->prepare("SELECT Member_ID FROM items WHERE ID=?");
			$stmt->execute(array($id));
			$item = $stmt->fetch();

			// Check if an item with a spacific ID in the items table exists.
			$check = checkItem('ID', 'items',  $id);

			if($check > 0){
				if($_SESSION['groupId'] == 1 || $_SESSION['id'] == $item['Member_ID']){
					$stmt = $conn->prepare("DELETE FROM items WHERE ID=:id");
					$stmt->bindParam('id', $id);
					$stmt->execute();

					$msg = "<div class='container alert alert-danger'>" . $stmt->rowCount() . " record have been deleted.</div>";
					homeRedirection($msg, 'back', 4);

				}elseif($_SESSION['id'] == $item['Member_ID']){
					$stmt = $conn->prepare("DELETE FROM items WHERE ID=:id");
					$stmt->bindParam('id', $id);
					$stmt->execute();

				}else { echo "<div class='container alert alert-danger text-center'>This item does not belong to you to delete it.</div>";}
			}else{echo "<div class='container alert alert-danger text-center'>The Item you are trying to delete is not exists.</div>";}
		echo "</div>";
	}elseif($do == "approve"){		// Approve page =========================
	}else {							// Default page =========================
	}

	include $temp . "footer.php";
}else {
	header("Location: index.php");
	exit();
}

?>