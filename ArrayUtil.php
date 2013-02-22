<?php

namespace helpers;

/**
 * Description of Array
 *
 * @author Alexsandro
 */
class ArrayUtil
{

	public function removeItemByValue($value, $array)
	{
		if (in_array($value, $array)) {
			$pos = array_search($value, $array);
			array_slice($array, $pos, 1);
			return $array;
		}
		return false;
	}

}

?>
