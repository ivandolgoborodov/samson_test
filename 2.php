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
function importXml($a){
	$xml=simplexml_load_file($a) or die("Error: Cannot create object");
	$host = 'localhost'; // адрес сервера 
	$database = 'test_samson'; // имя базы данных
	$user = 'root'; // имя пользователя
	$password = ''; // пароль

	$link = mysqli_connect($host, $user, $password, $database) 
		or die("Ошибка " . mysqli_error($link));
	$i=0; 
	$j=0;
	$k=0;
	$g=0;
	foreach ($xml->Товар as $v){
		$i++;
		$product_code=$v->attributes()[Код];
		$query ="INSERT INTO a_product (id, code, name) 
		VALUES ('".$i."','".$v->attributes()[Код]."', '".$v->attributes()[Название]."') 
		ON DUPLICATE KEY UPDATE id='".$i."', code='".$v->attributes()[Код]."' , name='".$v->attributes()[Название]."';";
		$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
		if($result)
		{
		   echo "Выполнение запроса a_product прошло успешно ";
		}
		foreach ($v->Цена as $v2){
			$j++;
			$query ="INSERT INTO a_price (id, id_product, type, value) 
			VALUES ('".$j."','".$i."','".$v2->attributes()[Тип]."', '".$v2."') 
			ON DUPLICATE KEY UPDATE id='".$j."',id_product='".$i."', type='".$v2->attributes()[Тип]."' , value='".$v2."';";
			$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
			if($result)
			{
			   echo "Выполнение запроса a_price прошло успешно ";
			}

		}
		foreach ($v->Свойства as $v3){
			foreach ($v3 as $k4=>$v4){	
			$k++;
				$query ="insert INTO a_property (id, id_product, name, value) 
				VALUES ('".$k."','".$i."','".$k4."', '".$v4."') 
				ON DUPLICATE KEY UPDATE id='".$k."',id_product='".$i."', name='".$k4."' , value='".$v4."';";
				$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
				if($result)
				{
				   echo "Выполнение запроса a_property прошло успешно ";
				}
			}

		}
		foreach ($v->Разделы as $v5){
			$gi=0;
			foreach ($v5 as $k6=>$v6){
				$gi++;
				$g++;
				if ($gi===1) $id_parent=$g;
				else $id_parent=$g-1;
				$query ="INSERT INTO a_category (product_code, id, name, parent_id) 
				VALUES ('".$product_code."','".$g."','".$v6."', '".$id_parent."') 
				ON DUPLICATE KEY UPDATE product_code='".$product_code."',id='".$g."', name='".$v6."' , parent_id='".$id_parent."';";
				$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
				if($result)
				{
				   echo "Выполнение запроса a_category прошло успешно ";
				}

			}
		}
	}
mysqli_close($link);
}
echo convertString('twostdddtwostbbbtwostaaa', 'twost');
$a=array(['a'=>2,'b'=>1],['a'=>1,'b'=>6],['a'=>8,'b'=>2],['a'=>9,'b'=>-4],['a'=>5,'b'=>3]);
echo '<pre>';print_r(mySortForKey($a,'a'));echo '</pre>';
importXml('2.xml');

?>