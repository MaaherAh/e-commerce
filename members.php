<?php

session_start();
$pageTitle = "Members";
if(isset($_SESSION["username"])) {
	include "init.php";

	$do = isset($_GET['do']) ? $_GET['do'] : 'main';

	if($do == "main") {				// Main page ============================
		
		// Adding additional query to the statement preparation to get the pending users only
		$addQuery = "";
		if(isset($_GET['page']) && $_GET['page'] == 'pending'){
			$addQuery = "AND RegStatus = 0";
		}

		// Getting all members into the table of the members.
		$stmt = $conn->prepare("SELECT * FROM users WHERE (GroupID = 0 OR GroupID = 1) $addQuery");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		?>
			<h1 class="text-center">Manage members</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
							<td>ID</td>
							<td>Full Name</td>
							<td>Username</td>
							<td>Email</td>
							<td>Registered date</td>
							<td>Status</td>
							<td>Control</td>
						</tr>
						<?php
							foreach($rows as $row) {
								echo "<tr>";
									echo "<td>" . $row['ID'] . "</td>";
									echo "<td>" . $row['FullName'] . "</td>";
									echo "<td>" . $row['Username'] . "</td>";
									echo "<td>" . $row['Email'] . "</td>";
									echo "<td>" . $row['Insert_Date'] . "</td>";
									echo "<td>";
										if($row['GroupID'] == 1){echo "Admin :D";}else{echo "Member";};
									echo "</td>";
									echo "<td>
											<a href='members.php?do=edit&id=" . $row['ID'] ."' class='btn btn-success'>Edit</a>
											<a href='members.php?do=delete&id=" . $row['ID'] ."' class='btn btn-danger confirm'>Delete</a>";
											if($row['RegStatus'] == 0){
												echo "<a href='members.php?do=activate&id=" . $row['ID'] ."' class='btn btn-info activate'>Activate</a>";
											}
									echo "</td>";
								echo "</tr>";
							}
						?>
					</table>
				</div>
				<a href='members.php?do=add' class="btn btn-primary" style="margin-bottom: 15px"><i class="fa fa-plus"></i> Add member</a>
			</div>
		<?php
	}elseif($do == "edit") { 		// Edit page ============================
		$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

		// Check if a user with a spacific ID exists in the database
		$stmt = $conn -> prepare("SELECT * FROM users WHERE ID = ? LIMIT 1");
		$stmt -> execute(array($id));
		$row = $stmt -> fetch();
		$count = $stmt -> rowCount();

		// Checks if the logged in user is an admin or the same user who has logged in.
		if($_SESSION['id'] == $id || $_SESSION['groupId'] == 1) {
			// Showing the form of editing
			if($count > 0) {
				?>
					<h1 class="text-center">Edit</h1>
					<div class="container">
						<form class="form-horizontal" action="?do=update" method="POST">
							<input type="hidden" name="id" value="<?php echo $id ?>"/>
							<!-- Start username field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Username</label>
								<div class="col-sm-10 col-md-5">
									<input type="text" name="username" class="form-control" value="<?php echo $row['Username']; ?>" autocomplete="off" required="required"/>
								</div>
							</div>
							<!-- End username field -->
							<!-- Start password field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Password</label>
								<div class="col-sm-10 col-md-5">
									<input type="password" name="password" class="form-control" autocomplete="new-password"/>
								</div>
							</div>
							<!-- End password field -->
							<!-- Start email field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Email</label>
								<div class="col-sm-10 col-md-5">
									<input type="email" name="email" class="form-control" value="<?php echo $row['Email']; ?>" required="required"/>
								</div>
							</div>
							<!-- End email field -->
							<!-- Start fullname field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Full name</label>
								<div class="col-sm-10 col-md-5">
									<input type="text" name="fullname" class="form-control" value="<?php echo $row['FullName']; ?>" autocomplete="off" required="required"/>
								</div>
							</div>
							<!-- End fullname field -->
							<!-- Start submit field -->
							<div class="form-group form-group-lg">
								<div class="col-sm-10 col-md-5">
									<input type="submit" value="Save" class="btn btn-primary btn-lg" />
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
			$errMsg = "<div class='container alert alert-danger'>Sorry you are not an admin to modify this user.</div>";
			homeRedirection($errMsg, 'back', 3);
		}
	}elseif($do == "update"){		// Update page ==========================
		echo "<h1 class='text-center'>Update Member</h1>";
		echo "<div class='container'>";
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				echo "<h1 class='text-center'>Update</h1>";
				
				$id 		= $_POST['id'];
				$username 	= $_POST['username'];
				$password 	= sha1($_POST['password']);
				$email 		= $_POST['email'];
				$fullName 	= $_POST['fullname'];

				// Validate input fields
				$errors = array();
				if(empty($username)){
					$errors[] = "Username cannot be empty.";
				}elseif(strlen($username) < 4) {
					$errors[] = "Username cannot be less than 4 characters.";
				}elseif(strlen($username) > 10) {
					$errors[] = "Username cannot be more that 10 characters.";
				}
				if(empty($email)){$errors[] = "Email cannot be empty.";}
				if(empty($fullName)){$errors[] = "Full name cannot be empty.";}

				foreach($errors as $value) {echo $value . "</br>";}

				if(empty($errors)) {
					// Update data
					$stm = $conn->prepare("UPDATE users SET Username=?, Password=?, Email=?, FullName=? WHERE ID=?");
					$stm->execute(array($username, $password, $email, $fullName, $id));

					$sucMsg = "<div class='alert alert-success'>" . $stm->rowCount() . " records has been updated.</div>";
					homeRedirection($sucMsg, 'back', 3);
				}

			}else {
				$errMsg = "<div class='alert alert-danger'>Sorry you cannot browse this page directly.</div>";
				homeRedirection($errMsg, 'back');
			}
		echo "</div>";
	}elseif($do == "add") {			// Add page =============================

		if($_SESSION['groupId'] == 1) {
			?>
				<h1 class="text-center">Add new member</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=insert" method="POST">
						<!-- Start username field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Username</label>
							<div class="col-sm-10 col-md-5">
								<input type="text" name="username" class="form-control" autocomplete="off" required="required"/>
							</div>
						</div>
						<!-- End username field -->
						<!-- Start password field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Password</label>
							<div class="col-sm-10 col-md-5">
								<input type="password" name="password" class="password form-control" autocomplete="new-password" required="required"/>
								<i class="show-pass fa fa-eye fa-1x"></i>
							</div>
						</div>
						<!-- End password field -->
						<!-- Start email field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Email</label>
							<div class="col-sm-10 col-md-5">
								<input type="email" name="email" class="form-control" required="required"/>
							</div>
						</div>
						<!-- End email field -->
						<!-- Start fullname field -->
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Full name</label>
							<div class="col-sm-10 col-md-5">
								<input type="text" name="fullname" class="form-control" autocomplete="off" required="required"/>
							</div>
						</div>
						<!-- End fullname field -->
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
			$errMsg = "<div class='container alert alert-danger'>Sorry you are not an Admin to add new users.</div>";
			homeRedirection($errMsg, 'back');
		}
	}elseif($do == "insert") {		// Insert page ==========================
		
		echo "<div class='container'>";
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				echo "<h1 class='text-center'>Insert Member</h1>";
				
				$username 	= $_POST['username'];
				$password 	= $_POST['password'];
				$hashedpass = sha1($_POST['password']);
				$email 		= $_POST['email'];
				$fullName 	= $_POST['fullname'];

				// Validate input fields
				$errors = array();
				if(empty($username)){$errors[] = "Username cannot be empty.";}
				elseif(strlen($username) < 4) {$errors[] = "Username cannot be less than 4 characters.";}
				elseif(strlen($username) > 10) {$errors[] = "Username cannot be more that 10 characters.";}

				if(empty($password)) {$errors[] = "Password cannot be empty.";}
				if(empty($email)){$errors[] = "Email cannot be empty.";}
				if(empty($fullName)){$errors[] = "Full name cannot be empty.";}

				if(empty($errors)) {
					// Chech if the username we inserted is exist.
					$checkUser = checkItem("Username", "users", $username);
					$checkEmail = checkItem("Email", "users", $email);

					if($checkUser == 1 || $checkEmail == 1){
						echo "<div class='alert alert-danger'>Sorry this user '" . $username . "' or email '" . $email ."' is already exists</div>";
					}else{
						// Insert data preparation code
						$stm = $conn->prepare("INSERT INTO users(Username, Password, Email, FullName, Insert_Date, RegStatus)
												VALUES(:username, :hashedpass, :email, :fullName, now(), 1) ");
						// Executing the insert preparation code by the data given from 'POST' request from the form.
						$stm->execute(array(
							'username' 		=> $username,
							'hashedpass' 	=> $hashedpass,
							'email'			=> $email,
							'fullName'		=> $fullName
						));

						$sucMsg = "<div class='container alert alert-success'>" . $stm->rowCount() . " records have been inserted.</div>";
						homeRedirection($sucMsg, 'back');
					}
				}else{foreach($errors as $value) {echo $value . "</br>";}}
			}else {
				$errMsg = "<div class='container alert alert-danger'>Sorry you can't browse this page directly.</div>";
				homeRedirection($errMsg, 'back', 3);
			}
		echo "</div>";
	}elseif($do == "delete"){		// Delete page ==========================
		
		$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
		// Check if a user with a spacific ID exists in the database
		$check = checkItem('ID', 'users', $id);

		// Checks if the logged in user is an admin or the same user who has logged in.
		if($_SESSION['id'] == $id || $_SESSION['groupId'] == 1) {
			echo "<h1 class='text-center'>Delete Member</h1>";
			// Deleting the user if the ID is exists or print error message if the ID is not exist.
			if($check > 0) {
				$stmt = $conn->prepare("DELETE FROM users WHERE ID = :id");
				$stmt->bindParam("id", $id);
				$stmt->execute();

				if($_SESSION['id'] === $id) {
					session_start();
					session_unset();
					session_destroy();
					header("Location: index.php");
					exit();
				}else {
					$errMsg = "<div class='container alert alert-success'>" . $stmt->rowCount() . " records has been deleted.</div>";
					homeRedirection($errMsg, 'back');
				}
			}else {
				$errMsg = "<div class='container alert alert-danger'>Sorry there is no suck ID.</div>";
				homeRedirection($errMsg, 'back');
			}
		}else {
				$errMsg = "<div class='container alert alert-danger'>Sorry you are not an Admin to delete this user.</div>";
				homeRedirection($errMsg, 'back', '');
		}
	}elseif($do == "activate"){		// Activate page ========================

		// Check if there is an 'id' = numeric number in the link [through get request]
		$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

		// Check if a user with a spacific ID exists in the database
		$check = checkItem('ID', 'users', $id);

		// Checks if the logged in user is an admin.
		if($_SESSION['groupId'] == 1) {
			echo "<h1 class='text-center'>Activate Member</h1>";
			// Updating the user if the ID is exists or print error message if the ID is not exist.
			if($check > 0) {
				$stmt = $conn->prepare("UPDATE users SET RegStatus = 1 WHERE ID = ?");
				$stmt->execute(array($id));

				$errMsg = "<div class='container alert alert-success'>" . $stmt->rowCount() . " records has been activated.</div>";
				homeRedirection($errMsg, 'back');

			}else {
				$errMsg = "<div class='container alert alert-danger'>Sorry there is no user with such ID.</div>";
				homeRedirection($errMsg, 'back');
			}
		}else {
				$errMsg = "<div class='container alert alert-danger'>Sorry you are not an Admin to Activate this user.</div>";
				homeRedirection($errMsg, 'back', '');
		}
	}else {							// Default page =========================
		echo "<div class='container alert alert-danger'>Error, there is no such page with this name \"" . $_GET['do'] . "\"</div>";
	}

	include $temp . "footer.php";
}else {
	header("Location: index.php");
	exit();
}

?>