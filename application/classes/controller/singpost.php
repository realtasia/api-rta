<?php

defined('SYSPATH') or die('No direct script access.');
ini_set("max_execution_time", 0);
ini_set('memory_limit', '2000M');

/**
 *
 * @author Jason Nathan
 * @category RealtAsia
 * @package RT_Controller
 * @subpackage Singpost
 * @version 1.0
 * @copyright 2012 Realtasia Pte Ltd
 * @uses Oauth2_Resource
 *
 */
class Controller_Singpost extends Controller {

    public function action_index()
    {
        $result     = false;
        $localities = $estates    = $children   = array('' => '');

        $estates    = array(0      => '', 'NULL' => 'NULL') + $this->action_get('estates');
        $localities = array(0      => '', 'NULL' => 'NULL') + $this->action_get('localities');
        $children   = array(0      => '', 'NULL' => 'NULL') + $this->action_get('children');
        //echo kohana::debug( $this->check_get( $_GET ) ); die;
        //if ($_GET && $this->check_get($_GET))
        //{
        $cols       = 'estates.estate, children.child, postcode.address_type AS address, postcode.number, streets.street_name As street, postcode.postal_code as postal, buildings.building_name AS building, localities.locality, old_table.name AS other_name, old_table.lng AS lon, old_table.lat';

        $query  = DB::select(DB::expr($cols))
          ->from('postcode')
          ->join('buildings', 'LEFT')
          ->on('buildings.building_key', '=', 'postcode.building_key')
          ->join('estates', 'LEFT')
          ->on('estates.id', '=', 'postcode.estate_key')
          ->join('localities', 'LEFT')
          ->on('localities.id', '=', 'postcode.locality_key')
          ->join('children', 'LEFT')
          ->on('children.id', '=', 'postcode.child_key')
          ->join('streets', 'LEFT')
          ->on('streets.street_key', '=', 'postcode.street_key')
          ->join('old_table', 'LEFT')
          ->on('old_table.postal', '=', 'postcode.postal_code');
        //$query = $this->search($query);
        //$query->where('old_table.lat', '>', 0);
        //echo $query; die();
        $result = $query->execute();

        // echo "<pre>";
        $count = 0;
        $mongo = Mongo_document::factory('singpost')->collection();
        //print_r($result[130371]);
        //die;
        foreach ($result as $row)
        {
            $row['address'] = $this->actual_type($row['address']);

            $upsert = [
                'estate'     => $row['estate'] ? $row['estate'] : NULL,
                'child'      => $row['child'] ? $row['child'] : NULL,
                'address'    => $row['address'] ? $row['address'] : NULL,
                'number'     => $row['number'] ? $row['number'] : NULL,
                'street'     => $row['street'] ? $row['street'] : NULL,
                'postal'     => (string) $row['postal'],
                'building'   => $row['building'] ? $row['building'] : NULL,
                'locality'   => $row['locality'] ? $row['locality'] : NULL,
                'other_name' => $row['other_name'] ? $row['other_name'] : NULL,
                'geo'        => [
                    'lon' => isset($row['lon']) ? $row['lon'] : NULL,
                    'lat' => isset($row['lat']) ? $row['lat'] : NULL
                ],
            ];

            $query = ['postal' => (int) $row['postal']];

            try
            {
                $mongo->insert($upsert);
                //$mongo->update_safe($query, $upsert, ['upsert' => true]);
                fwrite(STDOUT, "Processed Record Successfully: $count\r\n");
                $count++;
            }
            catch (MongoException $e)
            {
                fwrite(STDOUT, "Error Processing Record $count:  {$e->getMessage()}\r\n");
                fwrite(STDOUT, "\r\n\r\n");
                fwrite(STDOUT, "Query: " . print_r($query, true));
                fwrite(STDOUT, "\r\n\r\n");
                fwrite(STDOUT, "Update: " . print_r($upsert, true));
                exit(0);
            }
        }
        //echo "</pre>";
        die;
        //}

        $sectors = array(''   => '', "01" => "01", "03" => "03", "04" => "04", "05" => "05", "06" => "06", "07" => "07", "08" => "08", "09" => "09", "10" => "10", "11" => "11", "12" => "12", "13" => "13", "14" => "14", "15" => "15", "16" => "16", "17" => "17", "18" => "18", "19" => "19", "20" => "20", "21" => "21", "22" => "22", "23" => "23", "24" => "24", "25" => "25", "26" => "26", "27" => "27", "28" => "28", "29" => "29", "30" => "30", "31" => "31", "32" => "32", "33" => "33", "34" => "34", "35" => "35", "36" => "36", "37" => "37", "38" => "38", "39" => "39", "40" => "40", "41" => "41", "42" => "42", "43" => "43", "44" => "44", "45" => "45", "46" => "46", "47" => "47", "48" => "48", "49" => "49", "50" => "50", "51" => "51", "52" => "52", "53" => "53", "54" => "54", "55" => "55", "56" => "56", "57" => "57", "58" => "58", "59" => "59", "60" => "60", "61" => "61", "62" => "62", "63" => "63", "64" => "64", "65" => "65", "66" => "66", "67" => "67", "68" => "68", "69" => "69", "70" => "70", "71" => "71", "72" => "72", "73" => "73", "74" => "74", "75" => "75", "76" => "76", "78" => "78", "79" => "79", "80" => "80", "81" => "81", "82" => "82");
        $types   = array(''  => '', 'H' => 'HDB', 'K' => 'Block', 'C' => 'Condominium', 'S' => 'Standard', 'U' => 'Walkup', 'A' => 'Apartment');

        $this->request->response = View::factory('results')
          ->set('sectors', $sectors)
          ->set('localities', $localities)
          ->set('types', $types)
          ->set('estates', $estates)
          ->set('children', $children)
          ->set('count', count($result))
          ->set('loop', View::factory('loop')->set('rows', $result));
    }

    public function action_insertgeo()
    {
        echo "<pre>";
        $count = 0;
        $mongo = Mongo_document::factory('singpost')->collection();

        $upsert = [
            'geo' => [
                'lon' => null,
                'lat' => null
            ]
        ];

        $query = ['geo' => ['$exists' => false]];

        try
        {
            if ($mongo->update_safe($query, $upsert, ['upsert'   => false, 'multiple' => true]))
            {
                fwrite(STDOUT, "Processed Record Successfully: \r\n");
            }
        }
        catch (MongoException $e)
        {
            fwrite(STDOUT, "Processed Record Failed: {$e->getMessage()}\r\n");
        }

        //echo "<br />";
        //$mongo->clear();
        //echo "</pre>";
        die;
    }

    public function action_correctpostal()
    {
        $query  = ['geo'    => ['$exists' => false], 'postal' => ['$type' => 16]];
        $mongo  = Mongo_document::factory('singpost')->collection();
        $cursor = $mongo->find($query)->fields(['postal']);

        while ($cursor->hasNext())
        {
            $postalcodes[] = $cursor->getNext()->as_array()['postal'];
        }

        $count = 0;

        foreach ($postalcodes as $num_postal)
        {
            $upsert = [
                '$set' => ['geo'    => ['lon' => null, 'lat' => null], 'postal' => '0' . (string) $num_postal]
            ];
            try
            {
                if ($mongo->update_safe(['postal' => $num_postal], $upsert, ['upsert'   => false, 'multiple' => true]))
                {
                    fwrite(STDOUT, "Processed Record Successfully: $count\r\n");
                }
                $count++;
            }
            catch (MongoException $e)
            {
                fwrite(STDOUT, "Error processing $count: {$e->getMessage()}\r\n");
                exit(0);
            }
        }
        die;
    }

    public function action_update()
    {
        $field_options = array(''           => '', 'localities' => 'Localities', 'children'   => 'Child Groups', 'estates'    => 'Estates');
        $response      = View::factory('update')->set('field_options', $field_options);
        $return        = new stdClass();

        if ($_POST)
        {
            if ($field = Arr::get($_POST, 'field', false) || $value = Arr::get($_POST, 'value', false) || $ids   = Arr::get($_POST, 'id', false))
            {
                $field = inflector::singular($_POST['field']) . '_key';
                $value = Arr::get($_POST, 'value');
                $ids   = Arr::get($_POST, 'id');
                try
                {
                    if (strtoupper($value) == 'null' || trim($value) == '')
                        $value  = DB::expr('NULL');
                    //$query = DB::update('users')->set(array('username' => 'jane'))->where('username', '=', 'john');
                    $insert = DB::update('postcode')
                      ->set(array($field => $value))
                      ->where('postal_code', 'IN', $ids)
                      ->execute();

                    $return->body = "<p class='success'>Succesfully inserted! You should hit the Search button after closing this message to refresh.</p>";
                    $return->code = 1;
                }
                catch (Database_Exception $e)
                {

                    $return->body = "<p class='error'>I couldn't create that entry.<br />" . $e->getMessage() . "</p>";
                    $return->code = 0;
                }

                $response = json_encode($return);
            }
            else
            {
                $return->body = "<p class='error'>Erm... I don't think you wanna insert an empty name?</p>";
                $return->code = 0;
                $response     = json_encode($return);
            }
        }

        $this->request->response = $response;
    }

    public function action_delete()
    {
        $field_options = array(''           => '', 'localities' => 'Localities', 'children'   => 'Child Groups', 'estates'    => 'Estates');
        $response      = View::factory('delete')->set('field_options', $field_options);
        $return        = new stdClass();

        if ($_POST)
        {
            if ($field = Arr::get($_POST, 'field', false) || $value = Arr::get($_POST, 'value', false))
            {
                $value = Arr::get($_POST, 'value');
                try
                {
                    //$query = DB::update('users')->set(array('username' => 'jane'))->where('username', '=', 'john');
                    $insert = DB::delete($_POST['field'])
                      ->where('id', '=', $value)
                      ->execute();

                    $return->body = "<p class='success'>Succesfully deleted! You should hit the Search button after closing this message to refresh.</p>";
                    $return->code = 1;
                }
                catch (Database_Exception $e)
                {

                    $return->body = "<p class='error'>I couldn't delete that entry.<br />" . $e->getMessage() . "</p>";
                    $return->code = 0;
                }

                $response = json_encode($return);
            }
            else
            {
                $return->body = "<p class='error'>Erm... I don't think you wanna delete an empty name?</p>";
                $return->code = 0;
                $response     = json_encode($return);
            }
        }

        $this->request->response = $response;
    }

    public function action_create()
    {
        $field_options = array('' => '', 'localities' => 'Localities', 'children'   => 'Child Groups', 'estates'    => 'Estates');
        $response      = View::factory('create')->set('field_options', $field_options);
        $return        = new stdClass();


        if ($_POST)
        {
            if ($value = Arr::get($_POST, 'value', false))
            {
                $field = inflector::singular($_POST['field']);
                try
                {
                    $insert       = DB::insert($_POST['field'])
                      ->columns(array($field))
                      ->values(array($value))
                      ->execute();
                    $label        = ucfirst($field);
                    $return->body = "<p class='success'>'$value' <strong>$label</strong> was succesfully inserted! You should hit the Search button after closing this message to refresh.</p>";
                    $return->code = 1;
                }
                catch (Database_Exception $e)
                {

                    $return->body = "<p class='error'>I couldn't create that entry.<br />" . $e->getMessage() . "</p>";
                    $return->code = 0;
                }

                $response = json_encode($return);
            }
            else
            {
                $return->body = "<p class='error'>Erm... I don't think you wanna insert an empty name?</p>";
                $return->code = 0;
                $response     = json_encode($return);
            }
        }

        $this->request->response = $response;
    }

    public function search($obj = NULL)
    {

        if (Arr::get($_GET, 'postal', false) || Arr::get($_GET, 'num', false) || Arr::get($_GET, 'street', false))
        {
            if ($postal = Arr::get($_GET, 'postal', false))
                $obj->and_where('postcode.postal_code', '=', (int) $postal);
            if ($num    = Arr::get($_GET, 'num', false))
                $obj->and_where('postcode.number', '=', $num);
            if ($street = Arr::get($_GET, 'street', false))
                $obj->and_where('streets.street_name', '=', $street);
        }
        else
        {
            if ($sectors = ARR::get($_GET, 'sectors', false))
            {
                $sectors = $this->_oper($sectors);
                $obj->and_where(DB::expr('LEFT(postcode.postal_code, 2)'), $sectors[0], $sectors[1]);
            }
            if ($types = ARR::get($_GET, 'types', false))
            {
                $types = $this->_oper($types);
                $obj->and_where('postcode.address_type', $types[0], $types[1]);
            }
            if ($buildings = ARR::get($_GET, 'buildings', false))
            {
                $buildings = $this->_oper($buildings);
                $obj->and_where('buildings.building_name', $buildings[0], $buildings[1]);
            }

            $rta_fields = array('estates', 'localities', 'children');

            foreach ($rta_fields as $field)
            {
                if ($_field = ARR::get($_GET, $field, false))
                {
                    $_field = $this->_oper($_field);
                    $key    = inflector::singular($field) . '_key';
                    $obj->and_where("postcode.$key", $_field[0], $_field[1]);
                }
            }

            if ($s = ARR::get($_GET, 's', false))
            {
                $s_arr = explode('|', $s);
                if (!$s_arr || empty($s_arr))
                {
                    $obj->and_where_open()
                      ->where('streets.street_name', 'RLIKE', $s)
                      ->or_where('buildings.building_name', 'RLIKE', $s)
                      ->and_where_close();
                }
                else
                {
                    foreach ($s_arr as $keyword)
                    {
                        $keyword = trim($keyword);

                        if (strpos($keyword, '-') === 0)
                        {
                            $term = trim(substr($keyword, 1));
                            $obj->and_where_open()
                              ->where('streets.street_name', 'NOT RLIKE', $term)
                              ->or_where('streets.street_name', 'IS', NULL)
                              ->and_where_close()
                              ->and_where_open()
                              ->where('buildings.building_name', 'NOT RLIKE', $term)
                              ->or_where('buildings.building_name', 'IS', NULL)
                              ->and_where_close();
                        }
                        else
                        {
                            $obj->and_where_open()
                              ->where('streets.street_name', 'RLIKE', $s)
                              ->or_where('buildings.building_name', 'RLIKE', $s)
                              ->and_where_close();
                        }
                    }
                }
            }
        }
        //echo kohana::debug(strpos( $keyword, '-') === 0);die();
        return $obj;
    }

    public function check_get($get_array)
    {
        $arr = array_filter($get_array);

        foreach ($arr as $k => $v)
        {
            //echo kohana::debug( $k, ( trim( $v ) == ''), $v ); //die;
            if (is_numeric($v) && $v == 0)
            {
                unset($arr[$k]);
            }
            if ($v == 'Search')
            {
                unset($arr[$k]);
            }
            if (trim($v) == '')
            {
                unset($arr[$k]);
            }
        }

        return (bool) count($arr) > 0;
        //return $v == 0;
    }

    protected function _oper($item)
    {
        if (is_numeric($item))
            return array('=', (int) $item);
        elseif ($item == 'null' || $item == 'NULL')
            return array('IS', NULL);
        else
            return array('=', $item);
    }

    public static function format($col)
    {
        foreach ($col as $k => $v)
        {
            $col[$k] = ucwords(strtolower($v));
        }

        if (isset($col['address_type']))
        {
            $col['address_type'] = self::actual_type($col['address_type']);
        }

        return $col;
    }

    public function action_get($component)
    {
        $return = array();

        switch ($component)
        {
            case 'localities':
                $locale_res = DB::query(Database::SELECT, "SELECT * FROM localities")->execute();
                foreach ($locale_res as $row)
                {
                    $return[$row['id']] = $row['locality'];
                };
                break;

            case 'estates':
                $estates_res = DB::query(Database::SELECT, "SELECT * FROM estates")->execute();
                foreach ($estates_res as $row)
                {
                    $return[$row['id']] = $row['estate'];
                };
                break;

            case 'children':
                $children_res = DB::query(Database::SELECT, "SELECT * FROM children")->execute();
                foreach ($children_res as $row)
                {
                    $return[$row['id']] = $row['child'];
                };
                break;
        }

        asort($return);

        if ($this->request->is_ajax())
        {
            $obj    = array();
            $return = array('NULL' => 'NULL') + $return;

            if (!empty($return))
            {
                $return = array_filter($return);
                foreach ($return as $k => $v)
                {
                    $obj[] = array('id'  => $k, 'val' => $v);
                }
            }

            echo json_encode($obj);
        }
        else
        {
            return $return;
        }
    }

    public static function actual_type($type)
    {
        switch ($type)
        {
            case 'H': return "HDB";
                break;
            case 'HDB': return "H";
                break;
            case 'K': return "Block";
                break;
            case 'Block': return "K";
                break;
            case 'C': return "Condominium";
                break;
            case 'Condominium': return "C";
                break;
            case 'S': return "Standard";
                break;
            case 'Standard': return "S";
                break;
            case 'U': return "Walkup";
                break;
            case 'Walkup': return "U";
                break;
            case 'A': return "Apartment";
                break;
            case 'Apartment': return "A";
                break;
            case 'P': return "PO Box";
                break;
            case 'W': return "Window Delivery";
                break;
            case 'B': return "Locked Bag";
                break;
            case 'G': return "Godown";
                break;
        }
    }

    public function action_geocode()
    {
        $skip  = 0;
        $mongo = Mongo_document::factory('singpost')->collection();

        $cursor = $mongo->find(['geo.lon' => null])->sort('_id', 1);
        //$cursor = $mongo->find(['geo.lon' => '0.000000']);
        //fwrite(STDOUT, count($cursor) . "\r\n");
        //die;

        $i     = $skip;
        $start = new DateTime(date('r', time()));
        while ($cursor->hasNext())
        {
            $row = $cursor->getNext();
            sleep(2);
            $geo = $this->geocode($row);
            if (is_array($geo))
            {
                $accuracy          = $geo['accuracy'];
                unset($geo['accuracy']);
                $row->geo          = $geo;
                $row->geo_accuracy = $accuracy;
                //$row->set('geo.lon', $geo['lon']);
                //$row->set('geo.lat', $geo['lat']);
                try
                {
                    $row->save();
                    $now      = new DateTime(date('r', time()));
                    $interval = $now->diff($start);
                    $minutes  = $interval->format('%h hour(s), %i minute(s) & %s seconds');
                    fwrite(STDOUT, "Processed Record $i:  $minutes so far... \r\n");
                    fwrite(STDOUT, "\t Postal Code:  . {$row->postal} \r\n");
                }
                catch (MongoException $e)
                {
                    fwrite(STDOUT, "Error processing $i:  {$e->getMessage()} \r\n");
                }
            }
            else
            {
                $geo = (array) $geo;
                $geo = Arr::merge($row->as_array(), $geo);
                fwrite(STDOUT, print_r($geo, true) . "\r\n");
                //exit(0);
            }
            $i++;
        }

        fwrite(STDOUT, "No Records Found \r\n");
    }

    public $host = 'http://maps.google.com.sg';
    public $key  = '[REDACTED]';
    public $base_url;

    public function geocode($obj_add)
    {
        $this->base_url = $this->host . "/maps/geo?output=json&language=en" . "&key=" . $this->key;
        //$xml = simplexml_load_file( $this->base_url . "&q=" . urlencode( $postal ) );
//        $response       = json_decode(file_get_contents($this->base_url . "&q=" . urlencode('Singapore ' . $obj_add->postal)));
//        $delay          = 0;
//        $status         = $response->Status->code;
//        //print_r($response);
//
//
//        if ($status == 200)
//        {
//            $accuracy = $response->Placemark[0]->AddressDetails->Accuracy;
//            if ($accuracy >= 5)
//            {
        //sleep(1);
        $response       = json_decode(file_get_contents($this->base_url . "&q=" . urlencode($obj_add->number . ' ' . $obj_add->street . ' Singapore')));
//            }
//        }
//        if ($response->Status->code === 620)
//        {
//            sleep(1);
//            $response = json_decode(file_get_contents($this->base_url . "&q=" . urlencode($obj_add->number . ' ' . $obj_add->street . ' Singapore')));
//        }

        if ($response->Status->code !== 200)
        {
            fwrite(STDOUT, $response->Status->code);
            exit(0);
        }

        //print_r($response);
        //die;
        //return;
        //return($response);
        $accuracy = (int) $response->Placemark[0]->AddressDetails->Accuracy;
        if ($response->Placemark[0]->Point->coordinates[0] > 100 && ($accuracy >= 4))
        {
            return [
                'lon'      => $response->Placemark[0]->Point->coordinates[0],
                'lat'      => $response->Placemark[0]->Point->coordinates[1],
                'accuracy' => $accuracy,
            ];
        }

        if (isset($response->Placemark[1]))
        {
            $accuracy = (int) $response->Placemark[1]->AddressDetails->Accuracy;
            if ($response->Placemark[1]->Point->coordinates[0] > 100 && ($accuracy >= 4))
            {
                return [
                    'lon'      => $response->Placemark[1]->Point->coordinates[0],
                    'lat'      => $response->Placemark[1]->Point->coordinates[1],
                    'accuracy' => $accuracy,
                ];
            }
        }

        return $response;
    }

}

// End Welcome
