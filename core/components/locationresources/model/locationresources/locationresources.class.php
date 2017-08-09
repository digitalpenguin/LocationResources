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
        $this->modx->regClientStartupScript($this->modx->getOption('locationresources.assets_url').'js/libs/markerclusterer.js');
        return false;
    }

    public function setClusterMarkers($clusterParents) {
        $collection = $this->modx->getCollection('modResource',array(
            'class_key:='     =>  'Location',
            'AND:parent:IN'    =>  $clusterParents
        ));
        $markers = array();
        foreach($collection as $rec) {
            $profile = null;
            if(!$profile = $rec->getOne('Profile')) {
                $this->modx->log(1,'Error: resource did not contain an extended profile (Resource id:' . $rec->get('id') . ')');
                continue;
            }
            //$this->modx->log(1,'Profile id: '.$profile->get('id'));
            if($profile->get('has_marker')) {
                array_push($markers,$this->setClusterMarker($profile));
            }

        }
        return $markers;
    }

    public function setClusterMarker($profile) {
        $pid = $profile->get('id');
        $mLat = $this->convertDecimalToDot($profile->get('marker_lat'));
        $mLng = $this->convertDecimalToDot($profile->get('marker_lng'));
        $output = "
        var clusterMarker{$pid} = new google.maps.Marker({
            position: new google.maps.LatLng({$mLat}, {$mLng}),
            draggable: false,
            clickable: true,
        });
        clusterMarkers.push(clusterMarker{$pid});
        ";
        return $output;
    }

    /**
     * Sets all the placeholders.
     * @param $docid
     */
    public function setMapPlaceholders($docid,$clusterMarkers) {
        $clusterMarkers = implode($clusterMarkers);

        $this->modx->setPlaceholders(array(
            'docid'         =>  $docid,
            'map_lat'       =>  $this->convertDecimalToDot($this->profile->get('lat')),
            'map_lng'       =>  $this->convertDecimalToDot($this->profile->get('lng')),
            'zoom_lvl'      =>  $this->profile->get('zoom_level'),
            'has_marker'    =>  $this->profile->get('has_marker'),
            'marker_lat'    =>  $this->convertDecimalToDot($this->profile->get('marker_lat')),
            'marker_lng'    =>  $this->convertDecimalToDot($this->profile->get('marker_lng')),
            'marker_title'  =>  $this->profile->get('marker_title'),
            'marker_desc'   =>  $this->profile->get('marker_desc'),
            'marker_link'   =>  $this->profile->get('marker_link'),
            'cluster_markers'   =>  $clusterMarkers
        ),'lr.');
    }



    /**
     * Replaces any decimal commas with dots for certain locales
     *
     * @param $decimalNum
     * @return mixed
     */
    public function convertDecimalToDot($decimalNum) {
        return str_replace(',', '.', $decimalNum);
    }

    /**
     * This is the main function called by the snippet. It injects the CSS and JS and returns the div for the map.
     * @param $tpl
     * @param $js
     * @param $css
     * @param $docid
     * @return bool|string
     */
    public function getMap($tpl,$js,$css,$docid,$clusterParents) {
        if(!$targetDoc = $this->modx->getObject('modResource',$docid)) return 'Error: could not load requested resource (ID:' . $docid . ')';
        if($targetDoc->get('class_key') != "Location") return 'Error: resource is not a Location type (ID:' . $docid . ')';
        if(!$this->profile = $targetDoc->getOne('Profile')) return 'Error: resource did not contain an extended profile (ID:' . $docid . ')';

        // Check for GMaps API Key and add lib to head.
        $error = $this->initializeMap();
        if($error != false) return $error;

        // Check if docid has already been used as a div id and if it has, start incrementing
        $proposedDivId = '#'.$this->modx->getOption('locationresources.map_div').$docid;
        $baseId = $docid;
		if(strpos($this->modx->getRegisteredClientStartupScripts(),$proposedDivId)!==false) {
		    for($i=1;$i<99;$i++) { // The number 99 is arbitrary
                $proposedDivId = $this->modx->getOption('locationresources.map_div') . $baseId . '_' . $i;
		        if(strpos($this->modx->getRegisteredClientStartupScripts(),$proposedDivId)===false) {
			        $docid = $baseId.'_'.$i;
			        break;
		        }
	        }
        }
        $clusterMarkers = $this->setClusterMarkers($clusterParents);
        $this->setMapPlaceholders($docid, $clusterMarkers);

        // Get chunks and return errors if missing.
        if(!$map = $this->modx->getChunk($tpl)) return 'Error: Unable to find the locationResourcesTpl chunk!';
        if(!$jsChunk = $this->modx->getChunk($js)) return 'Error: Unable to find the locationResourcesScript chunk!';

        // Add default CSS to <head>
        if ($this->getOption('use_default_css')) {
            if(!$cssChunk = $this->modx->getChunk($css)) return 'Error: Unable to find the locationResourcesCSS chunk!';
            $this->modx->regClientStartupHTMLBlock($cssChunk);
        }

        // Add JS script to bottom of <body>
        $this->modx->regClientHTMLBlock($jsChunk);
        return $map;
    }
}
