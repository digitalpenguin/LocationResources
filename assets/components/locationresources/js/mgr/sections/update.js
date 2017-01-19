LocationResources.page.UpdateLocation = function(config) {
    config = config || {record:{}};
    config.record = config.record || {};
    Ext.applyIf(config,{
        panelXType: 'locationresources-panel-location-update'
    });
    LocationResources.page.UpdateLocation.superclass.constructor.call(this,config);
};
Ext.extend(LocationResources.page.UpdateLocation,MODx.page.UpdateResource);
Ext.reg('locationresources-page-location-update',LocationResources.page.UpdateLocation);