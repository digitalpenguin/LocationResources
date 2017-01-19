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
    /** @var modX $modx */
    $modx =& $object->xpdo;

    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modelPath = $modx->getOption('locationresources.core_path');

            if (empty($modelPath)) {
                $modelPath = '[[++core_path]]components/locationresources/model/';
            }

            if ($modx instanceof modX) {

                $modx->addExtensionPackage('locationresources', $modelPath, array (
));

            }

            break;
        case xPDOTransport::ACTION_UNINSTALL:
            if ($modx instanceof modX) {
                $modx->removeExtensionPackage('locationresources');
            }

            break;
    }
}
return true;