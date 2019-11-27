---------------------------------------
LocationResources
---------------------------------------
Version: 1.4.0
Author: Murray Wood <murray@digitalpenguin.hk>
---------------------------------------

LocationResources is a basic integration between the Google Maps API and MODX Resources.
It allows drag and drop in the MODX manager to position maps and set markers which is then reflected on the web context (or others).


27/11/2019 - **Version 1.4.0**

NEW - Usage with getResources
----
_Note: If you're not familiar with getResources, start with the "Normal Usage" instructions further below._

Make sure you have the getResources extra installed from the MODX package manager and your Google Maps API key is in the system settings.
Create your location resource(s).
Make a normal getResources snippet call and include your location resource(s).

For example if I have resource (id: 18) which is a parent and includes a list of location resources then I might call getResources like this:

[[getResources?
    &parents=`18`
    &tpl=`myCustomTpl`
]]

Create a chunk called `myCustomTpl` or whatever you fancy.

Inside the chunk you can call the location values directly as placeholders with a `location_` prefix.

<div>
    <h4>[[+pagetitle]]</h4>
    <p>This chunk displays the values of this location resource.</p>
    <ul>
        <li>Latitude: [[+location_lat]]</li>
        <li>Longitude: [[+location_lng]]</li>
        <li>Zoom Level: [[+location_zoom]]</li>
        <li>Does the Map Have a Marker?: [[+location_has_marker]]</li>
        <li>Marker Latitude: [[+location_marker_lat]]</li>
        <li>Marker Longitude: [[+location_marker_lng]]</li>
        <li>Marker Title: [[+location_marker_title]]</li>
        <li>Marker Description: [[+location_marker_description]]</li>
        <li>Marker Link (href): [[+location_marker_link]]</li>
        <li>Marker Link Text : [[+location_marker_link_text]]</li>
    </ul>
</div>

You can then of course display maps however you like using these values.



Normal Usage:
------

First add your Google Maps API Key into the system settings.
Then, in the manager simply create a new resource of "Location" type.
You will see the map displayed above the main resource content field.
You can drag the map around and zoom to position it. 
You can choose to add a marker, position it and add an info window to it.

Remember to save the resource!

Then to have it displayed on your web context:

Call this snippet on any template/chunk attached to the LocationResource: 
[[locationResourcesMap]]

This will display a map with all the default settings.

For customization, you can call the snippet with currently five parameters. e.g. :
[[locationResourcesMap?
    &docid=`27`
    &parents=`3,42,50`
    &tpl=`myCustomTpl`
    &js=`myCustomJS`
    &css=`myCustomDefaultCSS`
]]


Parameters
==========

&docid
------
The ID of the resource you wish to pull for map display. Defaults to the current resource if not supplied.

&tpl 
----
Put the name of your custom chunk that contains the main map div.

&js
---
Put the name of your custom chunk that contains the script to display the map.

&css
----
Put the name of your custom chunk that contains your own css. (Or you can choose not to use default css in your system settings and style on your own).

&parents
--------
Accepts a comma-separated list of resource ids. If any children of that resource are LocationResources and have a marker set, those markers will be added via clustering to the map.
This is in addition to the marker that belongs to the LocationResource controlling the main map. This marker will not be clustered. If you don't want it shown, you can remove it.

