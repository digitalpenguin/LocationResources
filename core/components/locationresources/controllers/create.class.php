<?php
class LocationCreateManagerController extends ResourceCreateManagerController {
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
        $this->addJavascript($managerUrl.'assets/modext/sections/resource/create.js');

        $this->addJavascript($locationResourcesJsUrl.'locationresources.js');
        $this->addJavascript('https://maps.googleapis.com/maps/api/js?key='.$this->modx->getOption('locationresources.api_key'));
        $this->addLastJavascript($locationResourcesJsUrl.'widgets/locationresources.panel.create.js');
        $this->addLastJavascript($locationResourcesJsUrl.'sections/create.js');

        $this->addHtml('
        <style>#map {height:'.$this->modx->getOption("locationresources.map_height").'px;}</style>
        <script type="text/javascript">
        // <![CDATA[
        MODx.config.publish_document = "'.$this->canPublish.'";
        MODx.onDocFormRender = "'.$this->onDocFormRender.'";
        MODx.ctx = "'.$this->resource->get('context_key').'";
        Ext.onReady(function() {
            MODx.load({
                xtype: "locationresources-page-location-create"
                ,resource: "'.$this->resource->get('id').'"
                ,record: '.$this->modx->toJSON($this->resourceArray).'
                ,publish_document: "'.$this->canPublish.'"
                ,canSave: '.($this->canSave ? 1 : 0).'
                ,canEdit: '.($this->canEdit ? 1 : 0).'
                ,canCreate: '.($this->canCreate ? 1 : 0).'
                ,canDuplicate: '.($this->canDuplicate ? 1 : 0).'
                ,canDelete: '.($this->canDelete ? 1 : 0).'
                ,show_tvs: '.(!empty($this->tvCounts) ? 1 : 0).'
                ,mode: "create"
            });
        });
        // ]]>
        </script>');
        /* load RTE */
        $this->loadRichTextEditor();
    }
}