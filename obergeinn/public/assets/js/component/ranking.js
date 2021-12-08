/**
 * Ranking module
 */
 const ranking = {

     init: function() {
        document.querySelectorAll(".table-sortable th").forEach(headerCell => {
            headerCell.addEventListener("click", () => {
                const tableElement = headerCell.parentElement.parentElement.parentElement;
                const headerIndex = Array.prototype.indexOf.call(headerCell.parentElement.children, headerCell);
                const currentIsAscending = headerCell.classList.contains("th-sort-asc");
    
                ranking.sortTableByColumn(tableElement, headerIndex, !currentIsAscending);
            });
        });
    },
    
    /**
     * Sorts a HTML table
     * 
     * @param {HTMLTableElement} table The table to sort
     * @param {number} column The index of the column to sort
     * @param {boolean} asc Determines if the sorting will be in ascending
     */
    sortTableByColumn: function(table, column, asc = true) 
    {
        const dirModifier = asc ? 1 : -1;
        const tBody = table.tBodies[0];
        // console.log(tBody.firstchild);
        const rows = Array.from(tBody.querySelectorAll("tr"));

        // Sort each row
        const sortedRows = rows.sort((a, b) => {
            if (column == 1) {
                const aColText = a.querySelector(`td:nth-child(${ column + 1 })`).dataset.timestamp;
                const bColText = b.querySelector(`td:nth-child(${ column + 1 })`).dataset.timestamp;

                return aColText > bColText ? (1 * dirModifier) : (-1 * dirModifier);
            } else {
                const aColText = a.querySelector(`td:nth-child(${ column + 1 })`).textContent.trim();
                const bColText = b.querySelector(`td:nth-child(${ column + 1 })`).textContent.trim();

                return aColText > bColText ? (1 * dirModifier) : (-1 * dirModifier);
            }    
        });
        
        // Remove all existing TRs from the table
        while (tBody.firstchild) {
            tBody.removeChild(tBody.firstchild); 
        }

        // Re-add the newly sorted rows
        tBody.append(...sortedRows);

        // Remember how the column is currently sorted
        table.querySelectorAll("th").forEach(th => th.classList.remove("th-sor-asc", "th-sort-desc"));
        table.querySelector(`th:nth-child(${ column + 1 })`).classList.toggle("th-sort-asc", asc);
        table.querySelector(`th:nth-child(${ column + 1 })`).classList.toggle("th-sort-desc", !asc);
    },
}