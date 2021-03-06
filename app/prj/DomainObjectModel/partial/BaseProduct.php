<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('DomainObjectModel_Product', 'main');

/**
 * DomainObjectModel_BaseProduct
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $product_id
 * @property integer $user_id
 * @property integer $product_linked_id
 * @property string $product_title
 * @property string $product_alias
 * @property decimal $product_price
 * @property decimal $product_discount_price
 * @property decimal $product_linked_price
 * @property decimal $product_linked_discount_price
 * @property integer $country_id
 * @property string $country_name
 * @property integer $resort_id
 * @property string $resort_name
 * @property integer $touroperator_id
 * @property string $product_operator
 * @property string $product_brief
 * @property string $product_content
 * @property string $product_notes
 * @property string $product_get_via
 * @property string $product_attributes
 * @property string $product_payable_includes
 * @property string $product_payable_excludes
 * @property integer $product_is_highlight
 * @property integer $product_is_discount_applied
 * @property string $product_cover
 * @property integer $product_from_id
 * @property enum $product_status
 * @property integer $product_qnt
 * @property timestamp $created
 * @property timestamp $updated
 * @property DomainObjectModel_Country $Country
 * @property DomainObjectModel_Resort $Resort
 * @property DomainObjectModel_Touroperator $Touroperator
 * @property DomainObjectModel_User $User
 * @property Doctrine_Collection $Product
 * @property Doctrine_Collection $Order
 * @property Doctrine_Collection $ProductDeparture
 * @property Doctrine_Collection $ProductImage
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class DomainObjectModel_BaseProduct extends DomainObjectModel
{
    public function setTableDefinition()
    {
        $this->setTableName('moihottur__product');
        $this->hasColumn('product_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '4',
             ));
        $this->hasColumn('product_linked_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '4',
             ));
        $this->hasColumn('product_title', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('product_alias', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('product_price', 'decimal', 9, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '9',
             'scale' => ' 2',
             ));
        $this->hasColumn('product_discount_price', 'decimal', 9, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '9',
             'scale' => ' 2',
             ));
        $this->hasColumn('product_linked_price', 'decimal', 9, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '9',
             'scale' => ' 2',
             ));
        $this->hasColumn('product_linked_discount_price', 'decimal', 9, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '9',
             'scale' => ' 2',
             ));
        $this->hasColumn('country_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '4',
             ));
        $this->hasColumn('country_name', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('resort_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '4',
             ));
        $this->hasColumn('resort_name', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('touroperator_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '4',
             ));
        $this->hasColumn('product_operator', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('product_brief', 'string', null, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('product_content', 'string', null, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('product_notes', 'string', null, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('product_get_via', 'string', null, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('product_attributes', 'string', null, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('product_payable_includes', 'string', null, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('product_payable_excludes', 'string', null, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('product_is_highlight', 'integer', 1, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '0',
             'notnull' => true,
             'autoincrement' => false,
             'length' => '1',
             ));
        $this->hasColumn('product_is_discount_applied', 'integer', 1, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '1',
             'notnull' => true,
             'autoincrement' => false,
             'length' => '1',
             ));
        $this->hasColumn('product_cover', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('product_from_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '4',
             ));
        $this->hasColumn('product_status', 'enum', 8, array(
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
        $this->hasColumn('product_qnt', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'default' => '0',
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
        $this->hasOne('DomainObjectModel_Country as Country', array(
             'local' => 'country_id',
             'foreign' => 'country_id'));

        $this->hasOne('DomainObjectModel_Resort as Resort', array(
             'local' => 'resort_id',
             'foreign' => 'resort_id'));

        $this->hasOne('DomainObjectModel_Touroperator as Touroperator', array(
             'local' => 'touroperator_id',
             'foreign' => 'touroperator_id'));

        $this->hasOne('DomainObjectModel_User as User', array(
             'local' => 'user_id',
             'foreign' => 'user_id'));

        $this->hasMany('DomainObjectModel_Product as Product', array(
             'local' => 'product_id',
             'foreign' => 'product_linked_id'));

        $this->hasMany('DomainObjectModel_Order as Order', array(
             'local' => 'product_id',
             'foreign' => 'product_id'));

        $this->hasMany('DomainObjectModel_ProductDeparture as ProductDeparture', array(
             'local' => 'product_id',
             'foreign' => 'product_id'));

        $this->hasMany('DomainObjectModel_ProductImage as ProductImage', array(
             'local' => 'product_id',
             'foreign' => 'product_id'));
    }
}