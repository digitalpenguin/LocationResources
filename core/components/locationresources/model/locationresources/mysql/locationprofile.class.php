<?php
/**
 * @package locationresources
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/locationprofile.class.php');
class LocationProfile_mysql extends LocationProfile {}
?>