LocationResources
=================
***Google Maps Custom Resource Class Extra for MODX Revolution***


LocationResources is a basic integration between the Google Maps API and MODX Resources.
It allows drag and drop in the MODX manager to position maps and set markers which is then reflected on the web context (or others).

This is the initial release.

Usage:
------
First add your Google Maps API Key into the system settings.
Then, in the manager simply create a new resource of "Location" type.
You will see the map displayed above the main resource content field.
You can drag the map around and zoom to position it. 
You can choose to add a marker, position it and add an info window to it.

Remember to save the resource!

Then to have it displayed on your web context:

Call this snippet on any template/chunk attached to the LocationResource: 
***[[!locationResourcesMap]]***

This will display a map with all the default settings.

For customization, you can call the snippet with four parameters:
**[[!locationResources? &docid=`[resource id]` &tpl=`myCustomTpl` &js=`myCustomJS` &css=`myCustomDefaultCSS`]]**


Parameters
==========

&docid
------
The document id of the resource you wish to target for map display. Defaults to the current resource if not supplied.

&tpl 
----
Put the name of your custom chunk that contains the main map div.

&js
---
Put the name of your custom chunk that contains the script to display the map.

&css
----
Put the name of your custom chunk that contains your own css. (Or you can choose not to use default css in your system settings and style on your own).
