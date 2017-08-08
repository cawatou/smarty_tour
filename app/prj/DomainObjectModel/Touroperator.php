<?php
/**
 * @method int getId()
 * @method string getTitle()
 * @method string getStatus()
 * @method DxDateTime getCreated()
 * @method DxDateTime getUpdated()
 *
 * @method setId(int $arg)
 * @method setTitle(string $arg)
 * @method setStatus(string $arg)
 * @method setCreated(DxDateTime $arg)
 * @method setUpdated(DxDateTime $arg)
 */
class DomainObjectModel_Touroperator extends DomainObjectModel_BaseTouroperator
{
    /** @var string */
    protected $field_prefix = 'touroperator';

    /**
     * @param null|string $field
     * @throws DxException
     */
    protected function validateField($field = null)
    {
        if ($field === null || $field == 'touroperator_title') {
            if (empty($this->touroperator_title)) {
                throw new DxException("Invalid 'touroperator_title'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }

        if ($field === null || $field == 'touroperator_status') {
            if (!is_string($this->touroperator_status) || !in_array($this->touroperator_status, array('ENABLED', 'DISABLED'))) {
                throw new DxException("Invalid 'touroperator_status'", self::DOMAIN_OBJECT_MODEL_ERROR_FIELD);
            }
        }
    }

    /**
     * @return bool
     */
    public function isUnique()
    {
        /** @var DomainObjectQuery_Touroperator $q */
        $q = DxFactory::getSingleton('DomainObjectQuery_Touroperator');

        $p = $q->findByTitle($this->getTitle());

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