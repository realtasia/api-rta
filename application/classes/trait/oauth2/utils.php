<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Description of utils
 *
 * @author Jason Nathan
 * @package RealtAsia API
 * @subpackage /Trait/oAuth2/Utils
 * @copyright 2012 Realtasia Pte Ltd
 */
trait Trait_Oauth2_Utils {

    public function stringify(array $arr)
    {
        foreach($arr as $k => $v)
        {
            if($v instanceof MongoID)
            {
                $arr[$k] = $v->__toString();
            }
        }
        return $arr;
    }

    protected function get_current_user_fields()
    {
        if(isset($this->current_user))
        {
            $user = $this->current_user->as_array();
            return [
                '_id' => $user['_id'],
                'name' => $user['name'],
            ];
        }
    }

    /**
     * Perform a hmac hash, using the configured method.
     *
     * @param   string  string to hash
     * @return  string
     */
    public static function hash($str)
    {
        return hash_hmac('sha256', $str, 'pS3*Z2f9Ow<}^H6L,{<dS<><ld; 6|xAkwg3MgakYd}>t:KZgjjDx4U@/}#XO+]*');
    }

}
