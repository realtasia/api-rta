<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Amazon Web Services Utility Helper.
 *
 * @author Jason Nathan
 * @category RealtAsia API
 * @package Helpers
 * @subpackage AWS
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
require 'AWSSDKforPHP/aws.phar';

 use Aws\S3\S3Client;
 use Aws\Common\Enum\Region;
class AWS {

    public static function s3()
    {
        return S3Client::factory([
            'key'    => '[REDACTED]',
            'secret' => '[REDACTED]',
            'region' => Region::US_EAST_1
        ]);
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
