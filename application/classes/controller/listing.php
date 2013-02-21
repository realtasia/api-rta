<?php

class Controller_Listing extends Controller{

    public function action_keyword()
    {
        $s = strip_tags($this->request->query('s'));

        if(empty($s))
            return $this->error('invalid_request');

        $r = [];

        $cursor = Collection::factory('singpost')
                ->find([
                    'estate' => ['$ne' => null],
                    'keywords' => new MongoRegex("/^".strtolower($s)."/")
                    ])
                ->fields(['keywords', 'estate', 'child', 'number', 'postal', 'street'])
                ->limit(50);

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
}