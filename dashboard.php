<?php

// Output Buffering (Yan3ny Takhzeen .. Fa Haykhzen El Data El Awl except 'headers') Start. For not sending outputs before headers.
// It's preferable to set the 'ob_start()' before the 'session()' function.
ob_start('ob_gzhandler'); // gz is a technique to handle the outputs and compress it to speed up the preformance
session_start();
if(isset($_SESSION["username"])) {
	$pageTitle = "Dashboard";
	include "init.php";

	$latestMembers = getLatest('*', 'users', 'ID', 4);

	?>
		<div class="container text-center home-stat"> <!-- Members Dashboard -->
			<h1>Dashboard</h1>
			<div class="row home-stat">
				<div class="col-md-3">
					<div class="stat st-members">
						Total Members
						<a href="members.php"><span><?php echo countItems('ID', 'users') ?></span></a>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stat st-pending">
						Pending Members
						<a href="members.php?do=main&page=pending"><span><?php echo countItems('RegStatus', 'users', 0) ?></span></a>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stat st-items">Total Items
						<a href="items.php"><span><?php echo countItems('ID', 'items') ?></span></a>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stat st-comments">Total comments<span>1300</span></div>
				</div>
			</div>
		</div>
		
		<div class="container latest"> <!-- Latest Dashboard -->
			<div class="row">
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading"><i class="fa fa-users"></i>Latest registered users:</div>
						<div class="panel-body">
							<ul class='list-unstyled latest_members'>
								<?php 
									foreach ($latestMembers as $member){
										echo "<li>";
											echo $member['Username'];
											echo "<a href='members.php?do=edit&id=" .  $member['ID'] . "' class='btn btn-success pull-right'><span><i class='fa fa-edit'></i>Edit</span></a>";
											if($member['RegStatus'] == 0){
												echo "<a href='members.php?do=activate&id=" . $member['ID'] ."' class='btn btn-info activate pull-right'>Activate</a>";
											}
										echo "</li>";
									}
								?>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="panel panel-default pull-right">
						<div class="panel-heading"><i class="fa fa-tag"></i>Latest items:</div>
						<div class="panel-body">test</div>
					</div>
				</div>
			</div>
		</div>
	<?php
	include $temp . "footer.php";
}else {
	header("Location: index.php");
	exit();
}
ob_end_flush(); // Send the output buffer and turn off output buffering.

?>