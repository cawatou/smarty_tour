<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('DomainObjectModel_PublicationImage', 'main');

/**
 * DomainObjectModel_BasePublicationImage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $publication_image_id
 * @property integer $publication_id
 * @property string $publication_image_path
 * @property integer $publication_image_is_cover
 * @property integer $publication_image_qnt
 * @property timestamp $created
 * @property timestamp $updated
 * @property DomainObjectModel_Publication $Publication
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class DomainObjectModel_BasePublicationImage extends DomainObjectModel
{
    public function setTableDefinition()
    {
        $this->setTableName('moihottur__publication_image');
        $this->hasColumn('publication_image_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('publication_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '4',
             ));
        $this->hasColumn('publication_image_path', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => '255',
             ));
        $this->hasColumn('publication_image_is_cover', 'integer', 1, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'default' => '0',
             'notnull' => true,
             'autoincrement' => false,
             'length' => '1',
             ));
        $this->hasColumn('publication_image_qnt', 'integer', 4, array(
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
        $this->hasOne('DomainObjectModel_Publication as Publication', array(
             'local' => 'publication_id',
             'foreign' => 'publication_id'));
    }
}