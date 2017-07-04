<?php
class LocationUpdateManagerController extends ResourceUpdateManagerController {
    public $profile;

    public function initialize() {
        $corePath = $this->modx->getOption('locationresources.core_path', null, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/locationresources/');
        $this->locationResources = $this->modx->getService(
            'locationresources',
            'LocationResources',
            $corePath . 'model/locationresources/',
            array(
                'core_path' => $corePath
            )
        );
        parent::initialize();
    }

    public function getLanguageTopics() {
        return array('resource','locationresources:default');
    }

    /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCustomCssJs() {
        $managerUrl = $this->context->getOption('manager_url', MODX_MANAGER_URL, $this->modx->_userConfig);
        $locationResourcesAssetsUrl = $this->modx->getOption('locationresources.assets_url', null, $this->modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/locationresources/');
        $locationResourcesJsUrl = $locationResourcesAssetsUrl . 'js/mgr/';
        $this->addCss($locationResourcesAssetsUrl . 'css/mgr.css');
        $this->addJavascript($managerUrl.'assets/modext/widgets/element/modx.panel.tv.renders.js');
        $this->addJavascript($managerUrl.'assets/modext/widgets/resource/modx.grid.resource.security.local.js');
        $this->addJavascript($managerUrl.'assets/modext/widgets/resource/modx.panel.resource.tv.js');
        $this->addJavascript($managerUrl.'assets/modext/widgets/resource/modx.panel.resource.js');
        $this->addJavascript($managerUrl.'assets/modext/sections/resource/update.js');

        $this->addJavascript($locationResourcesJsUrl.'locationresources.js');
        $this->addJavascript('https://maps.googleapis.com/maps/api/js?key='.$this->modx->getOption('locationresources.api_key'));
        $this->addLastJavascript($locationResourcesJsUrl.'widgets/locationresources.panel.update.js');
        $this->addLastJavascript($locationResourcesJsUrl.'sections/update.js');



        $this->addHtml('
        <style>#map {height:'.$this->modx->getOption("locationresources.map_height").'px;}</style>
        <script type="text/javascript">
        // <![CDATA[
        MODx.config.publish_document = "'.$this->canPublish.'";
        MODx.onDocFormRender = "'.$this->onDocFormRender.'";
        MODx.ctx = "'.$this->resource->get('context_key').'";
        Ext.onReady(function() {
            MODx.load({
                xtype: "locationresources-page-location-update"
                ,resource: "'.$this->resource->get('id').'"
                ,record: '.$this->modx->toJSON($this->resourceArray).'
                ,publish_document: "'.$this->canPublish.'"
                ,preview_url: "'.$this->previewUrl.'"
                ,locked: '.($this->locked ? 1 : 0).'
                ,lockedText: "'.$this->lockedText.'"
                ,canSave: '.($this->canSave ? 1 : 0).'
                ,canEdit: '.($this->canEdit ? 1 : 0).'
                ,canCreate: '.($this->canCreate ? 1 : 0).'
                ,canDuplicate: '.($this->canDuplicate ? 1 : 0).'
                ,canDelete: '.($this->canDelete ? 1 : 0).'
                ,show_tvs: '.(!empty($this->tvCounts) ? 1 : 0).'
                ,mode: "update"
            });
        });
        // ]]>
        </script>');
        /* load RTE */
        $this->loadRichTextEditor();
    }

    /**
     * Used to set values on the resource record sent to the template for derivative classes
     *
     * @return void
     */
    public function prepareResource() {
        /* Add data from related table */
        $this->profile = $this->resource->getOne('Profile');
        // If no values yet available on resource, get values from default system settings.
        if(!$this->profile) {
            if (!$this->resourceArray['lng']) {
                $this->resourceArray['lng'] = floatval($this->modx->getOption('locationresources.default_longitude'));
            }
            if (!$this->resourceArray['lat']) {
                $this->resourceArray['lat'] = floatval($this->modx->getOption('locationresources.default_latitude'));
            }
            if (!$this->resourceArray['zoom_level']) {
                $this->resourceArray['zoom_level'] = (int)$this->modx->getOption('locationresources.default_zoom_level');
            }
        } else {
            $this->resourceArray['lng'] = $this->profile->get('lng');
            $this->resourceArray['lat'] = $this->profile->get('lat');
            $this->resourceArray['zoom_level'] = $this->profile->get('zoom_level');
            $this->resourceArray['has_marker'] = $this->profile->get('has_marker');
            $this->resourceArray['marker_lat'] = $this->profile->get('marker_lat');
            $this->resourceArray['marker_lng'] = $this->profile->get('marker_lng');
            $this->resourceArray['marker_title'] = $this->profile->get('marker_title');
            $this->resourceArray['marker_desc'] = $this->profile->get('marker_desc');
            $this->resourceArray['marker_link'] = $this->profile->get('marker_link');
        }

    }

}