<?php

DxFactory::import('DxComponent');

class DxComponent_Smarty extends DxComponent
{
    /**
     * @static
     * @param array $params
     * @return Smarty
     */
    public static function getComponent(array $params = array())
    {
        try {
            $params = array(
                'trim_white_spaces' => false,
                'use_sub_dirs'      => false,
                'compile_check'     => true,
                'template_dir'      => DX_VAR_DIR . DS . 'templates',
                'compile_dir'       => DX_VAR_DIR . DS . 'templates' . DS . '__templates',
                'cache_dir'         => null,
            );

            $config = DxApp::config('smarty');

            foreach ($params as $k => $v) {
                if (isset($config[$k])) {
                    $params[$k] = $config[$k];
                }
            }

            require_once('Smarty/Smarty.class.php');

            $smarty                = new Smarty();
            $smarty->php_handling  = Smarty::PHP_REMOVE;
            $smarty->use_sub_dirs  = $params['use_sub_dirs'];
            $smarty->compile_check = $params['compile_check'];

            $smarty->setTemplateDir($params['template_dir'])->setCompileDir($params['compile_dir']);

            if ($params['cache_dir'] !== null) {
                $smarty->setCacheDir($params['cache_dir']);
            }

            if ($params['trim_white_spaces']) {
                $smarty->loadFilter('output', 'trimwhitespace');
            }

            if (DxApp::existComponent(DxApp::ALIAS_URL)) {
                $smarty->assign('__url', DxApp::getComponent(DxApp::ALIAS_URL));
            }

            if (DxApp::existComponent(DxApp::ALIAS_APP_CONTEXT)) {
                $smarty->assign('__ctx', DxApp::getComponent(DxApp::ALIAS_APP_CONTEXT));
            }

            if (DxApp::existComponent(DxConstant_Project::ALIAS_I18N)) {
                $i18n = DxApp::getComponent(DxConstant_Project::ALIAS_I18N);
                $smarty->registerPlugin(Smarty::PLUGIN_MODIFIER, 't', array($i18n, 'translate'));
            }

            return $smarty;
        } catch (Exception $e) {
            throw new DxException('Error occured while init Smarty component', self::DX_COMPONENT_ERROR_INIT_COMPONENT, $e);
        }
    }
}