<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Description of arr
 *
 * @author Jason Nathan
 * @category RealtAsia
 * @package RT_Helpers
 * @subpackage Arr
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Arr extends Kohana_Arr {

    /**
     * Returns a multidimensional array if there delimiters in the each key.
     * All non-delimited keys are returned with their respective values.
     * e.g
     * // returns $values[one][two][three] = $value
     * $values = Arr::delim_to_multi(array('one.two.three' => value))
     *
     * @param array $arr
     * @param string $delimiter
     * @return array
     */
    public static function delim_to_multi($arr, $delimiter = NULL)
    {
        $return = [];
        if ($delimiter == NULL)
        {
            $delimiter = parent::$delimiter;
        }

        if(is_array($arr))
        {
            foreach ($arr as $key => $value)
            {
                // ignoring int 0 (false negative). Delimiter cannot start key.
                if (strpos($key, $delimiter))
                {
                    $keys = $parts = explode($delimiter, $key);
                    $count = count($parts) - 1;
                    while($bottom = array_pop($parts))
                    {
                        if(count($parts) === $count)
                        {
                            $return = [$bottom => $value];
                        }
                        else
                        {
                            $return = [$bottom => $return];
                        }

                    }
                }
                else
                {
                    $return[$key] = $value;
                }
            }
        }

        return $return;
    }

    /**
     * returns a single dimension numeric array of values from an multidim array
     *
     * @param array $arr
     * @return array
     */
    public static function flatten_recursive(array $arr)
    {
        if($arr) {
            $flat = [];
            foreach(new RecursiveIteratorIterator(new RecursiveArrayIterator($arr), RecursiveIteratorIterator::SELF_FIRST) as $key=>$value) {
                if(!is_array($value)) {
                    $flat[] = $value;
                }
            }

            return $flat;
        }
        return [];
    }
    
    /**
     * Sorts an associative array with an array of keys
     * 
     * @param array $array
     * @param array $keys
     * @return array
     */
    public static function order(array $array, array $keys)
    {
        if(!\Arr::is_assoc($array))
            return $array;
        
        $ordered = [];
        
        foreach($keys as $key)
        {
            if(array_key_exists($key, $array))
            {
                $ordered[$key] = $array[$key];
                unset($array[$key]);
            }
        }
        
        return $ordered + $array;
    }
}
