<?php
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
}