<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('DomainObjectModel_HotelImage', 'main');

/**
 * DomainObjectModel_BaseHotelImage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $hotel_image_id
 * @property integer $hotel_id
 * @property string $hotel_image_path
 * @property enum $hotel_image_type
 * @property timestamp $created
 * @property timestamp $updated
 * @property DomainObjectModel_Hotel $Hotel
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class DomainObjectModel_BaseHotelImage extends DomainObjectModel
{
    public function setTableDefinition()
    {
        $this->setTableName('moihottur__hotel_image');
        $this->hasColumn('hotel_image_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('hotel_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '4',
             ));
        $this->hasColumn('hotel_image_path', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('hotel_image_type', 'enum', 8, array(
             'type' => 'enum',
             'fixed' => 0,
             'unsigned' => false,
             'values' => 
             array(
              0 => 'USER',
              1 => 'OPERATOR',
              2 => 'AGENCY',
             ),
             'primary' => false,
             'default' => 'OPERATOR',
             'notnull' => false,
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
        $this->hasOne('DomainObjectModel_Hotel as Hotel', array(
             'local' => 'hotel_id',
             'foreign' => 'hotel_id'));
    }
}