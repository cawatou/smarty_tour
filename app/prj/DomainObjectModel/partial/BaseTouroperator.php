<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('DomainObjectModel_Touroperator', 'main');

/**
 * DomainObjectModel_BaseTouroperator
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $touroperator_id
 * @property string $touroperator_title
 * @property enum $touroperator_status
 * @property timestamp $created
 * @property timestamp $updated
 * @property Doctrine_Collection $Discount
 * @property Doctrine_Collection $Product
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class DomainObjectModel_BaseTouroperator extends DomainObjectModel
{
    public function setTableDefinition()
    {
        $this->setTableName('moihottur__touroperator');
        $this->hasColumn('touroperator_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('touroperator_title', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('touroperator_status', 'enum', 8, array(
             'type' => 'enum',
             'fixed' => 0,
             'unsigned' => false,
             'values' => 
             array(
              0 => 'ENABLED',
              1 => 'DISABLED',
             ),
             'primary' => false,
             'default' => 'ENABLED',
             'notnull' => true,
             'autoincrement' => false,
             'length' => '8',
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
        $this->hasMany('DomainObjectModel_Discount as Discount', array(
             'local' => 'touroperator_id',
             'foreign' => 'touroperator_id'));

        $this->hasMany('DomainObjectModel_Product as Product', array(
             'local' => 'touroperator_id',
             'foreign' => 'touroperator_id'));
    }
}