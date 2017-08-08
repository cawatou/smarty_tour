<?php
DxFactory::import('DxController_Backend');

class DxController_Backend_Migrator extends DxController_Backend
{
    /** @var array */
    protected $cmd_method = array(
        '.adm.migrate_roles' => 'migrateRoles',
    );

    public function migrateRoles()
    {
        /** @var DomainObjectQuery_Faq $q_fa */
        $q_fa = DxFactory::getSingleton('DomainObjectQuery_Faq');
        /** @var DomainObjectQuery_Request $q_re */
        $q_re = DxFactory::getSingleton('DomainObjectQuery_Request');

        $faqs = $q_fa->findAll(false);

        foreach ($faqs as $faq) {
            if ($faq->getCityId()) {
                continue;
            }

            if (!$faq->getOfficeId()) {
                continue;
            }

            if ($faq->getOffice() === null) {
                continue;
            }

            $faq->setCityId($faq->getOffice()->getCityId());
        }

        unset($faqs);

        $requests = $q_re->findAll(false);

        foreach ($requests as $request) {
            if ($request->getOfficeId()) {
                continue;
            }

            if (!$request->getExtendedData('office')) {
                continue;
            }

            if (!$request->getOfficeViaExtended()) {
                continue;
            }

            $request->setOfficeId($request->getExtendedData('office'));
        }

        $this->getDomainObjectManager()->flush();
    }
}