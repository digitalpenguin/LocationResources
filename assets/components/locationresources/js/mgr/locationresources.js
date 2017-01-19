var LocationResources = function(config) {
    config = config || {};
    LocationResources.superclass.constructor.call(this,config);
};
Ext.extend(LocationResources,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config:{},renderer:{}
});
Ext.reg('locationresources',LocationResources);
LocationResources = new LocationResources();