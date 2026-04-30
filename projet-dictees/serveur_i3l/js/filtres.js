document.addEventListener('DOMContentLoaded', function(){

    const filterType = document.getElementById('filter-type');
    const filterNiveau = document.getElementById('filter-niveau');
    const filterTitre = document.getElementById('filter-titre');
    const rows = document.querySelectorAll('.dictees-row');

    function filterTable() {
        const typeValue = filterType.value.toLowerCase();
        const niveauValue = filterNiveau.value.toLowerCase();
        const titreValue = filterTitre.value.toLowerCase();

        rows.forEach(row => {
            const rowType = row.getAttribute('data-type').toLowerCase();
            const rowNiveau = row.getAttribute('data-niveau').toLowerCase();
            const rowTitre = row.getAttribute('data-titre').toLowerCase();

            const matchesType = typeValue === "" || rowType === typeValue;
            const matchesNiveau = niveauValue === "" || rowNiveau === niveauValue;
            const matchesTitre = rowTitre.includes(titreValue);

            row.style.display = (matchesType && matchesNiveau && matchesTitre) ? "" : "none";
        });
    }

    if(filterType) filterType.addEventListener('change', filterTable);
    if(filterNiveau) filterNiveau.addEventListener('change',filterTable);
    if(filterTitre) filterTitre.addEventListener('input',filterTable);
});
