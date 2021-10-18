/**
 * CloneInput module
 */
const cloneInput = {

    init : function () {
        cloneInput.createEmailInput();
    },

    createEmailInput: function() 
    {
        // On click in the button
        document.querySelectorAll('.bi-plus-square-fill').forEach(function(element) {
            element.addEventListener('click', cloneInput.handleClickOnAddEmailInputElement);
        });
    },
    
    handleClickOnAddEmailInputElement: function()
    {
        // Recover the template element in clone 
        const emailInputClone = document.getElementById('email-input').content.cloneNode(true);
        // Display of clone in the twig
        // Select element which contains the input
        const emailInputSection = document.querySelector('.email-input-section');
        // Insert the clone in front of the other elements
        emailInputSection.append(emailInputClone);
    },
}