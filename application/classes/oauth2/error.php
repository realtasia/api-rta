<?php
/**
 * Description of error
 *
 * @author jj
 */
class Oauth2_Error {

    /**
     * Shortcut method to return a formatted error object. Uses keys in the
     * oauth2 message file definitions
     *
     * @uses APPPATH/messages/oauth2.php
     * @param string $key
     * @return array
     */
    public static function create($key)
    {
        $message = Kohana::message('oauth2', 'message.'.$key);
        return [
            'error' => $key,
            'error_description' => $message
        ];
    }
}
