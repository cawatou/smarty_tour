<?php
DxFactory::import('DxController_Frontend');

class DxController_Frontend_Contacts extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.contacts' => 'index',
    );

    /**
     * @return string
     */
    protected function index()
    {
        /** @var $q_city DomainObjectQuery_City */
        $q_city = DxFactory::getSingleton('DomainObjectQuery_City');

        $selected_city = $this->getContext()->getCurrentCommand()->getArguments('city', null);

        if ($selected_city === null) {
            $selected_city = $this->getContext()->getCity();
        } else {
            $selected_city = $q_city->findByAlias($selected_city);

            if (empty($selected_city) || $selected_city->getStatus() == 'DISABLED') {
                return $this->notFound();
            }
        }

        /** @var $q_office DomainObjectQuery_Office */
        $q_office = DxFactory::getSingleton('DomainObjectQuery_Office');

        $city_list = array();

        $city_list_unsorted = $q_city->findAll(true);

        $i = $k = 0;

        $delimiter = ceil(count($city_list_unsorted) / 3);

        foreach ($city_list_unsorted as $city) {
            $i++;

            $city_list[$k][] = $city;

            if ($i == $delimiter) {
                $k++;

                $i = 0;

                continue;
            }
        }

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();

        $smarty->assign(
            array(
                'city_list'   => $city_list,
                'active_city' => $selected_city,
                'office_list' => $this->getCityOffices($selected_city->getId()),
            )
        );

        $html = $smarty->fetch('frontend/contacts.tpl.php');

        return $this->wrap($html);
    }
}