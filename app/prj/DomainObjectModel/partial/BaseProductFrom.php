<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('DomainObjectModel_ProductFrom', 'main');

/**
 * DomainObjectModel_BaseProductFrom
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $product_from_id
 * @property timestamp $product_from_date
 * @property timestamp $created
 * @property timestamp $updated
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class DomainObjectModel_BaseProductFrom extends DomainObjectModel
{
    public function setTableDefinition()
    {
        $this->setTableName('moihottur__product_from');
        $this->hasColumn('product_from_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => false,
             'length' => '4',
             ));
        $this->hasColumn('product_from_date', 'timestamp', 25, array(
             'type' => 'timestamp',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '25',
             ));
        $this->hasColumn('created', 'timestamp', 25, array(
             'type' => 'timestamp',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '25',
             ));
        $this->hasColumn('updated', 'timestamp', 25, array(
             'type' => 'timestamp',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '25',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}