<?php

/**
 * Location Resources
 *
 * @var \MODX\Revolution\modX $modx
 * @var array $scriptProperties
 */
if (!$modx->services->has('locationresources')) {
    return;
}

$locationResources = $modx->services->get('locationresources');
if (!($locationResources instanceof LocationResources\LocationResources)) return '';

//
//$className = "\\LocationResources\\Events\\{$modx->event->name}";
//if (class_exists($className)) {
//    /** @var \LocationResources\Events\Event $handler */
//    $handler = new $className($modx, $scriptProperties);
//    $handler->run();
//}

return;