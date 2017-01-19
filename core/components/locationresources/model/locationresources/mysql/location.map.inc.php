<?php
/**
 * @package locationresources
 */
$xpdo_meta_map['Location']= array (
  'package' => 'locationresources',
  'version' => '0.1',
  'extends' => 'modResource',
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
      'class' => 'LocationProfile',
      'local' => 'id',
      'foreign' => 'location',
      'cardinality' => 'one',
      'owner' => 'local',
    ),
  ),
);
