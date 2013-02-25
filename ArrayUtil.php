<?php

namespace helpers;

/**
 * Description of Array
 *
 * @author Alexsandro
 */
class ArrayUtil {

	/**
	 * Remove item array by value
	 * @param string $val value search in array
	 * @param array $array 
	 * @return array 
	 */
	public function removeItemByValue($val, $array)
	{
		for ($x = 0; $x < count($array); $x++) {
			$i = array_search($val, $array);
			if (is_numeric($i)) {
				$array_temp = array_slice($array, 0, $i);
				$array_temp2 = array_slice($array, $i + 1, count($array) - 1);
				$array = array_merge($array_temp, $array_temp2);
			}
		}
		return $array;
	}

}

?>
