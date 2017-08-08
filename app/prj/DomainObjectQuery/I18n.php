<?php

DxFactory::import('DataListQuery');
DxFactory::import('Form_Filter');

class DomainObjectQuery_I18n extends DomainObjectQuery implements DataListQuery
{
    /**
     * @param array $params
     */
    public function initByListParams(array &$params = array())
    {
        $search_params = empty($params[Form_Filter::FILTER_SEARCH_PARAMS]) ? array() : $params[Form_Filter::FILTER_SEARCH_PARAMS];
        $order_params  = empty($params[Form_Filter::FILTER_ORDER_PARAMS]) ? array() : $params[Form_Filter::FILTER_ORDER_PARAMS];
        $placeholders  = array();

        $qb = $this->getQueryBuilder()
            ->select('i18n')
            ->from('DomainObjectModel_I18n', 'i18n')
            ->orderBy('i18n.created', 'DESC');

        if (array_key_exists('only_not_translated', $search_params) && $search_params['only_not_translated']) {
            $qb->andWhere('(i18n.i18n_target_string IS NULL OR i18n.i18n_target_string = ?)');
            $placeholders[] = '';
        }

        if (array_key_exists('i18n_source_string', $search_params) && $search_params['i18n_source_string']) {
            $qb->andWhere('i18n.i18n_source_string LIKE ?');
            $placeholders[] = "%{$search_params['i18n_source_string']}%";
        }

        if (array_key_exists('i18n_target_string', $search_params) && $search_params['i18n_target_string']) {
            $qb->andWhere('i18n.i18n_target_string LIKE ?');
            $placeholders[] = "%{$search_params['i18n_target_string']}%";
        }

        if (array_key_exists('i18n_source_locale', $search_params) && $search_params['i18n_source_locale']) {
            $qb->andWhere('i18n.i18n_source_locale = ?');
            $placeholders[] = $search_params['i18n_source_locale'];
        }

        if (array_key_exists('i18n_target_locale', $search_params) && $search_params['i18n_target_locale']) {
            $qb->andWhere('i18n.i18n_target_locale = ?');
            $placeholders[] = $search_params['i18n_target_locale'];
        }

        $this->setCachedQueryBuilder($qb->setParameters($placeholders));
    }

    /**
     * @param int    $offset
     * @param int    $length
     * @return array
     */
    public function &findForList($offset, $length)
    {
        $qb = $this->getCachedQueryBuilder(true)
            ->offset($offset)
            ->limit($length);

        return $this->getMultiFound($qb);
    }

    /**
     * @return int
     */
    public function findCountForList()
    {
        return $this->getCount($this->getCachedQueryBuilder(true));
    }

    /**
     * @return string|int
     */
    public function getChecksumForList()
    {
        $qb = $this->getCachedQueryBuilder();

        if (is_null($qb)) {
            return 0;
        }

        return md5($qb->getSQL() . serialize($qb->getParameters()));
    }

    /**
     * @param int $id
     * @return DomainObjectController_I18n|null
     */
    public function findById($id)
    {
        $qb = $this->getQueryBuilder()
            ->select('i18n')
            ->from('DomainObjectModel_I18n', 'i18n')
            ->where('i18n.i18n_id = ?');

        return $this->getSingleFound($qb, array($id));
    }

    /**
     * @param string      $tag
     * @param null|string $source_locale
     * @param null|string $target_locale
     * @return DomainObjectController|null
     */
    public function findByTag($tag, $source_locale = null, $target_locale = null)
    {
        $qb = $this->getQueryBuilder()
            ->select('i18n')
            ->from('DomainObjectModel_I18n', 'i18n')
            ->where('i18n.i18n_source_tag = ?');

        $placeholder = array($tag);

        if (!is_null($source_locale)) {
            $qb->andWhere('i18n.i18n_source_locale = ?');
            $placeholder[] = $source_locale;
        }

        if (!is_null($target_locale)) {
            $qb->andWhere('i18n.i18n_target_locale = ?');
            $placeholder[] = $target_locale;
        }

        return $this->getSingleFound($qb, $placeholder);
    }

    /**
     * @param string      $string
     * @param null|string $source_locale
     * @param null|string $target_locale
     * @return DomainObjectController|null
     */
    public function findByString($string, $source_locale = null, $target_locale = null)
    {
        $qb = $this->getQueryBuilder()
            ->select('i18n')
            ->from('DomainObjectModel_I18n', 'i18n')
            ->where('i18n.i18n_source_string = ?')
            ->andWhere('i18n.i18n_source_tag IS NULL');

        $placeholder = array($string);

        if (!is_null($source_locale)) {
            $qb->andWhere('i18n.i18n_source_locale = ?');
            $placeholder[] = $source_locale;
        }

        if (!is_null($target_locale)) {
            $qb->andWhere('i18n.i18n_target_locale = ?');
            $placeholder[] = $target_locale;
        }

        return $this->getSingleFound($qb, $placeholder);
    }

    /**
     * @param string $source
     * @param string $target
     * @return array
     */
    public function getHaystack($source, $target)
    {
        $qb = $this->getQueryBuilder()
            ->select('i18n')
            ->from('DomainObjectModel_I18n', 'i18n')
            ->where('i18n.i18n_source_locale = ?')
            ->andWhere('i18n.i18n_target_locale = ?');

        return $this->getArrayResult($qb, array($source, $target));
    }

    /**
     * @return array
     */
    public function getSourceLocales()
    {
        $qb = $this->getQueryBuilder()
            ->select('DISTINCT i18n.i18n_source_locale as source_locale')
            ->from('DomainObjectModel_I18n', 'i18n')
            ->orderBy('i18n.i18n_source_locale');

        return $this->getArrayResult($qb);
    }

    /**
     * @return array
     */
    public function getTargetLocales()
    {
        $qb = $this->getQueryBuilder()
            ->select('DISTINCT i18n.i18n_target_locale as target_locale')
            ->from('DomainObjectModel_I18n', 'i18n')
            ->orderBy('i18n.i18n_target_locale');

        return $this->getArrayResult($qb);
    }
}
