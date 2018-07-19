jQuery(document).ready(function($) {

  //console.log("hello from myjquery.js")


  var locations = [];
  var infoWindowContent = [];
  var map;

  //add content to info window 
   function createInfoContent(info, title, address){
    console.log("info ", info);
    var arr = [];
    var str = '<div class="info_content"><h4><strong>';
    str += title;
    str += ' </strong></h4>';
    str += '<p> ' + info + ' </p>';
    str += '<p>' +address+ '</p></div>';
    arr.push(str);
    console.log("str ", str)
    infoWindowContent.push(arr);
   }

    /*
    map expects...array of arrays called locations
    
        eg var locations = [['Regional Office', 53.2767588, -9.0474665]]

    map also expects... array of arrays called infoWindowContent

        eg  
        [['<div class="info_content">' +
       '<h4>MCR (Mail Coach Road Community Centre), Sligo</h4>' +
       '<p>Homework club open for 7 – 12year olds, Mon – Friday from 3.30 – 5.30pm.</p>' +
       '<p>Activities include Homework, Soccer, Pool, Snooker, Arts & Crafts, Dance, Summer Camps.</p>' +
       '<p><span>Contact:</span> Catherine McCann, NCYCS 0719144150</p>' +
       '</div>']]
    */

    $.ajax({
      type:'GET',
      dataType: 'JSON',
      url: my_ajax_obj.ajax_url,
      data: {
        _ajax_nonce: my_ajax_obj.nonce,
        action: 'map_action'
      },
      success: function(response){  
        jQuery.each(response.data, function(i,r){
          var arr = [];
          arr.push(r.marker, r.lat, r.lng, r.address);
          locations.push(arr);
          createInfoContent(r.desc, r.marker, r.address);
          //now call initMap();
          initMap();
        })
      },
      error: function(response){
        console.log("error ", response);
      }
    });

      
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 9,
          center: {lat: 53.276762, lng: -9.0496552},

        });

      // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {

          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });

        // Create an array of alphabetical characters used to label the markers.
        var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';



 
   
        var infoWindow = new google.maps.InfoWindow(), marker, i; 
        for( i = 0; i < locations.length; i++ ) {
            var position = new google.maps.LatLng(locations[i][1], locations[i][2]);

            marker = new google.maps.Marker({
                position: position,
                map: map,
                icon: 'http://www.ncycs.ie/wp-content/themes/Zephyr-child/img/marker.png',
                title: locations[i][0]
            });
        
        // Allow each marker to have an info window    
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.open(map, marker);
            }
        })(marker, i));
        // Automatically center the map fitting all markers on the screen
       markers.push(marker);
    }
  var markerCluster = new MarkerClusterer(map, markers,
    {imagePath: 'http://youthworkgalway.ie/wp-content/uploads/2018/06/ywig_tiny_colour.png'});
    }
 





});