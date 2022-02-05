<?php

namespace LocationResources\Model;

use xPDO\Om\xPDOSimpleObject;

/**
 * @package locationresources
 */
class LocationProfile extends xPDOSimpleObject {

    // Duplicates the location profile data.
    public function duplicate(Location $resource) {
        $oldProfile = $this->toArray();
        unset($oldProfile['id']);
        $newProfile = $this->xpdo->newObject('LocationProfile');
        $newProfile->fromArray($oldProfile, '', true);
        $newProfile->set('location', $resource->get('id'));
        $newProfile->save();
        return $newProfile;
    }
    
    public function toArray($keyPrefix = '', $rawValues = false, $excludeLazy = false, $includeRelated = false) {
        $ta = parent::toArray($keyPrefix, $rawValues, $excludeLazy, $includeRelated);
        $ta = array_map(function ($o) {
            if (is_float($o)) {
                return number_format($o, 6, '.', '');
            } elseif (is_int($o)) {
                return strval($o);
            } else {
                return $o;
            }
        }, $ta);
        return $ta;
    }
}
