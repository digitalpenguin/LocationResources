LocationResources.panel.UpdateLocation = function(config) {
    config = config || {};
    this.googleMap = null;
    this.marker = null;

    LocationResources.panel.UpdateLocation.superclass.constructor.call(this,config);
    this.addMapPanel(config);
};
Ext.extend(LocationResources.panel.UpdateLocation,MODx.panel.Resource,{

    addMapDataFields: function(config) {
        //console.log(config.record);
        var me = this;
        return {
            id: 'map-data-fieldset',
            autoHeight: true,
            layout:'column',
            border: false,
            anchor: '100%',
            defaults: {
                labelSeparator: '',
                labelAlign: 'top',
                border: false,
                msgTarget: 'under'
            },
            items:[{
                id:'map-data-left-col',
                columnWidth: .33,
                layout:'form',
                items:[{
                    xtype: 'numberfield',
                    id: 'has-marker-field',
                    hidden: true,
                    name: 'has_marker',
                    value: config.record.has_marker
                },{
                    xtype: 'numberfield',
                    id: 'map-data-latitude',
                    decimalPrecision: 6,
                    name: 'lat',
                    fieldLabel: 'Latitude',
                    value: config.record.lat,
                    anchor: '100%',
                    listeners: {
                        'blur':function(field, e){
                            config.record.lat = field.getValue();
                            this.googleMap.setCenter({lat: config.record.lat, lng: config.record.lng});
                        },
                        scope: this
                    }
                }]

            },{
                id:'map-data-mid-col',
                layout: 'form',
                columnWidth: .33,
                items:[{
                    xtype: 'numberfield',
                    id: 'map-data-longitude',
                    decimalPrecision: 6,
                    name: 'lng',
                    fieldLabel: 'Longitude',
                    value: config.record.lng,
                    anchor: '100%',
                    listeners: {
                        'blur':function(field, e){
                            config.record.lng = field.getValue();
                            this.googleMap.setCenter({lat: config.record.lat, lng: config.record.lng});
                        },
                        scope: this
                    }
                }]
            },{
                id:'map-data-right-col',
                layout:'form',
                columnWidth: .33,
                items:[{
                    xtype: 'locationresources-combo-zoomlevel',
                    id: 'map-data-zoom',
                    name: 'zoom_level',
                    fieldLabel: 'Zoom Level',
                    value: config.record.zoom_level,
                    anchor: '100%',
                    listeners: {
                        'select':function(combo, record, index){
                            config.record.zoom_level = Number(combo.getRawValue());
                            this.googleMap.setZoom(config.record.zoom_level);
                        },
                        scope: this
                    }
                }]
            },{
                id:'map-data-button-col',
                layout: 'form',
                width: 124,
                items: [{
                    xtype: 'button',
                    id: 'button-add-marker',
                    text: '<i class="icon icon-map-marker"></i> Add Marker',
                    handler: function() {
                        if(Ext.getCmp('has-marker-field').getValue() == 1) {
                            me.showCurrentMarkerPanel(config);
                        } else {
                            me.showNewMarkerPanel(config);
                        }

                    },scope:this
                },{
                    xtype: 'button',
                    id: 'button-remove-marker',
                    text: '<i class="icon icon-map-marker"></i> Remove Marker',
                    hidden:true,
                    handler: function() {
                        this.marker.setMap(null);
                        me.removeMarkerPanel(config);
                    },scope:this
                }]
            }]
        }
    },

    addMapPanel: function(config) {
        var me = this;
        var mainPanel = Ext.getCmp('modx-panel-resource');
        var mapPanel = {
            name: 'map',
            id: 'location-map',
            autoHeight: true,
            headerCssClass: 'map-panel-header',
            collapsible: true,
            animCollapse: true,
            hideMode: 'offsets',
            title: 'Location Map',
            cls:'',
            bodyCssClass: 'location-map-body main-wrapper',
            items: [{
                id: 'map-data-container'
            },{
                html: '<div id="map"></div>',
                anchor: '100%'

            }],
            listeners: {
                'afterrender': function(panel) {
                    var targetLocation = {lat: config.record.lat, lng: config.record.lng};
                    me.googleMap = new google.maps.Map(document.getElementById('map'), {
                        zoom: config.record.zoom_level,
                        scrollwheel: false,
                        center: targetLocation
                    });
                    google.maps.event.addListener(me.googleMap, 'center_changed', function() {
                        Ext.getCmp('map-data-latitude').setValue(me.googleMap.getCenter().lat());
                        Ext.getCmp('map-data-longitude').setValue(me.googleMap.getCenter().lng());
                    });
                    google.maps.event.addListener(me.googleMap, 'zoom_changed', function() {
                        Ext.getCmp('map-data-zoom').setValue(me.googleMap.getZoom());
                    });

                    var mapDataFields = this.addMapDataFields(config);
                    Ext.getCmp('map-data-container').add(mapDataFields);
                    this.addMarkerPanel(config);
                },scope:this
            }
        };

        mainPanel.insert(2,mapPanel);

    },

    addMarkerPanel: function(config) {
        var me = this;
        var markerPanel = {
            id: 'location-map-marker-panel',
            anchor: '100%',
            layout: 'column',
            bodyCssClass: 'main-wrapper',
            defaults: {
                columnWidth: '.20',
                labelSeparator: '',
                labelAlign: 'top',
                border: false,
                msgTarget: 'under'
            },
            items: [{
                id: 'marker-col-1',
                layout: 'form',
                items: [{
                    xtype: 'numberfield',
                    decimalPrecision: 6,
                    id: 'marker-data-lat',
                    anchor: '100%',
                    value: config.record.marker_lat,
                    name: 'marker_lat',
                    fieldLabel: 'Latitude',
                    listeners: {
                        'blur':function(field, e){
                            config.record.marker_lat = field.getValue();
                            this.marker.setPosition({lat: config.record.marker_lat, lng: config.record.marker_lng});
                        },
                        scope: this
                    }
                }]
            },{
                id: 'marker-col-2',
                layout: 'form',
                items: [{
                    xtype: 'numberfield',
                    decimalPrecision: 6,
                    id: 'marker-data-lng',
                    anchor: '100%',
                    value: config.record.marker_lng,
                    name: 'marker_lng',
                    fieldLabel: 'Longitude',
                    listeners: {
                        'blur':function(field, e){
                            config.record.marker_lng = field.getValue();
                            this.marker.setPosition({lat: config.record.marker_lat, lng: config.record.marker_lng});
                        },
                        scope: this
                    }
                }]
            },{
                id: 'marker-col-3',
                layout: 'form',
                items: [{
                    xtype: 'textfield',
                    id: 'marker-data-title',
                    anchor: '100%',
                    value: config.record.marker_title,
                    name: 'marker_title',
                    fieldLabel: 'Title',
                    listeners: {
                        'blur':function(field, e){
                            config.record.marker_title = field.getValue();
                            me.setMarkerInfoContent(config.record.marker_title);
                        },
                        scope: this
                    }
                }]
            },{
                id: 'marker-col-4',
                layout: 'form',
                items: [{
                    xtype: 'textfield',
                    id: 'marker-data-desc',
                    anchor: '100%',
                    value: config.record.marker_desc,
                    name: 'marker_desc',
                    fieldLabel: 'Description',
                    listeners: {
                        'blur':function(field, e){
                            config.record.marker_desc = field.getValue();
                            me.setMarkerInfoContent(config.record.marker_desc);
                        },
                        scope: this
                    }
                }]
            },{
                id: 'marker-col-5',
                layout: 'form',
                items: [{
                    xtype: 'textfield',
                    id: 'marker-data-link',
                    anchor: '100%',
                    value: config.record.marker_link,
                    name: 'marker_link',
                    fieldLabel: 'Link',
                    listeners: {
                        'blur':function(field, e){
                            config.record.marker_link = field.getValue();
                            me.setMarkerInfoContent(config.record.marker_link);
                        },
                        scope: this
                    }
                }]
            }]
        };

        if (Ext.getCmp('has-marker-field').getValue() == 0) {
            markerPanel.hidden = true;
            Ext.getCmp('location-map').add(markerPanel);

        } else {
            Ext.getCmp('location-map').add(markerPanel);
            me.showCurrentMarkerPanel(config);
        }
    }

    ,dropMarkerPin: function(config) {
        var me = this;
        var latLng;
        if (Ext.getCmp('has-marker-field').getValue() == 0) {
            latLng = {lat: me.googleMap.getCenter().lat(), lng: me.googleMap.getCenter().lng()};
            Ext.getCmp('has-marker-field').setValue(1);
        } else {
            latLng = {lat: config.record.marker_lat, lng: config.record.marker_lng};
        }

        this.marker = new google.maps.Marker({
            id: 'marker-info-window',
            position: latLng,
            animation: google.maps.Animation.DROP,
            draggable: true,
            map: this.googleMap
        });
    }

    ,showCurrentMarkerPanel: function(config) {
        var me = this;
        Ext.getCmp('location-map-marker-panel').show();
        Ext.getCmp('button-add-marker').hide();
        Ext.getCmp('button-remove-marker').show();

        me.dropMarkerPin(config);

        Ext.getCmp('marker-data-lat').setValue(config.record.marker_lat);
        Ext.getCmp('marker-data-lng').setValue(config.record.marker_lng);
        google.maps.event.addListener(this.marker, 'position_changed', function() {
            Ext.getCmp('marker-data-lat').setValue(me.marker.getPosition().lat());
            Ext.getCmp('marker-data-lng').setValue(me.marker.getPosition().lng());
        });

        me.addMarkerInfoWindow();

        // Only show info window if there's at least one value to put in there.
        if (Ext.getCmp('marker-data-title').getValue() != '' || Ext.getCmp('marker-data-desc').getValue() != '' || Ext.getCmp('marker-data-link').getValue() != '') {
            me.setMarkerInfoContent();
        }
        me.doLayout();
    }

    ,showNewMarkerPanel: function(config) {
        var me =this;
        Ext.getCmp('location-map-marker-panel').show();
        Ext.getCmp('button-add-marker').hide();
        Ext.getCmp('button-remove-marker').show();

        me.dropMarkerPin(config);

        Ext.getCmp('marker-data-lat').setValue(me.marker.getPosition().lat());
        Ext.getCmp('marker-data-lng').setValue(me.marker.getPosition().lng());
        google.maps.event.addListener(this.marker, 'position_changed', function() {
            Ext.getCmp('marker-data-lat').setValue(me.marker.getPosition().lat());
            Ext.getCmp('marker-data-lng').setValue(me.marker.getPosition().lng());
        });

        me.addMarkerInfoWindow();
        me.doLayout();

    }

    ,removeMarkerPanel: function() {
        Ext.getCmp('button-remove-marker').hide();
        Ext.getCmp('button-add-marker').show();
        Ext.getCmp('location-map-marker-panel').hide();
        Ext.getCmp('has-marker-field').setValue(0);
        Ext.getCmp('marker-data-title').setValue('');
        Ext.getCmp('marker-data-desc').setValue('');
        Ext.getCmp('marker-data-link').setValue('');
    }

    ,addMarkerInfoWindow:function() {
        var me = this;
        me.marker.info = new google.maps.InfoWindow();

        google.maps.event.addListener(me.marker, 'click', function() {
            me.marker.info.open(me.googleMap, me.marker);
        });
    }

    ,setMarkerInfoContent: function() {
        var me = this;
        var title = Ext.getCmp('marker-data-title').getValue();
        var desc = Ext.getCmp('marker-data-desc').getValue();
        var link = Ext.getCmp('marker-data-link').getValue();
        var content = '<h4>'+title+'</h4><p>'+desc+'</p>';
        if (link != '' && link != null) {
            content += '<a href="'+link+'">Link</a>';
        }
        me.marker.info.setContent(content,me.marker.info);
        me.marker.info.open(me.googleMap, me.marker);
    }
});
Ext.reg('locationresources-panel-location-update',LocationResources.panel.UpdateLocation);



/**
 * ComboBox for selecting Zoom Level of a Google Map.
 * @param config
 * @constructor
 */
LocationResources.combo.ZoomLevel = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.ArrayStore({
            id: 0
            ,fields: ['level']
            ,data: [
                ['1'],
                ['2'],
                ['3'],
                ['4'],
                ['5'],
                ['6'],
                ['7'],
                ['8'],
                ['9'],
                ['10'],
                ['11'],
                ['12'],
                ['13'],
                ['14'],
                ['15'],
                ['16'],
                ['17'],
                ['18'],
                ['19'],
                ['20'],
                ['21']
            ]
        })
        ,mode: 'local'
        ,displayField: 'level'
        ,valueField: 'level'
    });
    LocationResources.combo.ZoomLevel.superclass.constructor.call(this,config);
};
Ext.extend(LocationResources.combo.ZoomLevel,MODx.combo.ComboBox);
Ext.reg('locationresources-combo-zoomlevel',LocationResources.combo.ZoomLevel);