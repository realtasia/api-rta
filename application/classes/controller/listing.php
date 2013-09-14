<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Description of corrections
 *
 * @author Jason Nathan
 * @category RealtAsia
 * @package RT_Controller
 * @subpackage Listing
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Controller_Listing extends Controller_Post{

    public function action_keyword()
    {
        $s = strip_tags($this->request->query('s'));

        if(empty($s))
            return $this->error('invalid_request');

        $r = [];

        $cursor = Collection::factory('singpost')
                ->find([
                    'estate' => ['$ne' => null],
                    'child._id' => ['$exists' => true],
                    'keywords' => new MongoRegex("/^".strtolower($s)."/")
                    ])
                ->fields(['keywords', 'estate', 'child', 'number', 'postal', 'street'])
                ->limit(50)
                ->slaveOkay();

        while($cursor->hasNext())
        {
            $row = $cursor->getNext();
            $k   = $row->keywords;

            foreach($k as $value)
            {
                if(strpos($value,strtolower($s)) !== false)
                {
                    $trimmed = trim(ucwords($value));
                    $r[] = [
                        'label' => $trimmed,
                        'value' => $trimmed,
                        'child' => empty($row->child) ? null : $row->stringify((array)$row->child),
                        'estate'=> $row->estate,
                        'address' => $row->number . " " . ucwords(strtolower($row->street))
                    ];
                }
            }
        }

        $data = array_values(array_combine(array_map(function($i){
            return $i['label'];
        }, $r), $r));

        if(count($data) > 15)
            return $this->data(array_slice($data, 0, 15));

        return $this->data($data);
    }

    public function action_attachments()
    {
        $data = [];
        $ids = \Arr::map('Document::check_id', \Arr::get(static::$params, '_ids', []));

        $attachments = Collection::factory('post')->find(['_id' => ['$in' => $ids]]);

        while($attachments->hasNext())
        {
            $a = $attachments->getNext();
            if(parent::logged_in())
            {
                $a->current_user = (array) $this->get_user();
            }

            $data[] = $a->stringify($a->as_array());
        }

        return $this->data($data);
    }

    public function action_search()
    {
        if(parent::logged_in())
            $listings = Collection::factory('post')->search(static::$params, static::$user_id)->slaveOkay();
        else
            $listings = Collection::factory('post')->search(static::$params)->slaveOkay();

        return $this->data($pages);
    }
}