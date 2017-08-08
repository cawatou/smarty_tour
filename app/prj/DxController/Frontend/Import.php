<?php
DxFactory::import('DxController_Frontend');

class DxController_Frontend_Import extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.import.sletat.countries' => 'sletatCountries',
        '.import.sletat.resorts'   => 'sletatResorts',
        '.import.sletat.hotels'    => 'sletatHotels',

        '.recheck.hotels'    => 'recheckHotels',

        '.import.merge.hotels'    => 'mergeHotels',
        '.import.merge.companion' => 'mergeCompanion',
        '.import.merge.news'      => 'mergeNews',
        '.import.merge.feedback'  => 'mergeFeedback',
        '.import.merge.faq'       => 'mergeFaq',

        '.import.merge.feedback_hotels' => 'mergeFeedbackHotels',
    );

    protected $file = 'Dictionaries.xml';

    protected $map_countries = array();

    /**
     * @param DxAppContext       $ctx
     * @param DxCommandHook|null $hook
     */
    public function __construct(DxAppContext $ctx, DxCommandHook $hook = null)
    {
        parent::__construct($ctx, $hook);

        ini_set('memory_limit', '768M');
        set_time_limit(0);
    }

    protected function sletatCountries()
    {
        $dictionary = $this->getDictionary();

        DxFactory::import('Utils_NameMaker');

        $temp_dictionary = $dictionary['countries']['country'];

        $i = $j = $k = 0;

        $used_dictionary = array();

        foreach ($temp_dictionary as $tmp) {
            $tmp = $tmp['@attributes'];

            if (!empty($this->map_countries)) {
                if (!in_array($tmp['id'], $this->map_countries)) {
                    continue;
                }
            }

            $used_dictionary[$tmp['name']] = array(
                'id'   => $tmp['id'],
                'name' => $tmp['name'],
            );
        }

        foreach ($used_dictionary as $country) {
            if (empty($country['id'])) {
                continue;
            }

            if (!empty($this->map_countries)) {
                if (!in_array($country['id'], $this->map_countries)) {
                    continue;
                }
            }

            $data = array(
                'country_external_id' => $country['id'],
                'country_title'       => $country['name'],
                'country_brief'       => ' ',
                'country_alias'       => Utils_NameMaker::cyrillicToLatin($country['name'], true),
            );

            $country = $this->getCountryByExternalId($country['id']);

            try {
                $this->writeToDatabase('country', $data, $country);
                $i++;

                if ($country !== null) {
                    $k++;
                }
            } catch (DxException $e) {
                $j++;
                continue;
            }
        }

        die(var_dump("Modified countries: {$i}. Created: ". ($i - $k) .", updated: {$k}, errors: {$j}"));

        DxApp::terminate();
    }

    protected function sletatResorts()
    {
        $dictionary = $this->getDictionary();

        DxFactory::import('Utils_NameMaker');

        $temp_dictionary = $dictionary['resorts']['resort'];

        $i = $j = $k = 0;

        /** @var $q DomainObjectQuery_Country */
        $q = DxFactory::getSingleton('DomainObjectQuery_Country');

        $_countries = $q->getAll();
        $countries  = array();

        foreach ($_countries as $_c) {
            $countries[$_c['country_external_id']] = $_c;
        }

        unset($_countries);

        $used_dictionary = array();

        foreach ($temp_dictionary as $tmp) {
            $tmp = $tmp['@attributes'];

            if (!empty($this->map_countries)) {
                if (!in_array($tmp['countryId'], $this->map_countries)) {
                    continue;
                }
            }

            if (!empty($this->map_resorts)) {
                if (!in_array($tmp['id'], $this->map_resorts)) {
                    continue;
                }
            }

            if (empty($countries[$tmp['countryId']])) {
                continue;
            }

            $used_dictionary[$tmp['name']] = array(
                'id'            => $tmp['id'],
                'title'         => $tmp['name'],
                'country_id'    => $tmp['countryId'],
                'country_title' => $countries[$tmp['countryId']]['country_title'],
            );
        }

        foreach ($used_dictionary as $resort) {
            if (empty($resort['id'])) {
                continue;
            }

            if (!empty($this->map_countries)) {
                if (!in_array($resort['country_id'], $this->map_countries)) {
                    continue;
                }
            }

            if (!empty($this->map_resorts)) {
                if (!in_array($resort['id'], $this->map_resorts)) {
                    continue;
                }
            }

            $country = $this->getCountryByExternalId($resort['country_id']);

            // We don't need resorts without country
            if ($country === null) {
                continue;
            }

            $data = array(
                'resort_external_id' => $resort['id'],
                'country_id'         => $country->getId(),
                'country_title'      => $country->getTitle(),
                'resort_title'       => $resort['title'],
                'resort_brief'       => ' ',
                'resort_alias'       => Utils_NameMaker::cyrillicToLatin($resort['title'] .'_'. $resort['id'], true),
            );

            $resort_obj = $this->getResortByExternalId($resort['id']);

            try {
                $this->writeToDatabase('resort', $data, $resort_obj);
                $i++;

                if ($resort_obj !== null) {
                    $k++;
                }
            } catch (DxException $e) {
                $j++;
                continue;
            }

            unset($country, $resort_obj);
        }

        die(var_dump("Modified resorts: {$i}. Created: ". ($i - $k) .", updated: {$k}, errors: {$j}"));

        DxApp::terminate();
    }

    protected function sletatHotels()
    {
        $dictionary = $this->getDictionary();

        DxFactory::import('Utils_NameMaker');

        $_stars = $dictionary['stars']['star'];
        $stars  = array();

        foreach ($_stars as $star) {
            $star = $star['@attributes'];

            $stars[$star['id']] = $star['name'];
        }

        $temp_dictionary = $dictionary['hotels']['hotel'];

        $i = $j = $k = 0;

        /** @var $q DomainObjectQuery_Resort */
        $q = DxFactory::getSingleton('DomainObjectQuery_Resort');

        $_resorts = $q->getAll();
        $resorts  = array();

        foreach ($_resorts as $_r) {
            $resorts[$_r['resort_external_id']] = $_r;
        }

        unset($_resorts);

        /** @var $q DomainObjectQuery_Hotel */
        $q = DxFactory::getSingleton('DomainObjectQuery_Hotel');

        $existing_hotels = $q->getExternalIdKeys();

        foreach ($temp_dictionary as $tmp) {
            $tmp = $tmp['@attributes'];

            // Already imported
            if (isset($existing_hotels[$tmp['id']])) {
                continue;
            }

            if (empty($resorts[$tmp['resortId']])) {
                continue;
            }

            if (!empty($this->map_hotels)) {
                if (!in_array($tmp['id'], $this->map_hotels)) {
                    continue;
                }
            }

            $data = array(
                'hotel_title'       => $tmp['name'],
                'hotel_external_id' => $tmp['id'],
                'hotel_stars'       => (empty($stars[$tmp['starId']]) ? null : $stars[$tmp['starId']]),

                'country_id'    => $resorts[$tmp['resortId']]['country_id'],
                'country_title' => $resorts[$tmp['resortId']]['country_title'],
                'resort_id'     => $resorts[$tmp['resortId']]['resort_id'],
                'resort_title'  => $resorts[$tmp['resortId']]['resort_title'],
            );

            $hotel_obj = $this->getHotelByExternalId($data['hotel_external_id']);

            try {
                $this->writeToDatabase('hotel', $data, $hotel_obj);
                $i++;

                if ($hotel_obj !== null) {
                    $k++;
                }
            } catch (DxException $e) {
                $j++;
                continue;
            }

            unset($hotel_obj);
        }

        die(var_dump("Modified hotels: {$i}. Created: ". ($i - $k) .", updated: {$k}, errors: {$j}"));

        DxApp::terminate();
    }

    public function writeToDatabase($table_name, array $data, DomainObjectModel $entry = null)
    {
        $table_name = str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($table_name))));

        if ($entry === null) {
            /** @var $model DomainObjectQuery_{$table_name} */
            $model = DxFactory::getInstance('DomainObjectModel_'. $table_name);

            $model->fromArray($data);

            return $model->save();
        }

        $entry->merge($data);

        return $entry->save();
    }

    public function getCountryByExternalId($external_id)
    {
        /** @var $q DomainObjectQuery_Country */
        $q = DxFactory::getSingleton('DomainObjectQuery_Country');

        return $q->findByExternalId($external_id);
    }

    public function getResortByExternalId($external_id)
    {
        /** @var $q DomainObjectQuery_Resort */
        $q = DxFactory::getSingleton('DomainObjectQuery_Resort');

        return $q->findByExternalId($external_id);
    }

    public function getHotelByExternalId($external_id)
    {
        /** @var $q DomainObjectQuery_Hotel */
        $q = DxFactory::getSingleton('DomainObjectQuery_Hotel');

        return $q->findByExternalId($external_id);
    }

    /**
     * Get whole dictionary from either a local file or from sletat.ru
     *
     * @return array
     */
    public function getDictionary()
    {
        $xml = file_get_contents($this->file);

        if (empty($xml)) {
            return $this->errorNotification('getDictionary: Empty XML received');
        }

        $xml = (array)@simplexml_load_string($xml);
        $xml = json_decode(json_encode($xml), true);

        return $xml;
    }

    protected function mergeHotels()
    {
        $xml_grids = <<<XML
XML;
        $xml_grids = (array)simplexml_load_string($xml_grids);
        $xml_grids = json_decode(json_encode($xml_grids), true);
        $xml_grids = $xml_grids['mgt_p102_setka'];

        $xml_city = <<<XML
XML;
        $xml_city = (array)simplexml_load_string($xml_city);
        $xml_city = json_decode(json_encode($xml_city), true);
        $xml_city = $xml_city['mgt_p102_countries'];

        $formatted_locations = array();

        foreach ($xml_city as $loc) {
            $formatted_locations[$loc['id']] = trim($loc['name']);
        }

        $dbo = DxApp::getComponent(DxConstant_Project::ALIAS_DOMAIN_OBJECT_DBO);

        $hotels_old = $dbo->getAssoc('SELECT * FROM `mgt_p102_hotels`');
        $_countries = $dbo->getAssoc('SELECT * FROM `moihottur__country`');
        $_resorts   = $dbo->getAssoc('SELECT * FROM `moihottur__resort`');
        $_hotels    = $dbo->getAssoc('SELECT * FROM `moihottur__hotel`');

        $countries = array();

        foreach ($_countries as $_country) {
            $countries[$_country['country_title']] = $_country['country_id'];
        }

        unset($_countries);

        $resorts = array();

        foreach ($_resorts as $_resort) {
            $resorts[$_resort['resort_title']] = $_resort['resort_id'];
        }

        unset($_resorts);

        $hotels = array();

        DxFactory::import('DomainObjectModel_Hotel');

        foreach ($_hotels as $_hotel) {
            $hotels[DomainObjectModel_Hotel::generateSignature($_hotel['hotel_title'])] = $_hotel['hotel_id'];
        }

        unset($_hotels);

        echo 'REQUIRED DATA LOADED';

        // Found
        $f = 0;

        // Unknown country
        $y = 0;
        // Unknown resort
        $z = 0;

        $map_countries = array(
            'Соединенные Штаты Америки' => 'США',
            'Корейская Народно-Демократическая Республика' => 'Южная Корея',
            'Республика Корея' => 'Южная Корея',
            'Объединенные Арабские Эмираты' => 'ОАЭ',
            'Папуа-Новая Гвинея' => 'Папуа Новая Гвинея',
            'Соединенное Королевство Великобритании и Северной' => 'Великобритания',
            'Сирийская Арабская Республика' => 'Сирия',
            'Сербия и Черногория' => 'Сербия',
            'Мальдивские о-ва' => 'Мальдивы',
            'Малави' => 'Мали',
            'Лаосская Народно-Демократическая Республика' => 'Лаос',
            'Республика Молдова' => 'Молдавия',
            'Сейшельские Острова' => 'Сейшелы',
            'Объединенная Республика Танзания' => 'Танзания',
            'Сьерра-Леоне' => 'Сьерра Леоне',
        );

        $hotel_map = array(
            'KALIPTUS HOTEL' => 36424,
        );

        $unexisting_c = array();
        $unexisting_r = array();
        $unexisting_h = array();

        echo 'IMPORT STARTED';
        echo '______________';

        foreach ($hotels_old as $hotel_old) {
            $country_id = null;
            $resort_id  = null;

            // Useless
            if (empty($formatted_locations[$hotel_old['city']])) {
                $y++;
            }

            $country_name = null;

            if (!empty($formatted_locations[$hotel_old['city']])) {
                $country_name = $formatted_locations[$hotel_old['city']];
            }

            if (isset($map_countries[$country_name])) {
                $country_name = $map_countries[$country_name];
            }

            if (!isset($countries[$country_name])) {
                $unexisting_c[$country_name] = true;
            } else {
                $country_id = $countries[$country_name];
            }

            // Useless
            if (empty($formatted_locations[$hotel_old['curort']])) {
                $z++;
            }

            $resort_name = null;

            if (!empty($formatted_locations[$hotel_old['curort']])) {
                $resort_name = $formatted_locations[$hotel_old['curort']];
            }

            if (!isset($resorts[$resort_name])) {
                $unexisting_r[$resort_name] = true;
            } else {
                $resort_id = $resorts[$resort_name];
            }

            $_h_sign = DomainObjectModel_Hotel::generateSignature($hotel_old['name']);

            $hotel_our = null;

            if (isset($hotel_map[$hotel_old['name']])) {
                $hotel_our = $hotel_map[$hotel_old['name']];
            } else {
                if (!empty($hotels[$_h_sign])) {
                    $hotel_our = $hotels[$_h_sign];
                }
            }

            if (!empty($hotel_our)) {
                $dbo->query(
                    "UPDATE `moihottur__hotel` SET `hotel_description`= ?,`hotel_extended_data`= ? WHERE `hotel_id` = ?",
                    array(
                        $hotel_old['description'],
                        $hotel_old['setka'],
                        $hotel_our,
                    )
                );

                echo 'IMPORTED HOTEL #'. $hotel_our .' ('. $hotel_old['name'] .')';
            } else {
                $unexisting_h[$hotel_old['name']] = $country_id .'|'. $resort_id;

                continue;
            }

            $f++;
        }

        echo 'IMPORT FINISHED';

        echo 'STATS';
        echo '> Updated: '. $f;

        exit(1);
    }

    protected function mergeNews()
    {
        $xml = <<<XML
XML;

        $xml = (array)simplexml_load_string($xml);
        $xml = json_decode(json_encode($xml), true);
        $xml = $xml['mgt_p2_news'];

        $data = array();

        DxFactory::import('Utils_NameMaker');

        foreach ($xml as $xml_part) {
            $d_part = array();

            $d_part['publication_title']     = trim($xml_part['title']);
            $d_part['publication_category']  = 'NEWS';
            $d_part['publication_brief']     = @trim($xml_part['description']);
            $d_part['publication_content']   = @trim($xml_part['article']);
            $d_part['publication_date']      = date('Y-m-d H:i:s', $xml_part['datestart']);
            $d_part['created']               = date('Y-m-d H:i:s', $xml_part['datecreated']);

            $signature = substr(Utils_NameMaker::cyrillicToLatin($d_part['publication_title'], true), 0, 255);

            if (empty($d_part['publication_brief'])) {
                $d_part['publication_brief'] = $d_part['publication_title'];
            }

            if (empty($d_part['publication_content'])) {
                $d_part['publication_content'] = $d_part['publication_title'];
            }

            if (empty($xml_part['datestart'])) {
                $d_part['publication_date'] = $d_part['created'];
            }

            $d_part['publication_signature'] = $signature;

            $data[$xml_part['articleid']] = $d_part;
        }

        foreach ($data as $news_entry) {
            $this->writeToDatabase('publication', $news_entry);
        }
    }

    protected function mergeFeedback()
    {
        $xml = <<<XML
XML;

        $xml = (array)simplexml_load_string($xml);
        $xml = json_decode(json_encode($xml), true);
        $xml = $xml['mgt_p2002_guestbook'];

        $data = array();

        $map = array(
            'username' => 'feedback_user_name',
            'email'    => 'feedback_user_email',
            'phone'    => 'feedback_user_phone',
            'message'  => 'feedback_message',
            'manager'  => 'staff_name',
        );

        /** @var $q DomainObjectQuery_Staff */
        $q = DxFactory::getInstance('DomainObjectQuery_Staff');

        foreach ($xml as $x) {
            $d = array(
                'feedback_status'  => 'DISABLED',
                'feedback_user_ip' => '0.0.0.0',
            );

            foreach ($map as $x_p => $method) {
                $d[$method] = empty($x[$x_p]) ? null : $x[$x_p];
            }

            if (!empty($x['online'])) {
                $d['feedback_status'] = 'ENABLED';
            }

            if (!empty($d['staff_name'])) {
                $staff = $q->getByName($d['staff_name']);

                if (!empty($staff)) {
                    $d['staff_id']   = $staff['staff_id'];
                    $d['staff_name'] = $staff['staff_name'];

                    unset($staff);
                } else {
                    // Not existing staff
                }
            }

            $data[$x['messageid']] = $d;
        }

        foreach ($data as $d) {
            $this->writeToDatabase('feedback', $d);
        }
    }

    protected function mergeFeedbackHotels()
    {
        $xml = <<<XML
XML;

        $xml = (array)simplexml_load_string($xml);
        $xml = json_decode(json_encode($xml), true);
        $xml = $xml['mgt_p102_otziv'];

        $total_xml = count($xml);

        $x = 0;
        $y = 0;
        $z = 0;

        $map = array(
            'message' => 'feedback_message',
            'name'    => 'feedback_user_name',
            'mail'    => 'feedback_user_email',
        );

        $map_ext = array(
            'ocenserv' => 'rating_service',
            'ocensost' => 'rating_room',
            'ocenplag' => 'rating_beach',
            'ocenterr' => 'rating_territory',
            'ocenpit'  => 'rating_food',
            'ocenanim' => 'rating_anim',

            'datetravel' => 'date_staying',

            'hotel_recom' => '_recommend',
        );

        $map_ext_recommend = array(
            '1' => 'recommend_family',
            '2' => 'recommend_young',
            '3' => 'recommend_family_children',
            '4' => 'recommend_old',
            '5' => 'recommend_dont_ask',
            '6' => 'recommend_no_opinion',
        );

        $data = array();

        foreach ($xml as $xml_p) {
            $their_hotel = $this->findTheirsHotel($xml_p['hotelid']);

            if (empty($their_hotel)) {
                $z++;

                continue;
            }

            $data_part = array(
                'feedback_status' => empty($xml_p['show_on']) && $xml_p['show_on'] != 0 ? 'ENABLED' : 'DISABLED',
                'feedback_type'   => 'HOTEL',
            );

            foreach ($map as $their => $our) {
                $data_part[$our] = empty($xml_p[$their]) ? null : $xml_p[$their];
            }

            foreach ($map_ext as $their => $our) {
                $data_part['feedback_extended_data'][$our] = empty($xml_p[$their]) ? null : $xml_p[$their];
            }

            $data[] = $data_part;
        }

        foreach ($data as $data_part) {
            $this->writeToDatabase('feedback', $data_part);
        }

        $x = $z - $y;

        $report = <<<REPORT
Total entries: {$total_xml}
---------------------------

Not found hotels: {$z}
Found hotels:     {$y}

Diff: {$x}
REPORT;

        echo $report;

        DxApp::terminate();
    }

    public function mergeFaq()
    {
        $xml = <<<XML
XML;

        $xml = (array)simplexml_load_string($xml);
        $xml = json_decode(json_encode($xml), true);
        $xml = $xml['mgt_p4_guestbook'];

        $total_xml = count($xml);

        $map = array(
            'username'    => 'faq_user_name',
            'email'       => 'faq_user_email',
            'phone'       => 'faq_user_phone',
            'message'     => 'faq_message',
            'answer'      => 'faq_answer',
            'online'      => 'faq_status',
            'date_create' => 'created',
        );

        $i = 0;

        foreach ($xml as $faq) {
            $data = array();

            foreach ($faq as $key => $f) {
                if (empty($map[$key])) {
                    continue;
                }

                if ($key == 'online') {
                    $data[$map[$key]] = ($f == 1 ? 'ENABLED' : 'DISABLED');
                } elseif ($key == 'date_create') {
                    $data[$map[$key]] = ($f == '0000-00-00 00:00:00' ? date('Y-m-d H:i:s') : $f);
                } elseif ($key == 'email') {
                    if (!filter_var($f, FILTER_VALIDATE_EMAIL)) {
                        $data[$map[$key]] = null;
                    } else {
                        $data[$map[$key]] = $f;
                    }
                } else {
                    $data[$map[$key]] = empty($f) ? null : $f;
                }
            }

            $data['faq_user_ip'] = '0.0.0.0';

            $this->writeToDatabase('faq', $data);

            $i++;
        }

        $report = <<<REPORT
Total entries: {$total_xml}
---------------------------

Created entries: {$i}
REPORT;

        echo $report;

        DxApp::terminate();
    }

    protected function mergeCompanion()
    {
        $xml = <<<XML
XML;

        $xml = (array)simplexml_load_string($xml);
        $xml = json_decode(json_encode($xml), true);
        $xml = $xml['mgt_p202_guestbook'];

        $total_entries = count($xml);
        $i = 0;

        $prefix = 'companion_';

        foreach ($xml as $x) {
            try {
                $date_from = date('Y-m-d H:i:s', strtotime($x['ot'] . date('.m.Y')));
            } catch (Exception $e) {
                $date_from = date('Y-m-d H:i:s');
            }

            try {
                $date_to = date('Y-m-d H:i:s', strtotime($x['do2'] . date('.m.Y')));
            } catch (Exception $e) {
                $date_to = date('d.m.Y H:i:s', strtotime('+ 5 days'));
            }

            $data = array(
                $prefix .'user_name'   => trim($x['username']),
                $prefix .'user_email'  => empty($x['email']) || !filter_var($x['email'], FILTER_VALIDATE_EMAIL) ? 'broken@email.drop' : mb_strtolower($x['email']),
                $prefix .'user_phone'  => empty($x['phone']) ? '+7 000 000 00 00' : $x['phone'],
                $prefix .'user_age'    => empty($x['age']) || $x['age'] <= 0 ? 25 : (int)$x['age'],
                $prefix .'status'      => $x['online'] == 1 ? 'ENABLED' : 'DISABLED',
                $prefix .'user_ip'     => '0.0.0.0',
                $prefix .'price'       => empty($x['budget']) || $x['budget'] <= 0 ? 15000 : (int)$x['budget'],
                $prefix .'location'    => empty($x['country']) ? '' : $x['country'],
                $prefix .'notes'       => empty($x['message']) ? '' : $x['message'],
                $prefix .'user_city'   => empty($x['city']) ? 'Новосибирск' : $x['city'],
                $prefix .'date_from'   => $date_from,
                $prefix .'date_to'     => $date_to,
                $prefix .'daynum_from' => empty($x['ot'])  || $x['ot']  <= 0 ? 7  : (int)$x['ot'],
                $prefix .'daynum_to'   => empty($x['do2']) || $x['do2'] <= 0 ? 14 : (int)$x['do2'],
                $prefix .'user_photo'  => null,
            );

            if (!empty($x['img'])) {
                $path_source  = realpath('/home/kaa/prj/www/mht_old');
                $path_source .= '/images/poisk_pari/'. $x['img'];

                $path_dest = realpath('/home/kaa/prj/www/mht/static/files/upload/companion') .'/'. $x['img'];

                copy($path_source, $path_dest);

                $data[$prefix .'user_photo'] = '/static/files/upload/companion/'. $x['img'];
            }

            $this->writeToDatabase('companion', $data);

            $i++;
        }

        $report = <<<REPORT
Total entries: {$total_entries}
---------------------------

Created entries: {$i}
REPORT;

        echo $report;

        DxApp::terminate();
    }

    protected function recheckHotels()
    {
        return $this->notFound();

        DxFactory::import('DomainObjectModel_Hotel');

        $dbo = DxApp::getComponent(DxConstant_Project::ALIAS_DOMAIN_OBJECT_DBO);

        $hotels = $dbo->getAll('SELECT * FROM `moihottur__hotel` WHERE `hotel_signature` IS NULL');

        foreach ($hotels as $hotel) {
            $hotel['hotel_signature'] = DomainObjectModel_Hotel::generateSignature($hotel['hotel_title']);

            $dbo->query('UPDATE `moihottur__hotel` SET `hotel_signature` = ? WHERE `hotel_id` = ?', array($hotel['hotel_signature'], $hotel['hotel_id']));
        }

        $hotels = $dbo->getAll('SELECT * FROM `mgt_p102_hotels`');

        $stats_total  = count($hotels);
        $stats_exists = 0;

        foreach ($hotels as $hotel) {
            $signature = DomainObjectModel_Hotel::generateSignature($hotel['name']);

            $is_exists = $dbo->getOne("SELECT `hotel_id` FROM `moihottur__hotel` WHERE `hotel_signature` LIKE ? LIMIT 1", array('%'. $signature .'%'));

            if (!empty($is_exists)) {
                $stats_exists++;
            }

            unset($is_exists);
        }

        die(var_dump($stats_total, $stats_exists, ($stats_total - $stats_exists), ($stats_exists * 100) / $stats_total .'%'));
    }
}