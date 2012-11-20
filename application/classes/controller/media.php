<?php

defined('SYSPATH') or die('No direct script access.');

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
    public $temp_dir      = 'uploads';
    /* The S3 Bucket we're using */
    protected $bucket        = 'static2.realtasia.com.sg';
    protected $uploaded_file = null;

    /* the different avatar sizes that realtasia uses */
    protected $_avatar_sizes = [150, 60, 40];

    /* the different thumbnail sizes that realtasia uses */

    //protected $_thumb_sizes = [165, 135];

    public function before()
    {
        parent::before();
        $currentDom   = $_SERVER['HTTP_HOST'];
        $domParts     = explode('.', $currentDom);
        $rootDomain   = $domParts[1] . '.' . $domParts[2];
        $rootURL      = 'http://' . $rootDomain;
        $currentURL   = 'http://' . $currentDom;
        $icons        = $rootURL . '/common/icons/';
        $icons2       = $rootURL . '/common/icons/2x/';
        $this->params = [
            'currentDom' => $currentDom,
            'domParts'   => $domParts,
            'rootDomain' => $rootDomain,
            'rootURL'    => $rootURL,
            'currentURL' => $currentURL,
            'icons'      => $icons,
            'icons2'     => $icons2
        ];

        $this->template->header = View::factory('header', $this->params);

        $this->temp_dir = APPPATH . $this->temp_dir;
        if (!file_exists($this->temp_dir))
        {
            @mkdir($this->temp_dir);
        }
    }

    protected function upload()
    {
        $chunk    = (int) Arr::get($_REQUEST, 'chunk', 0);
        $chunks   = (int) Arr::get($_REQUEST, 'chunks', 0);
        $filename = Arr::get($_REQUEST, 'name', '');
        $file     = Arr::get($_FILES, 'file');
        $type     = Arr::get($file, 'type');
        $content  = Arr::get($_SERVER, 'HTTP_CONTENT_TYPE', Arr::get($_SERVER, 'CONTENT_TYPE'));
        $names    = static::rename($filename, $type);
        $path     = $this->temp_dir . DIRECTORY_SEPARATOR . $names['new_name'];

        $this->clean_temp($path);

        if (strpos($content, "multipart") !== false)
        {
            if (isset($file['tmp_name']) && is_uploaded_file($file['tmp_name']))
            {
                // Open temp file
                $out = fopen("{$path}.part", $chunk === 0 ? "wb" : "ab");
                if ($out)
                {
                    // Read binary input stream and append it to temp file
                    $in = fopen($file['tmp_name'], "rb");

                    if ($in)
                    {
                        while ($buff = fread($in, 4096))
                            fwrite($out, $buff);
                    }
                    fclose($in);
                    fclose($out);
                    @unlink($file['tmp_name']);
                }
            }
        }
        else
        {
            // Open temp file
            $out = fopen("{$path}.part", $chunk === 0 ? "wb" : "ab");
            if ($out)
            {
                // Read binary input stream and append it to temp file
                $in = fopen("php://input", "rb");

                if ($in)
                {
                    while ($buff = fread($in, 4096))
                        fwrite($out, $buff);
                }

                fclose($in);
                fclose($out);
            }
        }

        // Check if file has been uploaded
        if (!$chunks || $chunk === $chunks - 1)
        {
            // Strip the temp .part suffix off
            rename("{$path}.part", $path);
        }

        if (file_exists($path))
        {
            $this->uploaded_file = Arr::merge($names, ['location' => $path, 'type'  => $type]);
        }

        return false;
    }

    public function action_upload()
    {
        $this->auto_render = false;
        $user_id           = '50a2318fb90fb898b7000002';

        while ($this->uploaded_file === null)
        {
            $this->upload();
            $file = $this->uploaded_file;
            if (is_array($file))
            {
                if(strpos($file['type'], 'image') !== false)
                {
                    //return $this->save($this->process_image($file, $user_id));
                }
                else
                {
                    //return $this->save($this->process_file($user_id));
                }
            }
        }

        die('did not happen');
    }

    protected function process_image($file, $user_id)
    {
        $image = Image::factory($file['location']);
        $i     = clone $image;

        // we can't work with images too big in size
        if ($i->width > 1200)
        {
            $i->resize(1200);
        }

        // trying to put a file instead of the stream for cache to work on s3
        $original['body']        = $i->render();
        $original['contentType'] = $i->mime;

        // save to s3
        $original_url = $this->saveS3($user_id, $file['new_name'], $original);

        $t = clone $image;
        $t->resize(NULL, 110);

        $thumb['body']        = $t->render();
        $thumb['contentType'] = $t->mime;
        $thumb_name = 'thumb-'. $file['new_name'];

        // save to s3
        $thumb_url = $this->saveS3($user_id, $thumb_name, $thumb);

        if ($original_url && $thumb_url)
        {
            return Arr::merge($file, [
                'original' => $original_url,
                'thumb' => $thumb_url
            ]);
        }

        return false;
    }

    protected function process_file($user_id)
    {
        $document = [
            'body' => $this->uploaded_file,
            'contentType' => $this->uploaded_file['type']
        ];

        $document_url = $this->saveS3($user_id, $this->uploaded_file['new_name'], $document);

        //$thumb =

        if ($document_url)
        {
            return Arr::merge($this->uploaded_file, [
                'original' => $document_url
            ]);
        }

        return false;
    }

    protected static function rename($file, $type)
    {
        $actual_name = substr($file, 0, strpos($file, '.'));
        $new_name    = md5($actual_name . uniqid());

        switch (strtolower($type))
        {
            case 'image/jpeg' : $ext = '.jpg';
                break;
            case 'image/gif' : $ext = '.gif';
                break;
            case 'image/png' : $ext = '.png';
                break;
            case 'image/bmp' : $ext = '.bmp';
                break;
            case 'application/pdf' : $ext = '.pdf';
                break;
        }

        return [
            'old_name' => $actual_name . $ext,
            'new_name' => $new_name . $ext
        ];
    }

    protected function clean_temp($path)
    {
        if (is_dir($this->temp_dir) && ($dir = opendir($this->temp_dir)))
        {
            while (($file = readdir($dir)) !== false)
            {
                $tmpfilePath = $this->temp_dir . DIRECTORY_SEPARATOR . $file;

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.part$/', $file) && ($tmpfilePath !== "{$path}.part"))
                {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }
    }

    /**
     * The actual method that saves processed images to S3. Sets cache control
     * headers and other required defaults for S3
     *
     * @param string $filename the new filename
     * @param array $metadata and array of extra information for the file
     * @param array $headers any possible extra headers to set
     * @return string the newly created URL of the file
     */
    public function saveS3($user_id, $filename, $metadata = [], $headers = [])
    {
        $exp_time = strtotime("+2 years");
        $expires  = $exp_time - time();
        $s3       = AWS::s3();
        //$s3->path_style = true;

        $header = Arr::merge(array(
                    'Expires'       => date('D, d M Y H:i:s \G\M\T', $exp_time),
                    'Cache-Control' => "max-age=$expires, public"
                        ), $headers);

        $meta = Arr::merge(array(
                    'body'        => NULL,
                    'contentType' => NULL,
                    'acl'         => $s3::ACL_PUBLIC,
                    'headers'     => $header
                        ), $metadata);

        $new_filename = $user_id . '/' . $filename;

        try
        {
            $r = $s3->create_object($this->bucket, $new_filename, $meta);
            return $r->isOK() ? $this->bucket . '/' . $new_filename : false;
        } catch (Exception $e)
        {
            Debug::log($e->getMessage());
        }

        return false;
    }

    /**
     * Utility method that validates upload
     *
     * @param array $files_array the $_FILES array
     * @return instance of the Validation object
     */
    protected static function verify_upload($files_array, $fieldname)
    {
        return Validation::factory($files_array)
                        ->rules($fieldname, array(
                            array('Upload::not_empty'),
                            array('Upload::valid'),
                            array('Upload::size', array(':value', '20M')),
                            array('Upload::type', array(':value', array('jpg', 'png', 'gif', 'jpeg', 'pdf'))),
                                )
        );
    }

    public function action_index()
    {
        //die(phpinfo());
//        $this->template->body = View::factory('mediaLibrary', $this->params)
//                ->set('leftbar', View::factory('leftbar'))
//                ->set('rightbar', View::factory('rightbarMember'));
        $this->template->leftbar = View::factory('leftbar');
        $this->template->content = View::factory('media_lib_content');
        $this->template->rightbar = View::factory('rightbarMember');
        $this->template->body = View::factory('mediaLibrary', $this->params);
    }

}
