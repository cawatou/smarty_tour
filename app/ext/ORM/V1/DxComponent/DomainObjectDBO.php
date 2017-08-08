<?php

DxFactory::import('DxComponent');
DxFactory::import('DxConstant_Project');

class DxComponent_DomainObjectDBO extends DxComponent
{
    /**
     * @static
     * @param array $params
     * @return DxDBO_PDO
     * @throws DxException
     */
    public static function getComponent(array $params = array())
    {
        try {
            /** @var $dom DomainObjectManager */
            $dom = DxApp::getComponent(DxConstant_Project::ALIAS_DOMAIN_OBJECT_MANAGER);

            $params = array(
                'pdo' => $dom->getWrappedConnection()
            );

            $params = array_merge($params, (array)DxApp::config('doctrine'));

            return DxFactory::getSingleton('DxDBO_PDO', array($params));
        } catch (Exception $e) {
            throw new DxException('Error occured while init DomainObjectDBO component', self::DX_COMPONENT_ERROR_INIT_COMPONENT, $e);
        }
    }
}
?>