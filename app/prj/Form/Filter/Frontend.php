<?php
DxFactory::import('Form_Filter');

abstract class Form_Filter_Frontend extends Form_Filter
{
    /** @var string */
    protected $scope_id = '__FILTER_FRONTEND__';
}