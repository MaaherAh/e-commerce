<?php
	session_start();
	$noNavbar = "";
	$pageTitle = "Login";
	if(isset($_SESSION['username'])) {
		header('Location: dashboard.php');
	}
	include "init.php";

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$username = $_POST['user'];
		$password = $_POST['pass'];
		$hashedPass = sha1($password);

		// Check if the user exist in database
		$stmt = $conn -> prepare("SELECT ID, Username, Password, GroupID
								  FROM users
								  WHERE Username = ? AND Password = ? LIMIT 1");
		$stmt -> execute(array($username, $hashedPass));
		$row = $stmt -> fetch();
		$count = $stmt -> rowCount();

		// Check if there is a record with those information
		if($count > 0) {
			$_SESSION['username'] = $username;
			$_SESSION['id'] = $row['ID'];
			$_SESSION['groupId'] = $row['GroupID'];
			header('Location: dashboard.php');
			exit();
		}
	}
?>

<form class="login" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
	<h4 class="text-center">LOGIN</h4>
	<input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off"/>
	<input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="off"/>
	<input class="btn btn-primary btn-block" type="submit" value="Login"/>
</form>

<?php include $temp . "footer.php" ?>