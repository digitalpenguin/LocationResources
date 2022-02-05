<?php

/**
 * @var \MODX\Revolution\modX $modx
 * @var array $namespace
 */
$modx->addPackage('LocationResources\Model', $namespace['path'] . 'src/', null, 'LocationResources\\');

$modx->services->add('locationresources', function($c) use ($modx) {
    return new LocationResources\LocationResources($modx);
});