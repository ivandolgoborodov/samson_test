<?php
function findSimple ($a, $b){
	function isPrime($number)
	{
		if ($number==2)
					return true;
		if ($number%2==0)
			return false;
		$i=3;
		$max_factor = (int)sqrt($number);
		while ($i<=$max_factor){
			if ($number%$i == 0)
				return false;
			$i+=2;
		}
		return true;
	}
	$arraySimple=[];
	if (!is_int($a)||!is_int($b)) {
		echo "Ошибка числа не простые";
		return;
	}
	for ($i=$a;$i<=$b;$i++)
	if (isPrime($i)) $arraySimple[]=$i;
	
	return $arraySimple;
	}
function createTrapeze($a){
	if (!(count($a)%3===0)) {
			echo "количество чисел на кратно трем";
			return;
		}
	foreach($a as $val){
		if ($val<=0) {
			echo "числа не положительные";
			return;
		}
	}
	for ($i=0;$i<count($a);$i=$i+3){
		$arr['a']=$a[$i];
		$arr['b']=$a[$i+1];
		$arr['c']=$a[$i+2];
		$result[]=$arr;
	}
	return $result;
}
function squareTrapeze($a){
	foreach ($a as $k=>$v){
		$s=$v['c']*(($v['a']+$v['b'])/2);
		$v['s']=$s;
		$result[]=$v;
	}
	return $result;
}
function getSizeForLimit($a, $b){
	foreach ($a as $k => $v){
		if ($v['s']<=$b) {
			$max=$v['s'];
			$result=$v;
			break;
		}
		else continue;
	}
	if (isset($max)){
		foreach ($a as $k => $v){
			if ($v['s']>$max&&$v['s']<=$b) {
				$max=$v['s'];
				$result=$v;
			}
		}
		return $result;	
	}
	else {
		echo "все элементы массива больше максимально заданной площади т.е больше ".$b;
		return;
	}
}
function getMin($a){
	$min=$a[0];
	for ($i=0;$i<count($a);$i++){
		if ($a[$i]<$min) $min=$a[$i];
	}
	return $min;
}
function printTrapeze($a){
echo '<table border=1><tr><th>a</th><th>b</th><th>c</th><th>s</th></tr>';
	foreach ($a as $k => $v){
		$s = $v['s'];
		if (!($s%2===0)) 
			echo '<tr><td>'.$v['a'].'</td><td>'.$v['b'].'</td><td>'.$v['c'].'</td><td style="color:red;">'.$v['s'].'</td></tr>';
		else
			echo '<tr><td>'.$v['a'].'</td><td>'.$v['b'].'</td><td>'.$v['c'].'</td><td>'.$v['s'].'</td></tr>';

	}
	echo '</table>';
}
abstract class BaseMath
{
    abstract protected function getValue();
 
	public function exp1($a,$b,$c) {
        return pow($b,$c)*$a;
    }

	public function exp2($a,$b,$c) {
        return pow($a/$b,$c);
    }
}
class F1 extends BaseMath
{
	var $a;
	var $b;
	var $c;
 
	function  __construct($a,$b,$c){
        $this -> a = $a;
        $this -> b = $b;
        $this -> c = $c;
	}	
    
	public function getValue() {
        return $this->exp1($this->a,$this->b,$this->c)+pow(($this->exp2($this->a,$this->c,$this->b))%3,min(array($this->a,$this->b,$this->c)));
    }
}
echo '<pre>';print_r(findSimple(1,33));echo '</pre>';
$a=array(1,2,3,4,5,6,4.5,5,196.6);	
echo '<pre>';print_r(createTrapeze($a));echo '</pre>';
echo '<pre>';print_r(squareTrapeze(createTrapeze($a)));echo '</pre>';
echo '<pre>';print_r(getSizeForLimit(squareTrapeze(createTrapeze($a)),200));echo '</pre>';
echo 'getMin($a)='.getMin($a);
printTrapeze(squareTrapeze(createTrapeze($a)));
$c1=new F1(8,3,1);
echo '$c1->getValue()='.$c1->getValue();

?>