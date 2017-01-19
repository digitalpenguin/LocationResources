<?php
require_once MODX_CORE_PATH.'model/modx/modprocessor.class.php';
require_once MODX_CORE_PATH.'model/modx/processors/resource/create.class.php';
require_once MODX_CORE_PATH.'model/modx/processors/resource/update.class.php';
/**
 * @package locationresources
 */
class Location extends modResource
{
    public $showInContextMenu = true;
    function __construct(xPDO & $xpdo) {
        parent:: __construct($xpdo);
        $this->set('class_key', 'Location');
    }
    public static function getControllerPath(xPDO &$modx) {
        return $modx->getOption('locationresources.core_path', null, $modx->getOption('core_path') . 'components/collections/') . 'controllers/';
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

}

class LocationUpdateProcessor extends modResourceUpdateProcessor {
    public $profile;
    /**
     * Do any processing before the fields are set
     * @return boolean
     */
    public function beforeSet() {
        $this->updateProfile();
        $this->setProperty('cacheable',true);
        return parent::beforeSet();
    }
    public function updateProfile() {
        $this->profile = $this->object->getOne('Profile');
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
        $this->updateProfile();
        $this->setProperty('cacheable',true);
        return parent::beforeSet();
    }
    public function updateProfile() {
        $this->profile = $this->modx->newObject('LocationProfile');
        $this->object->addOne($this->profile);
        $this->profile->fromArray($this->getProperties());
        $this->profile->save();
        return $this->profile;
    }

}