/**
 * needForm module
 */
 const needForm = {

    init : function () {
        needForm.createNeedForm();
    },

    createNeedForm: function() 
    {
        // On click in the button
        document
        .querySelectorAll('.add_item_link')
        .forEach(btn => btn.addEventListener("click", needForm.addFormToCollection));
      
    },

    addFormToCollection: function(e) {
        const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);
        console.log(collectionHolder);
        const item = document.createElement('li');
      
        item.innerHTML = collectionHolder
          .dataset
          .prototype
          .replace(
            /__name__/g,
            collectionHolder.dataset.index
          );
      
        collectionHolder.appendChild(item);
      
        collectionHolder.dataset.index++;
    }

}