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
        app.displayInputNeedsInModal();
        
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
};

document.addEventListener('DOMContentLoaded', app.init);
