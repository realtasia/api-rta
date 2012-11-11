<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Description of abstract
 *
 * @author Jason Nathan
 * @package RealtAsia API
 * @subpackage /Controller/Abstract
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Controller_Media extends Controller_Template {

    public $params;

    public function before()
    {
        parent::before();
        $currentDom = $_SERVER['HTTP_HOST'];
        $domParts   = explode('.', $currentDom);
        $rootDomain = $domParts[1] . '.' . $domParts[2];
        $rootURL    = 'http://' . $rootDomain;
        $currentURL = 'http://' . $currentDom;
        $icons = $rootURL . '/common/icons/';
        $icons2 = $rootURL . '/common/icons/2x/';
        $this->params = [
            'currentDom' => $currentDom,
            'domParts' => $domParts,
            'rootDomain' => $rootDomain,
            'rootURL' => $rootURL,
            'currentURL' => $currentURL,
            'icons' => $icons,
            'icons2' => $icons2
        ];

        $this->template->header = View::factory('header', $this->params);
    }

    public function action_index()
    {
        $this->template->body = View::factory('mediaLibrary', $this->params);
    }
}
