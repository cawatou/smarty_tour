<?php
/**
 * @method int getId()
 * @method string getTitle()
 * @method string getAlias()
 * @method string getStatus()
 * @method DxDateTime getCreated()
 * @method DxDateTime getUpdated()
 *
 * @method setId(int $arg)
 * @method setTitle(string $arg)
 * @method setAlias(string $arg)
 * @method setStatus(string $arg)
 * @method setCreated(DxDateTime $arg)
 * @method setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_Subdivision extends DomainObjectModel_BaseSubdivision
{
    /** @var string */
    protected $field_prefix = 'subdivision';

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field === 'subdivision_title') {
            if (empty($this->subdivision_title)) {
                throw new DxException("Invalid 'subdivision_title'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        /*
        if ($field === null || $field === 'subdivision_alias') {
            if (empty($this->subdivision_alias)) {
                throw new DxException("Invalid 'subdivision_alias'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }

            if (preg_match('~[^a-z0-9_-]+~u', $this->subdivision_alias)) {
                throw new DxException("Invalid 'subdivision_alias'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD_FORMAT);
            }
        }
         */

        if ($field === null || $field === 'subdivision_status') {
            if (!is_string($this->subdivision_status) || !in_array($this->subdivision_status, array('ENABLED', 'DISABLED'))) {
                throw new DxException("Invalid 'subdivision_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @param string $field
     * @return bool
     */
    public function isUnique($field = 'title')
    {
        /** @var $q DomainObjectQuery_Subdivision */
        $q = DxFactory::getSingleton('DomainObjectQuery_Subdivision');
        $p = $q->findByTitleOrAlias($field === 'title' ? $this->getTitle() : $this->getAlias());

        if (!$p) {
            return true;
        }


        if (!$this->getId()) {
            return false;
        }

        if ($this->getId() == $p->getId()) {
            return true;
        }

        return false;
    }
}