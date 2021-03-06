<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('DomainObjectModel_Gallery', 'main');

/**
 * DomainObjectModel_BaseGallery
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $gallery_id
 * @property string $gallery_title
 * @property string $gallery_alias
 * @property string $gallery_description
 * @property enum $gallery_category
 * @property string $gallery_cover
 * @property integer $gallery_is_highlight
 * @property enum $gallery_status
 * @property timestamp $gallery_date
 * @property timestamp $created
 * @property timestamp $updated
 * @property Doctrine_Collection $Country
 * @property Doctrine_Collection $GalleryImage
 * @property Doctrine_Collection $Hotel
 * @property Doctrine_Collection $Hotel_4
 * @property Doctrine_Collection $Hotel_5
 * @property Doctrine_Collection $Resort
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class DomainObjectModel_BaseGallery extends DomainObjectModel
{
    public function setTableDefinition()
    {
        $this->setTableName('moihottur__gallery');
        $this->hasColumn('gallery_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('gallery_title', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('gallery_alias', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('gallery_description', 'string', null, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('gallery_category', 'enum', 14, array(
             'type' => 'enum',
             'fixed' => 0,
             'unsigned' => false,
             'values' => 
             array(
              0 => 'COUNTRY',
              1 => 'RESORT',
              2 => 'HOTEL_AGENCY',
              3 => 'HOTEL_OPERATOR',
              4 => 'HOTEL_TOURISTS',
              5 => 'CYCLING',
              6 => 'OTHER',
             ),
             'primary' => false,
             'default' => 'OTHER',
             'notnull' => true,
             'autoincrement' => false,
             'length' => '14',
             ));
        $this->hasColumn('gallery_cover', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('gallery_is_highlight', 'integer', 1, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '0',
             'notnull' => true,
             'autoincrement' => false,
             'length' => '1',
             ));
        $this->hasColumn('gallery_status', 'enum', 8, array(
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
        $this->hasColumn('gallery_date', 'timestamp', 25, array(
             'type' => 'timestamp',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
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
        $this->hasMany('DomainObjectModel_Country as Country', array(
             'local' => 'gallery_id',
             'foreign' => 'gallery_id'));

        $this->hasMany('DomainObjectModel_GalleryImage as GalleryImage', array(
             'local' => 'gallery_id',
             'foreign' => 'gallery_id'));

        $this->hasMany('DomainObjectModel_Hotel as Hotel', array(
             'local' => 'gallery_id',
             'foreign' => 'gallery_agency_id'));

        $this->hasMany('DomainObjectModel_Hotel as Hotel_4', array(
             'local' => 'gallery_id',
             'foreign' => 'gallery_operator_id'));

        $this->hasMany('DomainObjectModel_Hotel as Hotel_5', array(
             'local' => 'gallery_id',
             'foreign' => 'gallery_tourists_id'));

        $this->hasMany('DomainObjectModel_Resort as Resort', array(
             'local' => 'gallery_id',
             'foreign' => 'gallery_id'));
    }
}