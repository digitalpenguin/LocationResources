<?php
/**
 * Resolve creating db tables
 *
 * THIS RESOLVER IS AUTOMATICALLY GENERATED, NO CHANGES WILL APPLY
 *
 * @package locationresources
 * @subpackage build
 */

if ($object->xpdo) {
    $modx =& $object->xpdo;
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modelPath = $modx->getOption('locationresources.core_path', null, $modx->getOption('core_path') . 'components/locationresources/') . 'model/';
            
            $modx->addPackage('locationresources', $modelPath, null);


            $manager = $modx->getManager();

            $manager->createObjectContainer('LocationProfile');

            break;
    }
}

return true;