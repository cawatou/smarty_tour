<?php

DxFactory::import('DxConstant');

class DxConstant_Project extends DxConstant
{
    const ALIAS_DOMAIN_OBJECT_MANAGER = 'DOMAIN_OBJECT_MANAGER';
    const ALIAS_DOMAIN_OBJECT_DBO     = 'DOMAIN_OBJECT_DBO';
    const ALIAS_SESSION               = 'SESSION';
    const ALIAS_SMARTY                = 'SMARTY';
    const ALIAS_I18N                  = 'I18N';
	const ALIAS_CART                  = 'CART';

    const ROLE_ANONYMOUS = 'ANONYMOUS';
    const ROLE_ADMIN     = 'ADMIN';
    const ROLE_USER      = 'USER';

    const FILTER_SEARCH        = '_search';
    const FILTER_ORDER         = '_order';
    const FILTER_CLEAR         = '_clear';
    const FILTER_SEARCH_PARAMS = 'search_params';
    const FILTER_ORDER_PARAMS  = 'order_params';

    const LAST_CONST_NUMBER = 1251;
}