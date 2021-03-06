 /**
 * App module
 */
 const app = {

    /**
     * Init method who contains the firsts run codes 
     */
    init: function() {
      
        cloneInput.init();
        filter.init();
        needForm.init();
        ranking.init();
        app.displayInputNeedsInModal();
        // app.getGeolocalisation();
        app.displayMapForEvent();
        
    },

    /**
     * Method dealing the display of input into the modal for needs assignations
     */
    displayInputNeedsInModal: function() 
    {
        modalCheckboxes = document.querySelectorAll('.form-check-input-modal');

        modalCheckboxes.forEach(function(element) {
            element.addEventListener('click', app.handleClickOnCheckboxElement);
        });
        
    },

    /**
     * Method called when there is a click action on the modal checkboxes
     * 
     * @param {*} evt 
     */
    handleClickOnCheckboxElement: function(evt) 
    {
        elementClick = evt.target;
        parentNode = elementClick.parentNode;
        inputCheckbox = parentNode.querySelector('.checkbox-need')

        // Adding or remove the hidden attribute on checkbox click
        if (inputCheckbox.hasAttribute('hidden')) {
            inputCheckbox.removeAttribute('hidden');
        } else {
            inputCheckbox.setAttribute('hidden', '');
        }       
    },
    

    // TODO method deprecated using on http. Search for some other way to find the geolocation or switch to https. 
    // /**
    //  * Method retrieving the localisation of the user connected
    //  */
    // getGeolocalisation: function() 
    // {
    //     if ("geolocation" in navigator) {
    //         // console.log('la géolocalisation est disponible')
            
    //         navigator.geolocation.getCurrentPosition(function(position) {
    //             // console.log(position.coords.latitude, position.coords.longitude);
    //             app.displayMapForEvent(position.coords.latitude, position.coords.longitude);
    //             });
    //     } else {
    //         // console.log('la géolocalisation est indisponible')
    //     };

    // },

    /**
     * Method allowed the display of an event map
     */
    displayMapForEvent: function()
    {
        // If the current page is the details of an event
        if ((window.location.pathname).search("evenement/details") === 1 ) {
            // First we will transfom the adress of the event into coordinates
            // ========= MapBox Search API ==============
            // Select the event adress from the DOM
            const address = document.querySelector('#adress').innerHTML;
            
            // Format the adress to use it in the API
            const location = address.replaceAll(' ', '%20');
            
            const apiSearchUrl = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' + location + '.json?access_token=pk.eyJ1IjoibW9yZ2FuZTY2IiwiYSI6ImNrdjBzbTh6eDBrbjMyb2xwdzRyNnVpZnoifQ.oU_h1HqLy6f1-3o58Qkerg';
            
            const searchConfig = {
                method: 'GET',
                mode: 'cors',
                cache: 'no-cache'
            };
            
            fetch(apiSearchUrl, searchConfig)
            .then(function(response) {
                return response.json();
            })
            .then(function(fullCoordinates) {
                
                const destinationCoordinates = fullCoordinates.features[0].center;
                // Then we have to create a map
                const map = L.map('map').setView([destinationCoordinates[1],destinationCoordinates[0]], 12);
                L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
                    maxZoom: 20
                }).addTo(map);
                
                // And a marker for the adress of the event
                const marker = L.marker([destinationCoordinates[1],destinationCoordinates[0]]).addTo(map);
                
                // Then use the current position coordinates transfered in parameters and link the itinerary from the current position and the destination
                marker.bindPopup('<a target="_blank" href="https://www.google.com/maps/search/' + destinationCoordinates[1] + ',' + destinationCoordinates[0] + '" ><h3>Y aller</h3></a>');
                
                // TODO For directions with a geolocation
                // marker.bindPopup('<a target="_blank" href="https://www.google.com/maps/dir/' + latitude + ','  + longitude + '/' + destinationCoordinates[1] + ',' + destinationCoordinates[0] + '" ><h3>Y aller</h3></a>');
            })
        }
        },
    }
    
    document.addEventListener('DOMContentLoaded', app.init);
    