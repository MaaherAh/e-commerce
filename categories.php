<?php

session_start();
$pageTitle = "Categories";
if(isset($_SESSION["username"])) {
	include "init.php";
	
	$do = isset($_GET['do']) ? $_GET['do'] : 'main';

	if($do == "main") {				// Main page ============================

		$sort = "desc";
		$sort_array = array('asc', 'desc');
		if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){
			$sort = $_GET['sort'];
		}

		// Getting all Categories into the table of the members.
		$stmt = $conn->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
		$stmt->execute();
		$categories = $stmt->fetchAll();
		?>
			<h1 class="text-center">Manage Categories</h1>
			<div class="container categories">
				<div class="panel panel-default">
					<div class="panel-heading">
						Categories:
						<div class="ordering pull-right">
							Ordering: [
							<a href="?sort=asc" class="<?php if($sort == 'asc'){echo 'active';} ?>">ASC</a> |
							<a href="?sort=desc" class="<?php if($sort == 'desc'){echo 'active';} ?>">DESC</a> ]
						</div>
					</div>
					<div class="panel-body">
						<?php
							foreach($categories as $category){
								echo "<div class='category'>";
									echo "<div class='edit-buttons pull-right'>";
										echo "<a href='?do=edit&id=" . $category['ID'] . "' class='btn btn-xs btn-primary edit-button'><i class='fa fa-edit'></i> Edit</a>";
										echo "<a href='?do=delete&id=" . $category['ID'] . "' class='btn btn-xs btn-danger delete-button'><i class='far fa-trash-alt'></i> Delete</a>";
									echo "</div>";
									echo "<h3>" . $category['Name'] . "</h3>";
									echo "<p>";if($category['Description'] == ''){echo "No description.";}else{echo $category['Description'];}echo "</p>";
									if($category['Visibility'] == 0){echo "<span class='visibility'>Hidden</span>";}
									if($category['Allow_Comment'] == 0){echo "<span class='commenting'>commenting disabled</span>";}
									if($category['Allow_Ads'] == 0){echo "<span class='ads-disabeld'>Ads disabled</span>";}
								echo "</div>";
								echo "<hr>";
							}
						?>
					</div>
				</div>
				<a href="?do=add" class='btn btn-primary add-category'><i class="fa fa-plus"></i> Add Category</a>
			</div>
		<?php
	}elseif($do == "edit") { 		// Edit page ============================

		$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

		// Check if a user with a spacific ID exists in the database
		$stmt = $conn -> prepare("SELECT * FROM categories WHERE ID = ?");
		$stmt -> execute(array($id));
		$category = $stmt -> fetch();
		$count 	  = $stmt -> rowCount();

		// Checks if the logged in user is an admin or the same user who has logged in.
		if($_SESSION['id'] == $id || $_SESSION['groupId'] == 1) {
			// Showing the form of editing
			if($count > 0) {
				?>
					<h1 class="text-center">Edit Category</h1>
					<div class="container">
					<form class="form-horizontal" action="?do=update" method="POST">
						<!-- Hidden ID field to store the ID of the category -->
						<input type="hidden" name="id" value="<?php echo $id; ?>" />
						<!-- Start Name field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Name</label>
							<div class="col-sm-10 col-md-5">
								<input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name of the category" value="<?php echo $category['Name'] ?>"/>
							</div>
						</div>
						<!-- End Name field -->
						<!-- Start Description field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10 col-md-5">
								<input type="text" name="description" class="form-control" placeholder="Describe the category" value="<?php echo $category['Description'] ?>"/>
							</div>
						</div>
						<!-- End Description field -->
						<!-- Start Ordering field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Ordering</label>
							<div class="col-sm-10 col-md-5">
								<input type="number" name="ordering" class="form-control" placeholder="The number to arrange the category" value="<?php echo $category['Ordering'] ?>"/>
							</div>
						</div>
						<!-- End Ordering field -->
						<!-- Start Visibility field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Visibile</label>
							<div class="col-sm-10 col-md-5">
								<span class="vis-labels">
									<input id="vis-yes" type="radio" name="visibility" value="1" <?php if($category['Visibility'] == 1){echo "checked";} ?>/>
									<label for="vis-yes">Yes</label>
								</span>
								<span>
									<input id="vis-no" type="radio" name="visibility" value="0" <?php if($category['Visibility'] == 0){echo "checked";} ?>/>
									<label for="vis-no">No</label>
								</span>
							</div>
						</div>
						<!-- End Visibility field -->
						<!-- Start Allow-commenting field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Allow Commenting</label>
							<div class="col-sm-10 col-md-5">
								<span class="vis-labels">
									<input id="com-yes" type="radio" name="allow_commenting" value="1" <?php if($category['Allow_Comment'] == 1){echo "checked";} ?>/>
									<label for="com-yes">Yes</label>
								</span>
								<span>
									<input id="com-no" type="radio" name="allow_commenting" value="0" <?php if($category['Allow_Comment'] == 0){echo "checked";} ?>/>
									<label for="com-no">No</label>
								</span>
							</div>
						</div>
						<!-- End Allow-commenting field -->
						<!-- Start Allow-ads field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Allow Ads</label>
							<div class="col-sm-10 col-md-5">
								<span class="vis-labels">
									<input id="ads-yes" type="radio" name="allow_ads" value="1" <?php if($category['Allow_Ads'] == 1){echo "checked";} ?>/>
									<label for="ads-yes">Yes</label>
								</span>
								<span>
									<input id="ads-no" type="radio" name="allow_ads" value="0" <?php if($category['Allow_Ads'] == 0){echo "checked";} ?>/>
									<label for="ads-no">No</label>
								</span>
							</div>
						</div>
						<!-- End Allow-ads field -->
						<!-- Start submit field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-10 col-md-5">
								<input type="submit" value="Update" class="btn btn-primary btn-lg" />
							</div>
						</div>
						<!-- End submit field -->
					</form>
					</div>
				<?php
			}else {
				// if the spacified ID doesn't exist in the database, print an Error massege
				$errMsg = "<div class='container alert alert-danger'>Sorry, there's no such ID</div>";
				homeRedirection($errMsg);
			}
		}else {
			$errMsg = "<div class='container alert alert-danger'>Sorry you are not an admin to modify this Category.</div>";
			homeRedirection($errMsg, 'back', 3);
		}
	}elseif($do == "update"){		// Update page ==========================
		echo "<div class='container'>";
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				echo "<h1 class='text-center'>Update Category</h1>";
				
				$id 			= $_POST['id'];
				$name 			= $_POST['name'];
				$description 	= $_POST['description'];
				$ordering 		= $_POST['ordering'];
				$visibility 	= $_POST['visibility'];
				$commenting 	= $_POST['allow_commenting'];
				$ads 			= $_POST['allow_ads'];

				// Update data
				$stmt = $conn->prepare("UPDATE categories SET Name=?, Description=?, Ordering=?, Visibility=?, Allow_Comment=?, Allow_Ads=? WHERE ID=?");
				$stmt->execute(array($name, $description, $ordering, $visibility, $commenting, $ads, $id));

				$sucMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " records has been updated.</div>";
				homeRedirection($sucMsg, 'back', 4);

			}else {
				$errMsg = "<div class='alert alert-danger'>Sorry you cannot browse this page directly.</div>";
				homeRedirection($errMsg, 'back');
			}
		echo "</div>";
	}elseif($do == "add") {			// Add page =============================

		if($_SESSION['groupId'] == 1) {
			?>
				<h1 class="text-center">Add new Category</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=insert" method="POST">
						<!-- Start Name field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Name</label>
							<div class="col-sm-10 col-md-5">
								<input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name of the category"/>
							</div>
						</div>
						<!-- End Name field -->
						<!-- Start Description field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10 col-md-5">
								<input type="text" name="description" class="form-control" placeholder="Describe the category"/>
							</div>
						</div>
						<!-- End Description field -->
						<!-- Start Ordering field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Ordering</label>
							<div class="col-sm-10 col-md-5">
								<input type="number" name="ordering" class="form-control" placeholder="The number to arrange the category"/>
							</div>
						</div>
						<!-- End Ordering field -->
						<!-- Start Visibility field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Visibile</label>
							<div class="col-sm-10 col-md-5">
								<span class="vis-labels">
									<input id="vis-yes" type="radio" name="visibility" value="1" checked/>
									<label for="vis-yes">Yes</label>
								</span>
								<span>
									<input id="vis-no" type="radio" name="visibility" value="0"/>
									<label for="vis-no">No</label>
								</span>
							</div>
						</div>
						<!-- End Visibility field -->
						<!-- Start Allow-commenting field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Allow Commenting</label>
							<div class="col-sm-10 col-md-5">
								<span class="vis-labels">
									<input id="com-yes" type="radio" name="allow_commenting" value="1" checked/>
									<label for="com-yes">Yes</label>
								</span>
								<span>
									<input id="com-no" type="radio" name="allow_commenting" value="0"/>
									<label for="com-no">No</label>
								</span>
							</div>
						</div>
						<!-- End Allow-commenting field -->
						<!-- Start Allow-ads field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Allow Ads</label>
							<div class="col-sm-10 col-md-5">
								<span class="vis-labels">
									<input id="ads-yes" type="radio" name="allow_ads" value="1" checked/>
									<label for="ads-yes">Yes</label>
								</span>
								<span>
									<input id="ads-no" type="radio" name="allow_ads" value="0"/>
									<label for="ads-no">No</label>
								</span>
							</div>
						</div>
						<!-- End Allow-ads field -->
						<!-- Start submit field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-10 col-md-5">
								<input type="submit" value="Add" class="btn btn-primary btn-lg" />
							</div>
						</div>
						<!-- End submit field -->
					</form>
				</div>
			<?php
		}else {
			$errMsg = "<div class='container alert alert-danger text-center'>Sorry you are not an Admin to add new Category.</div>";
			homeRedirection($errMsg, 'back', '');
		}
	}elseif($do == "insert") {		// Insert page ==========================

		echo "<div class='container'>";
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				echo "<h1 class='text-center'>Insert Category</h1>";
				$name 			= $_POST['name'];
				$description 	= $_POST['description'];
				$ordering 		= $_POST['ordering'];
				$visibility 	= $_POST['visibility'];
				$commenting 	= $_POST['allow_commenting'];
				$ads 			= $_POST['allow_ads'];

				// Error to appear if the Name of the Category is Empty
				$error = "";
				if(empty($name)){$error = "Name field cannot be empty.";}

				if(!empty($error)){

					echo "<div class='container alert alert-danger'>Sorry, Invalid/Missing Name value.</div>";
				}else{

					// Check if the username we inserted is exist.
					$checkName = checkItem("Name", "categories", $name);

					if($checkName == 1){
						echo "<div class='alert alert-danger'>Sorry this Category '" . $name . "' is already exists</div>";
					}else{
						// Insert data preparation code
						$stm = $conn->prepare("INSERT INTO categories(Name, Description, Ordering, Visibility, Allow_Comment, Allow_Ads)
												VALUES(:Name, :description, :ordering, :visibility, :commenting, :ads) ");
						// Executing the insert preparation code by the data given from 'POST' request from the form.
						$stm->execute(array(
							'Name' 			=> $name,
							'description' 	=> $description,
							'ordering'		=> $ordering,
							'visibility'	=> $visibility,
							'commenting'	=> $commenting,
							'ads'			=> $ads
						));

						$sucMsg = "<div class='container alert alert-success'>" . $stm->rowCount() . " records have been inserted.</div>";
						homeRedirection($sucMsg, 'back');
					}
				}

			}else {
				$errMsg = "<div class='container alert alert-danger'>Sorry you can't browse this page directly.</div>";
				homeRedirection($errMsg, 'back', 3);
			}
		echo "</div>";
	}elseif($do == "delete"){		// Delete page ==========================
		
		echo "<div class='container'>";
			$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

			// Check if a Category with a spacific ID exists in the categories table exists.
			$check = checkItem('ID', 'categories', $id);

			// Checks if the logged in user is an admin or the same user who has logged in.
			if($_SESSION['id'] == $id || $_SESSION['groupId'] == 1) {
				echo "<h1 class='text-center'>Delete Category</h1>";
				// Deleting the user if the ID is exists or print error message if the ID is not exist.
				if($check > 0) {
					$stmt = $conn->prepare("DELETE FROM categories WHERE ID = :id");
					$stmt->bindParam("id", $id);
					$stmt->execute();

					if($_SESSION['id'] === $id) {
						session_start();
						session_unset();
						session_destroy();
						header("Location: index.php");
						exit();
					}else {
						$errMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " records has been deleted.</div>";
						homeRedirection($errMsg, 'back');
					}
				}else {
					$errMsg = "<div class='alert alert-danger'>Sorry there is no suck ID.</div>";
					homeRedirection($errMsg, 'back');
				}
			}else {
					$errMsg = "<div class='alert alert-danger'>Sorry you are not an Admin to delete this user.</div>";
					homeRedirection($errMsg, 'back', '');
			}
		echo "</div>";
	}else {							// Default page =========================
		echo "<div class='container alert alert-danger'>Error, there is no such page with this name \"" . $_GET['do'] . "\"</div>";
	}

	include $temp . "footer.php";
}else {
	header("Location: index.php");
	exit();
}

?>