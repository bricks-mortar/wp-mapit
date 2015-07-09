// make maps available on the window
window.maps = [];

let mapElements = document.getElementsByClassName('wp-mapit');
addMaps(mapElements);

/**
 * Creates all maps for a page
 * @param mapElements
 */
function addMaps(mapElements) {

  // cut off if the page has no maps
  if (mapElements.length < 1) return;

  Array.prototype.forEach.call(mapElements, (mapEl) => {

    function initialize() {

      // map options
      // @todo: allow user to pass options
      let mapOptions = {
        center: { lat: 28.9932355, lng: -9.9536244 },
        zoom: 2,
        streetViewControl: false,
        mapTypeControl: false,
        panControl: false
      };

      // attach map to canvas
      let mapCanvas = mapEl.querySelector('.wp-mapit-map');
      let map = new google.maps.Map(mapCanvas,
        mapOptions);

      // attach markers to map object
      map.markers = [];

      let dataElements = mapEl.getElementsByClassName('wp-mapit-location');
      Array.prototype.forEach.call(dataElements, (dataEl) => {

        // coordinates
        let lat = dataEl.dataset['mapit_lat'];
        let lng = dataEl.dataset['mapit_long'];
        let myLatlng = new google.maps.LatLng(lat, lng);

        // location
        let address = dataEl.dataset['mapit_address'];
        let city = dataEl.dataset['mapit_city'];
        let state = dataEl.dataset['mapit_state'];
        let zip = dataEl.dataset['mapit_zip'];
        let country = dataEl.dataset['mapit_country'];
        let fullAddr = dataEl.dataset['mapit_full_addr'];

        // location content
        let locId = dataEl.dataset.locid;
        let locTitle = dataEl.dataset.loctitle;
        let locContent = dataEl.dataset.loccontent;
        let locImage = dataEl.dataset.locimage;

        // infowindow content
        let infoWindowContent = infoWindowMarkup(locTitle, locContent, fullAddr, locImage);

        // create a marker
        let marker = new google.maps.Marker({
          position: myLatlng,
          map: map,
          title: locId
        });

        // create an infowindow
        let infowindow = new google.maps.InfoWindow({
          content: infoWindowContent
        });

        // todo: toggle based on option
        google.maps.event.addListener(marker, 'click',
          () => infowindow.open(map, marker));

        // add infoWindow to marker
        marker.infoWindow = infowindow;
        map.markers.push(marker);

        // place marker on map
        marker.setMap(map);
      });

      // keep track of maps
      window.maps.push(map);
    }

    google.maps.event.addDomListener(window, 'load', initialize);
  });

}

/**
 * Returns markup for infoWidow
 * @param title
 * @param content
 * @param address
 * @param image
 * @returns {*}
 */
function infoWindowMarkup(title, content, address, image) {
  return `<div class="mapit-location">
            <div class="featured-image">
              <img src="${image}">
            </div>
            <div class="location-info">
              <h2 class="title">${title}</h2>
              <div class="content">${content}</div>
              <address>${address}</address>
            </div>
          </div>`;
}
