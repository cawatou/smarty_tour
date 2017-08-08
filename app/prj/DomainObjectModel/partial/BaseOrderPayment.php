<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('DomainObjectModel_OrderPayment', 'main');

/**
 * DomainObjectModel_BaseOrderPayment
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $order_payment_id
 * @property integer $order_id
 * @property string $order_payment_transaction_id
 * @property decimal $order_payment_amount
 * @property enum $order_payment_status
 * @property string $order_payment_response
 * @property timestamp $created
 * @property timestamp $updated
 * @property timestamp $order_payment_completed
 * @property DomainObjectModel_Order $Order
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class DomainObjectModel_BaseOrderPayment extends DomainObjectModel
{
    public function setTableDefinition()
    {
        $this->setTableName('moihottur__order_payment');
        $this->hasColumn('order_payment_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('order_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '4',
             ));
        $this->hasColumn('order_payment_transaction_id', 'string', 32, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '32',
             ));
        $this->hasColumn('order_payment_amount', 'decimal', 9, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '9',
             'scale' => ' 2',
             ));
        $this->hasColumn('order_payment_status', 'enum', 9, array(
             'type' => 'enum',
             'fixed' => 0,
             'unsigned' => false,
             'values' => 
             array(
              0 => 'NEW',
              1 => 'PREAUTH',
              2 => 'RESERVED',
              3 => 'COMPLETED',
              4 => 'CANCELLED',
             ),
             'primary' => false,
             'default' => 'NEW',
             'notnull' => true,
             'autoincrement' => false,
             'length' => '9',
             ));
        $this->hasColumn('order_payment_response', 'string', null, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
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
        $this->hasColumn('order_payment_completed', 'timestamp', 25, array(
             'type' => 'timestamp',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '25',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('DomainObjectModel_Order as Order', array(
             'local' => 'order_id',
             'foreign' => 'order_id'));
    }
}