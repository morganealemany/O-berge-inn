/**
 * Filter module
 */
const filter = {

    init: function() {
        filter.bindEvents();
    },
    
    /**
     * Method dealing the click on filter elements
     */
    bindEvents: function() {
        document.querySelectorAll('.all-events').forEach(function(element) {
            element.addEventListener('click', filter.handleClickOnAllEvents);
        });
        document.querySelectorAll('.organized-events').forEach(function(element) {
            element.addEventListener('click', filter.handleClickOnOrganizedEvents);
        });
        document.querySelectorAll('.invited-events').forEach(function(element) {
            element.addEventListener('click', filter.handleClickOnInvitedEvents);
        });
        document.querySelectorAll('.archived-events').forEach(function(element) {
            element.addEventListener('click', filter.handleClickOnArchivedEvents);
        })
    },
 
    handleClickOnAllEvents: function(evt) {
        filter.showAllEvents();
    },
 
    handleClickOnOrganizedEvents: function(evt) {
        filter.showOnlyEventsOrganized();
    },
 
    handleClickOnInvitedEvents: function(evt) {
        filter.showOnlyEventsInvited();
    },

    handleClickOnArchivedEvents: function(evt) {
        filter.showOnlyEventsArchived();
    },

    /**
    * Method allowing the display of only the organized events
    */
    showOnlyEventsOrganized: function() {
        // Recovering elements of the events list
        const allEvents = document.querySelectorAll('#myevent');

        // Browse the list
        for (const event of allEvents) {
            // If the selected item has the invited class
            if(event.classList.contains('invited')){
                // We add the visually-hidden class to hidden it 
                event.classList.add('visually-hidden');
            } else {
                // On the contrary if the selected item is not invited (organized)
                // We remove the visually-hidden class to display it.
                event.classList.remove('visually-hidden');
            }
        }
    },

    /**
     * Method allowing the display of only the invited events
     */
    showOnlyEventsInvited: function() {
        //Recover the event list
        const allEvents = document.querySelectorAll('#myevent');

        // brows the list
        for (const event of allEvents) {
            // If the selected element contain the class organizer
            if(event.classList.contains('organizer')){
                //add a class for hide it 
                event.classList.add('visually-hidden');
            } else {
                // Else, if the selected element don't contain the class organizer 
                // Remove the class hidden for show it
                event.classList.remove('visually-hidden');
            }
        }
    },

    /**
     * Method allowing the display of only the archived events
     */
    showOnlyEventsArchived: function() {
        const allEvents = document.querySelectorAll('#myevent');
        // console.log(allEvents);
        for (const event of allEvents) {
            if(event.classList.contains('archived')){
                event.classList.remove('visually-hidden');
            } else {
                event.classList.add('visually-hidden');
            }
        }
    },

    /**
     * Method allowing the display of all the events
     */
    showAllEvents: function() {
        //recovering the Event List
        const allEvents = document.querySelectorAll('#myevent');

        // brows the list
        for (const event of allEvents) {
            
            event.classList.remove('visually-hidden');
        }
    },
}