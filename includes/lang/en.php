<?php

function lang($phrase) {
	static $lang = array(
		"admin" 		=> "Home",
		"categories" 	=> "Categories",
		"items" 		=> "Items",
		"members" 		=> "Members",
		"stat" 			=> "Statistics",
		"logs" 			=> "Logs"
	);

	return $lang[$phrase];
}

?>