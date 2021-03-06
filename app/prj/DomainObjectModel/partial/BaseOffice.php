<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('DomainObjectModel_Office', 'main');

/**
 * DomainObjectModel_BaseOffice
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @property integer $office_id
 * @property integer $city_id
 * @property string $city_name
 * @property integer $related_city_id
 * @property string $related_city_name
 * @property integer $subdivision_id
 * @property string $subdivision_name
 * @property string $office_title
 * @property string $office_display_name
 * @property string $office_alias
 * @property string $office_address
 * @property string $office_email
 * @property string $office_phone
 * @property string $office_metro
 * @property string $office_schedule
 * @property string $office_sletat_data
 * @property integer $office_is_pay_cash
 * @property integer $office_is_pay_cashless
 * @property integer $office_is_pay_installment
 * @property integer $office_is_pay_credit
 * @property enum $office_status
 * @property integer $office_qnt
 * @property timestamp $created
 * @property timestamp $updated
 * @property DomainObjectModel_City $City
 * @property DomainObjectModel_City $City_2
 * @property DomainObjectModel_Subdivision $Subdivision
 * @property Doctrine_Collection $Faq
 * @property Doctrine_Collection $Feedback
 * @property Doctrine_Collection $Order
 * @property Doctrine_Collection $Request
 * @property Doctrine_Collection $Staff
 * @property Doctrine_Collection $User
 *
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class DomainObjectModel_BaseOffice extends DomainObjectModel
{
    public function setTableDefinition()
    {
        $this->setTableName('moihottur__office');
        $this->hasColumn('office_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('city_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '4',
             ));
        $this->hasColumn('city_name', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('related_city_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '4',
             ));
        $this->hasColumn('related_city_name', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('subdivision_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '4',
             ));
        $this->hasColumn('subdivision_name', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('office_title', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('office_display_name', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('office_alias', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('office_address', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('office_email', 'string', 100, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '100',
             ));
        $this->hasColumn('office_phone', 'string', 100, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '100',
             ));
        $this->hasColumn('office_metro', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('office_schedule', 'string', null, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('office_sletat_data', 'string', null, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('office_is_pay_cash', 'integer', 1, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'default' => '1',
             'notnull' => true,
             'autoincrement' => false,
             'length' => '1',
             ));
        $this->hasColumn('office_is_pay_cashless', 'integer', 1, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'default' => '1',
             'notnull' => true,
             'autoincrement' => false,
             'length' => '1',
             ));
        $this->hasColumn('office_is_pay_installment', 'integer', 1, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'default' => '1',
             'notnull' => true,
             'autoincrement' => false,
             'length' => '1',
             ));
        $this->hasColumn('office_is_pay_credit', 'integer', 1, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'default' => '1',
             'notnull' => true,
             'autoincrement' => false,
             'length' => '1',
             ));
        $this->hasColumn('office_status', 'enum', 8, array(
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
        $this->hasColumn('office_qnt', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '4',
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
        $this->hasOne('DomainObjectModel_City as City', array(
             'local' => 'city_id',
             'foreign' => 'city_id'));

        $this->hasOne('DomainObjectModel_City as City_2', array(
             'local' => 'related_city_id',
             'foreign' => 'city_id'));

        $this->hasOne('DomainObjectModel_Subdivision as Subdivision', array(
             'local' => 'subdivision_id',
             'foreign' => 'subdivision_id'));

        $this->hasMany('DomainObjectModel_Faq as Faq', array(
             'local' => 'office_id',
             'foreign' => 'office_id'));

        $this->hasMany('DomainObjectModel_Feedback as Feedback', array(
             'local' => 'office_id',
             'foreign' => 'office_id'));

        $this->hasMany('DomainObjectModel_Order as Order', array(
             'local' => 'office_id',
             'foreign' => 'office_id'));

        $this->hasMany('DomainObjectModel_Request as Request', array(
             'local' => 'office_id',
             'foreign' => 'office_id'));

        $this->hasMany('DomainObjectModel_Staff as Staff', array(
             'local' => 'office_id',
             'foreign' => 'office_id'));

        $this->hasMany('DomainObjectModel_User as User', array(
             'local' => 'office_id',
             'foreign' => 'office_id'));
    }
}