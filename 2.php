<?php
function convertString($a, $b){
	$count_b_in_a=mb_substr_count($a, $b);
	if ($count_b_in_a>=2) {
		$pos1 = strpos($a, $b);
		$pos2 = strpos($a, $b, $pos1 + strlen($b));
		$result=substr_replace($a, strrev($b), $pos2, strlen($b));
	}
	return $result;
}
echo convertString('twostdddtwostbbbtwostaaa', 'twost');


?>