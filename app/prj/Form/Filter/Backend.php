<?php

DxFactory::import('Form_Filter');

abstract class Form_Filter_Backend extends Form_Filter
{
    /** @var string */
    protected $scope_id = '__FILTER_BACKEND__';
}