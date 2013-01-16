<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Amazon Web Services Utility Helper.
 *
 * @author Jason Nathan
 * @package realtasia-beta-0.8
 * @subpackage Helper
 * @copyright 2011 Realtasia Pte Ltd
 */
require_once MODPATH . 'kaws/vendor/aws-sdk-for-php/sdk.class.php';

class AWS
{

    public static function s3()
    {
        defined('AWS_KEY') or self::_initialise();
        return new AmazonS3(array('key' => '[REDACTED]', 'secret' => '[REDACTED]'));
    }

    public static function sns()
    {
        defined('AWS_KEY') or self::_initialise();
        return new AmazonSNS();
    }

    protected static function _initialise()
    {
        $config = Kohana::$config->load('amazon');
        foreach ($config as $key => $value)
        {
            define($key, $value);
        }
    }

}

?>
