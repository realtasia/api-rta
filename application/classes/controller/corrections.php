<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Description of corrections
 *
 * @author Jason Nathan
 * @category RealtAsia
 * @package RT_Controller
 * @subpackage Corrections
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 */
class Controller_Corrections extends Controller {

    public function action_pages()
    {
        $cursor = Collection::factory('page')->find();

        while ($cursor->hasNext())
        {
            $row = $cursor->getNext();
            $keywords = [];

            if($row->type === 'location' && $row->subtype !== 'Estate')
            {
                $s = Collection::factory('singpost');
                $q = ['child.name' => $row->name];
                $streets = $s->distinct('street', $q);
                $s->reset();
                $keywords = array_merge($keywords, $streets);
            }

            $keywords[] = $row->name;

            $parent_name = Arr::get($row->parent, 'name');
            if(isset($row->property_type))
            {
                $keywords[] = $row->property_type;
            }

            if($parent_name)
            {
                $keywords[] = $parent_name;
            }

            $keywords = array_filter(array_map('strtolower', \Arr::flatten($keywords)));
            $row->set('keywords', $keywords);
            $row->save();
            fwrite(STDOUT, "Adding the following to {$row->name}: \n");
            fwrite(STDOUT, implode(', ', $keywords) . "\n");
        }
    }   

    public function action_postIndex()
    {
        $r = Collection::factory('singpost')->collection()->find([
            'keywords' => new MongoRegex("/^0/"),
        ]); 
        
        fwrite(STDOUT, print_r(iterator_to_array($r), true) . "\n\n\n");
    }
    
    public function action_postal()
    {
        $cursor = Collection::factory('singpost')->find();
        $keywords = [];

        while ($cursor->hasNext())
        {
            $row = $cursor->getNext();
            $a = $row->as_array();
            
            $keywords[] = \Arr::get($a, 'building');
            $keywords[] = \Arr::get($a, 'estate');
            $keywords[] = \Arr::get($a, 'locality');
            $keywords[] = \Arr::get($a, 'number', '') . ' ' . \Arr::get($a, 'street', '');
            $keywords[] = \Arr::get($a, 'postal');
            $keywords[] = \Arr::get($a, 'other_name');
            $keywords[] = \Arr::path($a, 'child.name');

            $keywords = array_filter(array_map('trim', \Arr::flatten($keywords)));
            $keywords = array_values(array_unique(array_map('strtolower', $keywords), SORT_STRING));

            $row->set('keywords', $keywords);
            $row->save();
            fwrite(STDOUT, "Adding the following to {$row->_id}: \n");
            fwrite(STDOUT, print_r($keywords, true) . "\n\n\n");
            $keywords = [];
        }
    }    
    
    public function action_estates()
    {
        Collection::factory('page')->remove(['_id' => new MongoID('50ed26deb90fb8c817000049')]);
        $return = [];
        $cursor = Collection::factory('page')->find(['type' => 'location','subtype' => 'Estate', 'name' => new MongoRegex("/^Jurong West/")])
                ->fields(['name']);
        while($cursor->hasNext())
        {
            $estate = $cursor->getNext();
            $children = Collection::factory('page')->find([
                'type' => 'location', 'parent._id' => $estate->_id
            ])->fields(['name', 'parent.name']);

            $return[(string)$estate->_id]['name'] = $estate->name;
            $return[(string)$estate->_id]['count'] = $children->count();
            $return[(string)$estate->_id]['children'] = $children->as_array(false);
        }

        echo Debug::vars($return);
    }

    public function action_cleanup()
    {
        //http://static2.realtasia.com.sg/50f81f30d0729dc509000016/40/9a0d765af4214f907dee6d1f9b333729.jpg
        $user = Document::factory('user', '5106694e6c01992d14000011');
        $user->load();
        $lib  = Document::factory('page', $user->library['_id']);
        $del = Collection::factory('page')->remove_safe([
//            'user._id' => null,
            'type' => null,
//            '$or' => [
//                ['name' => ['$nin' => ['Profile Pictures', 'Private Uploads']]],
//                ['page.name' => '']
//            ]
        ], ['multiple' => true]);
        print_r($del);
        $lib->set('counts.albums.public', 1);
        $lib->save();
        $user->set('avatar', null);
        $user->set('counts.attachments', 1);
        $user->save();
    }
}