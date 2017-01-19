<?php
/**
 * @package locationresources
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/location.class.php');
class Location_mysql extends Location {}
?>