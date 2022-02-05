<?php
namespace LocationResources\Model\mysql;

use xPDO\xPDO;

class LocationProfile extends \LocationResources\Model\LocationProfile
{

    public static $metaMap = array (
        'package' => 'LocationResources\\Model\\',
        'version' => '3.0',
        'table' => 'location_profile',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'location' => NULL,
            'lat' => NULL,
            'lng' => NULL,
            'zoom_level' => NULL,
            'has_marker' => NULL,
            'marker_lat' => NULL,
            'marker_lng' => NULL,
            'marker_title' => NULL,
            'marker_desc' => NULL,
            'marker_link' => NULL,
            'marker_link_text' => NULL,
        ),
        'fieldMeta' => 
        array (
            'location' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'phptype' => 'integer',
                'attributes' => 'unsigned',
                'null' => true,
            ),
            'lat' => 
            array (
                'dbtype' => 'decimal',
                'precision' => '10,6',
                'phptype' => 'float',
                'null' => true,
            ),
            'lng' => 
            array (
                'dbtype' => 'decimal',
                'precision' => '10,6',
                'phptype' => 'float',
                'null' => true,
            ),
            'zoom_level' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'phptype' => 'integer',
                'attributes' => 'unsigned',
                'null' => true,
            ),
            'has_marker' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '1',
                'phptype' => 'integer',
                'attributes' => 'unsigned',
                'null' => true,
            ),
            'marker_lat' => 
            array (
                'dbtype' => 'decimal',
                'precision' => '10,6',
                'phptype' => 'float',
                'null' => true,
            ),
            'marker_lng' => 
            array (
                'dbtype' => 'decimal',
                'precision' => '10,6',
                'phptype' => 'float',
                'null' => true,
            ),
            'marker_title' => 
            array (
                'dbtype' => 'varchar',
                'phptype' => 'string',
                'precision' => '191',
                'null' => true,
            ),
            'marker_desc' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'string',
                'null' => true,
            ),
            'marker_link' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'string',
                'null' => true,
            ),
            'marker_link_text' => 
            array (
                'dbtype' => 'varchar',
                'phptype' => 'string',
                'precision' => '191',
                'null' => true,
            ),
        ),
        'aggregates' => 
        array (
            'Location' => 
            array (
                'class' => 'LocationResources\\Model\\Location',
                'local' => 'location',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
        ),
    );

}
