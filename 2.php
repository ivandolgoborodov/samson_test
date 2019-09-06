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
function mySortForKey($a, $b){
    $sortArr = array();
    foreach($a as $key=>$val){
		if (!$val[$b]) throw new Exception("Отсутствует ключ $b в $key - м массиве!");
        $sortArr[$key] = $val[$b];
    }
    array_multisort($sortArr,$a);
    return $a;
}
echo convertString('twostdddtwostbbbtwostaaa', 'twost');
$a=array(['a'=>2,'b'=>1],['a'=>1,'b'=>6],['a'=>8,'b'=>2],['a'=>9,'b'=>-4],['g'=>5,'b'=>3]);
echo '<pre>';print_r(mySortForKey($a,'a'));echo '</pre>';

?>