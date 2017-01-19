<?php
$tpl = $modx->getOption('tpl', $scriptProperties, 'locationResourcesTpl');
$js = $modx->getOption('js', $scriptProperties, 'locationResourcesScript');
$css = $modx->getOption('css', $scriptProperties, 'locationResourcesCSS');

$locationResources = $modx->getService(
    'locationresources',
    'LocationResources',
    $modx->getOption('locationresources.core_path', null, $modx->getOption('core_path').'components/locationresources/').'model/locationresources/',$scriptProperties
);
if (!($locationResources instanceof LocationResources)) {
    return;
}

return $locationResources->getMap($tpl,$js,$css);