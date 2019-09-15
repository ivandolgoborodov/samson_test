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
				if ($gi===1) {$id_parent=$g;}
				else {$id_parent=$g-1;}
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
function exportXml($a, $b){
	$host = 'localhost'; // адрес сервера 
	$database = 'test_samson'; // имя базы данных
	$user = 'root'; // имя пользователя
	$password = ''; // пароль

	$link = mysqli_connect($host, $user, $password, $database) 
		or die("Ошибка " . mysqli_error($link));
	//$b[strlen($b)-1]=NULL;
	//$b=iconv("UTF-8","Windows-1251",$b);
	//echo $b;
	$query ="Select distinct ap.id as id,ap.code as code,ap.name as name from a_product ap,a_category ac where ac.product_code=ap.code and ap.name like '".$b."%';";
	$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
	$i=0;
	while ($row = mysqli_fetch_assoc($result)) {
		$tovars[ids][$i]=$row["id"];
		$tovars[codes][$i]=$row["code"];
		$tovars[names][$i]=$row["name"];
		$query2 ="Select * from a_price where id_product='".$tovars[ids][$i]."';";
		$result2 = mysqli_query($link, $query2) or die("Ошибка " . mysqli_error($link)); 
		$j=0;
		while ($row = mysqli_fetch_assoc($result2)) {
			$prices[$i][id][$j]=$row["id"];
			$prices[$i][type][$j]=$row["type"];
			$prices[$i][value][$j]=$row["value"];
			$j++;
		}
		$query3 ="Select * from a_property where id_product='".$tovars[ids][$i]."';";
		$result3 = mysqli_query($link, $query3) or die("Ошибка " . mysqli_error($link)); 
		$k=0;
		while ($row = mysqli_fetch_assoc($result3)) {
			$properties[$i][id][$k]=$row["id"];
			$properties[$i][name][$k]=$row["name"];
			$properties[$i][value][$k]=$row["value"];
			$k++;
		}
		$query4 ="Select a.parent_id,a.name,b.id,b.name from a_category a,a_category b where a.name like '".$b."%' and b.parent_id=a.parent_id;";
		$result4 = mysqli_query($link, $query4) or die("Ошибка " . mysqli_error($link)); 
		$n=0;
		while ($row = mysqli_fetch_assoc($result4)) {
			$categories[$i][parent_id][$n]=$row["parent_id"];
			$categories[$i][name][$n]=$row["name"];
			$n++;
		}
		$i++;
	}
  //echo '<pre>';print_r($tovars);echo '</pre>';
  //echo '<pre>';print_r($prices);echo '</pre>';
  //echo '<pre>';print_r($properties);echo '</pre>';
  //echo '<pre>';print_r($categories);echo '</pre>';
  $dom = new domDocument("1.0", "windows-1251"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
  $root = $dom->createElement("Товары"); // Создаём корневой элемент
  $dom->appendChild($root);
  for ($i = 0; $i < count($tovars)-1; $i++) {
    $tovar = $dom->createElement("Товар"); 
    $tovar->setAttribute("Код", $tovars[codes][$i]); 
    $tovar->setAttribute("Название", $tovars[names][$i]); 
	for ($j = 0; $j < count($prices); $j++) {    
		$price = $dom->createElement("Цена", $prices[$i][value][$j]); 
		$price->setAttribute("Тип", $prices[$i][type][$j]); 
		$tovar->appendChild($price);
	}
	$propertyes=$dom->createElement("Свойства");
	for ($k = 0; $k < count($properties); $k++) {    
		$property = $dom->createElement('_'.$properties[$i][name][$k], $properties[$i][value][$k]);
		$propertyes->appendChild($property);
    }
	$tovar->appendChild($propertyes);
	$dcategories=$dom->createElement("Разделы");
	for ($n = 0; $n < count($categories); $n++) {    
	if ($categories[$i][parent_id][$n]===$categories[$i][parent_id][$n+1]) 
		$category = $dom->createElement('Раздел', $categories[$i][name][$n]);
	else {
		$category = $dom->createElement('Раздел', $categories[$i][name][$n]);
		$dcategories->appendChild($category);
	}$tovar->appendChild($dcategories);
    }
    $root->appendChild($tovar); 
  }
  $dom->save($a); // Сохраняем полученный XML-документ в файл*/
}
echo convertString('twostdddtwostbbbtwostaaa', 'twost');
$a=array(['a'=>2,'b'=>1],['a'=>1,'b'=>6],['a'=>8,'b'=>2],['a'=>9,'b'=>-4],['a'=>5,'b'=>3]);
echo '<pre>';print_r(mySortForKey($a,'a'));echo '</pre>';
importXml('2.xml');
exportXml('2export.xml','Принтер');

?>