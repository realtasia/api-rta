<?php defined('SYSPATH') or die('No direct script access.');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of corrections
 *
 * @author jason
 */
class Controller_Corrections extends Controller {
        
    public function action_pages()
    {
        $cursor = Collection::factory('page')->find(['type' => 'location', 'subtype' => 'Estate'], [
            'type' => 1, 'subtype' => 1, 'parent' => 1, 'property_type' => 1
        ])->limit(1000);
        $return = [];
        
        while ($cursor->hasNext())
        {
            $row = $cursor->getNext();
            $keywords = [];            
            
//            if($row->type === 'location' && $row->subtype !== 'Estate')
//            {
//                $s = Collection::factory('singpost');
//                $q = ['child.name' => $row->name];
//                $streets = $s->distinct('street', $q);
//                $s->reset();                
//                $keywords = array_merge($keywords, $streets);
//            }
            
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
            $return [] = $keywords;           
        } 
        
            echo Debug::vars($return);        
            
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
}

?>
