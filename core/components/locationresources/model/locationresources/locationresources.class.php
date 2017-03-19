<?php

/**
 * The main LocationResources service class.
 *
 * @package locationresources
 */
class LocationResources {
    public $modx = null;
    public $namespace = 'locationresources';
    public $cache = null;
    public $options = array();
    public $profile = null;

    public function __construct(modX &$modx, array $options = array()) {
        $this->modx =& $modx;
        $this->namespace = $this->getOption('namespace', $options, 'locationresources');

        $corePath = $this->getOption('core_path', $options, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/locationresources/');
        $assetsPath = $this->getOption('assets_path', $options, $this->modx->getOption('assets_path', null, MODX_ASSETS_PATH) . 'components/locationresources/');
        $assetsUrl = $this->getOption('assets_url', $options, $this->modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/locationresources/');

        /* loads some default paths for easier management */
        $this->options = array_merge(array(
            'namespace' => $this->namespace,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'templatesPath' => $corePath . 'templates/',
            'assetsPath' => $assetsPath,
            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'connectorUrl' => $assetsUrl . 'connector.php'
        ), $options);

        $this->modx->addPackage('locationresources', $this->getOption('modelPath'));
        $this->modx->lexicon->load('locationresources:default');
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, $options = array(), $default = null) {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } elseif (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
                $option = $this->modx->getOption("{$this->namespace}.{$key}");
            }
        }
        return $option;
    }

    public function initializeMap() {
        if ($this->modx->getOption('locationresources.api_key') == '') {
            return 'ERROR: For Google Maps to display, you must enter your API Key in the MODX System Settings!';
        }
        $this->modx->regClientStartupScript('https://maps.googleapis.com/maps/api/js?key='.$this->modx->getOption('locationresources.api_key'));

        return false;
    }

    public function setMapPlaceholders() {
        $this->modx->setPlaceholders(array(
            'map_lat'       =>  $this->profile->get('lat'),
            'map_lng'       =>  $this->profile->get('lng'),
            'zoom_lvl'      =>  $this->profile->get('zoom_level'),
            'has_marker'    =>  $this->profile->get('has_marker'),
            'marker_lat'    =>  $this->profile->get('marker_lat'),
            'marker_lng'    =>  $this->profile->get('marker_lng'),
            'marker_title'  =>  $this->profile->get('marker_title'),
            'marker_desc'   =>  $this->profile->get('marker_desc'),
            'marker_link'   =>  $this->profile->get('marker_link')
        ),'lr.');
    }

    public function getMap($tpl,$js,$css,$docid) {
        if(!$targetdoc = $this->modx->getObject('modResource',$docid)) return 'Error: could not load requested resource (ID:' . $docid . ')';
        if($targetdoc->get('class_key') != "Location") return 'Error: resource is not a Location type (ID:' . $docid . ')';
        if(!$this->profile = $targetdoc->getOne('Profile')) return 'Error: resource did not contain an extended profile (ID:' . $docid . ')';
        $this->setMapPlaceholders();

        // Check for GMaps API Key and add lib to head.
        $error = $this->initializeMap();
        if($error != false) return $error;
        
        // Check if docid has already been used as a DIV id and if it has, start incrementing
	$proposedDIVID = "lr_map" . $docid;
        $baseID = $docid;
		if(strpos($this->modx->getRegisteredClientStartupScripts(),$proposedDIVID)!==FALSE) {
	        for($i=1;$i<99;$i++) {
		        $proposedDIVID = "lr_map" . $baseID . "_" . $i;
		        if(strpos($this->modx->getRegisteredClientStartupScripts(),$proposedDIVID)===FALSE) {
			        $docid = $baseID . "_" . $i;
			        break;
		        }
	        }
        }

        // Get chunks and return errors if missing.
        if(!$map = $this->modx->getChunk($tpl,array('lr.docid'=>$docid))) return 'Error: Unable to find the locationResourcesTpl chunk!';
        if(!$jsChunk = $this->modx->getChunk($js,array('lr.docid'=>$docid))) return 'Error: Unable to find the locationResourcesScript chunk!';

        // Add default CSS to <head>
        if ($this->getOption('use_default_css')) {
            if(!$cssChunk = $this->modx->getChunk($css,array('lr.docid'=>$docid))) return 'Error: Unable to find the locationResourcesCSS chunk!';
            $this->modx->regClientStartupHTMLBlock($cssChunk);
        }

        // Add JS script to bottom of <body>
        $this->modx->regClientHTMLBlock($jsChunk);

        return $map;
    }
}
