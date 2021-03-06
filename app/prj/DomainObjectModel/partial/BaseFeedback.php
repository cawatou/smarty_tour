<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('DomainObjectModel_Feedback', 'main');

/**
 * DomainObjectModel_BaseFeedback
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $feedback_id
 * @property integer $user_id
 * @property integer $hotel_id
 * @property string $hotel_title
 * @property integer $office_id
 * @property integer $staff_id
 * @property string $staff_name
 * @property enum $feedback_status
 * @property enum $feedback_type
 * @property string $feedback_user_name
 * @property string $feedback_user_phone
 * @property string $feedback_user_email
 * @property string $feedback_user_ip
 * @property string $feedback_extended_data
 * @property string $feedback_message
 * @property string $feedback_answer
 * @property timestamp $created
 * @property timestamp $updated
 * @property DomainObjectModel_User $User
 * @property DomainObjectModel_Staff $Staff
 * @property DomainObjectModel_Hotel $Hotel
 * @property DomainObjectModel_Office $Office
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class DomainObjectModel_BaseFeedback extends DomainObjectModel
{
    public function setTableDefinition()
    {
        $this->setTableName('moihottur__feedback');
        $this->hasColumn('feedback_id', 'integer', 4, array(
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
        $this->hasColumn('hotel_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '4',
             ));
        $this->hasColumn('hotel_title', 'string', 256, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '256',
             ));
        $this->hasColumn('office_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '4',
             ));
        $this->hasColumn('staff_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '4',
             ));
        $this->hasColumn('staff_name', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('feedback_status', 'enum', 8, array(
             'type' => 'enum',
             'fixed' => 0,
             'unsigned' => false,
             'values' => 
             array(
              0 => 'ENABLED',
              1 => 'DISABLED',
             ),
             'primary' => false,
             'default' => 'DISABLED',
             'notnull' => true,
             'autoincrement' => false,
             'length' => '8',
             ));
        $this->hasColumn('feedback_type', 'enum', 7, array(
             'type' => 'enum',
             'fixed' => 0,
             'unsigned' => false,
             'values' => 
             array(
              0 => 'QUALITY',
              1 => 'PROPOSE',
              2 => 'HOTEL',
             ),
             'primary' => false,
             'default' => 'QUALITY',
             'notnull' => true,
             'autoincrement' => false,
             'length' => '7',
             ));
        $this->hasColumn('feedback_user_name', 'string', 100, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '100',
             ));
        $this->hasColumn('feedback_user_phone', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('feedback_user_email', 'string', 100, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '100',
             ));
        $this->hasColumn('feedback_user_ip', 'string', 15, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '15',
             ));
        $this->hasColumn('feedback_extended_data', 'string', null, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('feedback_message', 'string', null, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('feedback_answer', 'string', null, array(
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
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('DomainObjectModel_User as User', array(
             'local' => 'user_id',
             'foreign' => 'user_id'));

        $this->hasOne('DomainObjectModel_Staff as Staff', array(
             'local' => 'staff_id',
             'foreign' => 'staff_id'));

        $this->hasOne('DomainObjectModel_Hotel as Hotel', array(
             'local' => 'hotel_id',
             'foreign' => 'hotel_id'));

        $this->hasOne('DomainObjectModel_Office as Office', array(
             'local' => 'office_id',
             'foreign' => 'office_id'));
    }
}