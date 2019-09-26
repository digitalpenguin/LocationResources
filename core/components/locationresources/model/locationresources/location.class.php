<?php
require_once MODX_CORE_PATH.'model/modx/modprocessor.class.php';
require_once MODX_CORE_PATH.'model/modx/processors/resource/create.class.php';
require_once MODX_CORE_PATH.'model/modx/processors/resource/update.class.php';
/**
 * @package locationresources
 */
class Location extends modResource {

    public $showInContextMenu = true;
    function __construct(xPDO & $xpdo) {
        parent:: __construct($xpdo);
        $this->set('class_key', 'Location');
    }
    public function duplicate(array $options = array()) {
        $duplicated = parent::duplicate($options);
        if ($duplicated instanceof Location) {
            $locationProfile = $this->getOne('Profile');
            $locationProfile->duplicate($duplicated);
        }
        return $duplicated;
    }
    public static function getControllerPath(xPDO &$modx) {
        return $modx->getOption('locationresources.core_path', null, $modx->getOption('core_path') . 'components/locationresources/') . 'controllers/';
    }
    public function getContextMenuText() {
        $this->xpdo->lexicon->load('locationresources:default');
        return array(
            'text_create' => $this->xpdo->lexicon('locationresources.system.text_create'),
            'text_create_here' => $this->xpdo->lexicon('locationresources.system.text_create_here'),
        );
    }
    public function getResourceTypeName() {
        $this->xpdo->lexicon->load('locationresources:default');
        return $this->xpdo->lexicon('locationresources.system.type_name');
    }

    public function toArray($keyPrefix = '', $rawValues = false, $excludeLazy = false, $includeRelated = false)
    {
        $fields = parent::toArray($keyPrefix, $rawValues, $excludeLazy, true);
        $c = $this->xpdo->newQuery('LocationProfile');
        $c->where(array('location' => $fields['id']));
        $c->select($this->xpdo->getSelectColumns('LocationProfile', 'LocationProfile', '', array('id', 'location'), true));
        $profile = $this->getOne('Profile', $c);
        if ($profile) {
            $fields = array_merge($fields, $profile->toArray('location_', false, true));
        }
        return $fields;
    }

    public function get($k, $format = null, $formatTemplate = null)
    {
        if (is_array($k)) {
            $fields = parent::get($k, $format, $formatTemplate);
            $c = $this->xpdo->newQuery('LocationProfile');
            $c->where(array('location' => $fields['id']));
            $c->select($this->xpdo->getSelectColumns('LocationProfile', 'LocationProfile', '', array(
                'id', 'location'
            ), true));
            $profile = $this->getOne('Profile', $c);
            if ($profile) {
                $fields = array_merge($fields, $profile->toArray('location_', false, true));
            }
            return $fields;
        } else {
            return parent::get($k, $format, $formatTemplate);
        }
    }
}

class LocationUpdateProcessor extends modResourceUpdateProcessor {
    public $profile;
    /**
     * Do any processing before the fields are set
     * @return boolean
     */
    public function beforeSet() {
        // Check to see if the resource is already a LocationResource.
        if($this->object instanceof Location) {
            $this->updateProfile();
        } else {
            $this->createProfile();
        }
        $this->setProperty('cacheable',true);
        return parent::beforeSet();
    }
    // If an existing standard resource is changed to a LocationResource, a profile still needs to be created.
    public function createProfile() {
        $this->profile = $this->modx->newObject('LocationProfile',array(
            'location' => $this->object->get('id')
        ));

        // Grab default location settings and populate record
        $lat = $this->modx->getOption('locationresources.default_latitude');
        $lng = $this->modx->getOption('locationresources.default_longitude');
        $this->setProperty('lat',$lat);
        $this->setProperty('lng',$lng);
        $this->setProperty('zoom_level',$this->modx->getOption('locationresources.default_zoom_level'));
        if(empty($this->getProperty('has_marker'))) {
            $this->setProperty('has_marker',0);
        }
        if(empty($this->getProperty('marker_lat'))) {
            $this->setProperty('marker_lat',$lat);
        }
        if(empty($this->getProperty('marker_lng'))) {
            $this->setProperty('marker_lng', $lng);
        }

        $this->profile->fromArray($this->getProperties());
        $this->profile->save();
        return $this->profile;
    }
    // If the resource being updated is already a LocationResource, just use the existing profile.
    public function updateProfile() {
        $this->profile = $this->object->getOne('Profile');
        // Check to make sure profile record exists. If it doesn't create a new one instead of updating.
        // This is merely a safety check to avoid errors if the database record is accidentally deleted.
        if($this->profile === null) {
            return $this->createProfile();
        }
        $this->profile->fromArray($this->getProperties());
        return $this->profile;
    }
}

class LocationCreateProcessor extends modResourceCreateProcessor {
    public $profile;
    /**
     * Do any processing before the fields are set
     * @return boolean
     */
    public function beforeSet() {
        $this->createProfile();
        $this->setProperty('cacheable',true);
        return parent::beforeSet();
    }
    public function createProfile() {
        $this->profile = $this->modx->newObject('LocationProfile',array(
            'location' => $this->object->get('id')
        ));
        // Grab default location settings and populate record
        $lat = $this->modx->getOption('locationresources.default_latitude');
        $lng = $this->modx->getOption('locationresources.default_longitude');
        $zoom = $this->modx->getOption('locationresources.default_zoom_level');

        if(empty($this->getProperty('lat'))) {
            $this->setProperty('lat',$lat);
        }
        if(empty($this->getProperty('lng'))) {
            $this->setProperty('lng',$lng);
        }
        if(empty($this->getProperty('zoom_level'))) {
            $this->setProperty('zoom_level', $zoom);
        }

        if(empty($this->getProperty('has_marker'))) {
            $this->setProperty('has_marker',0);
        }
        if(empty($this->getProperty('marker_lat'))) {
            $this->setProperty('marker_lat',$lat);
        }
        if(empty($this->getProperty('marker_lng'))) {
            $this->setProperty('marker_lng', $lng);
        }
        $this->profile->fromArray($this->getProperties());
        $this->profile->save();
        $this->object->addOne($this->profile);
        return $this->profile;
    }
}
