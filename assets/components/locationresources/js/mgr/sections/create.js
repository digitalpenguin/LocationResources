LocationResources.page.CreateLocation = function(config) {
    config = config || {record:{}};
    config.record = config.record || {};
    Ext.applyIf(config,{
        panelXType: 'locationresources-panel-location-create'
    });
    LocationResources.page.CreateLocation.superclass.constructor.call(this,config);
};
Ext.extend(LocationResources.page.CreateLocation,MODx.page.CreateResource);
Ext.reg('locationresources-page-location-create',LocationResources.page.CreateLocation);