<script>
    var lrMap[[+lr.docid]] = new google.maps.Map(document.getElementById('[[++locationresources.map_div]][[+lr.docid]]'), {
        zoom: [[+lr.zoom_lvl]],
        center: {lat: [[+lr.map_lat]], lng: [[+lr.map_lng]]}
    });
    if ([[+lr.has_marker]]) {
        var marker = new google.maps.Marker({
            position: {lat: [[+lr.marker_lat]], lng: [[+lr.marker_lng]]},
            draggable: false,
            clickable: true,
            map: lrMap[[+lr.docid]]
        });
        var lrTitle = '[[+lr.marker_title]]';
        var lrDesc = '[[+lr.marker_desc]]';
        var lrLink = '[[+lr.marker_link]]';
        var lrContent = '';
        if (lrTitle.length > 0) {
            lrContent += "<h4>[[+lr.marker_title]]</h4>";
        }
        if (lrDesc.length > 0) {
            lrContent += "<p>[[+lr.marker_desc]]</p>";
        }
        if (lrLink.length > 0) {
            lrContent += "<a href='[[+lr.marker_link]]'>Click Here</a>";
        }
        if (lrContent.length > 0) {
            marker.info = new google.maps.InfoWindow({
                content: lrContent
            });
            marker.info.open(lrMap[[+lr.docid]], marker);
            google.maps.event.addListener(marker, 'click', function() {
                marker.info.open(lrMap[[+lr.docid]], marker);
            });
        }
    }
    var clusterMarkers = [];
    [[+lr.cluster_markers]]

    var options = {
        imagePath: '[[++locationresources.assets_url]]img/clusterer/m'
    };

    var markerCluster = new MarkerClusterer(lrMap[[+lr.docid]] , clusterMarkers, options);
    google.maps.event.addDomListener(window, "resize", function() {
        var center = lrMap[[+lr.docid]].getCenter();
        google.maps.event.trigger(lrMap[[+lr.docid]], "resize");
        lrMap[[+lr.docid]].setCenter(center);
    });
</script>
