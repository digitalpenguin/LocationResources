<?php
namespace LocationResources\Model\mysql;

use xPDO\xPDO;

class Location extends \LocationResources\Model\Location
{

    public static $metaMap = array (
        'package' => 'LocationResources\\Model\\',
        'version' => '3.0',
        'extends' => 'MODX\\Revolution\\modResource',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
        ),
        'fieldMeta' => 
        array (
        ),
        'composites' => 
        array (
            'Profile' => 
            array (
                'class' => 'LocationResources\\Model\\LocationProfile',
                'local' => 'id',
                'foreign' => 'location',
                'cardinality' => 'one',
                'owner' => 'local',
            ),
        ),
    );

}
