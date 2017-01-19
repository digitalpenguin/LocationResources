<?php
/**
 * @package locationresources
 */
$xpdo_meta_map['LocationProfile']= array (
  'package' => 'locationresources',
  'version' => '0.1',
  'table' => 'location_profile',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'location' => 0,
    'lat' => 22.286424,
    'lng' => 114.18151,
    'zoom_level' => 4,
    'has_marker' => 0,
    'marker_lat' => 0,
    'marker_lng' => 0,
    'marker_title' => '',
    'marker_desc' => '',
    'marker_link' => '',
  ),
  'fieldMeta' => 
  array (
    'location' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
    ),
    'lat' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '10,6',
      'phptype' => 'float',
      'null' => true,
      'default' => 22.286424,
    ),
    'lng' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '10,6',
      'phptype' => 'float',
      'null' => true,
      'default' => 114.18151,
    ),
    'zoom_level' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 4,
    ),
    'has_marker' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
    ),
    'marker_lat' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '10,6',
      'phptype' => 'float',
      'null' => true,
      'default' => 0,
    ),
    'marker_lng' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '10,6',
      'phptype' => 'float',
      'null' => true,
      'default' => 0,
    ),
    'marker_title' => 
    array (
      'dbtype' => 'varchar',
      'phptype' => 'string',
      'precision' => '191',
      'null' => false,
      'default' => '',
    ),
    'marker_desc' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'marker_link' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
  ),
  'aggregates' => 
  array (
    'Location' => 
    array (
      'class' => 'Location',
      'local' => 'location',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
