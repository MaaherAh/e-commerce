<?php

function lang($phrase) {
	static $lang = array(
		"admin" 		=> "الرئيسية",
		"categories" 	=> "الاقسام",
		"items" 		=> "الاقسام",
		"members" 		=> "الاعضاء",
		"stat" 			=> "التحليل",
		"logs" 			=> "السجلات"
	);

	return $lang[$phrase];
}

?>