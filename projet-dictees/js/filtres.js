document.addEventListener('DOMContentLoaded', function(){

    const filterType = document.getElementById('filter-type');
    const filterNiveau = document.getElementById('filter-niveau');
    const filterTitre = document.getElementById('filter-titre');
    const filterDate = document.getElementById('filter-date'); 
    const rows = document.querySelectorAll('.dictees-row');

    function filterTable() {
        const typeValue = filterType.value.toLowerCase();
        const niveauValue = filterNiveau.value.toLowerCase();
        const titreValue = filterTitre.value.toLowerCase();
        const dateValue = filterDate.value; // Récupère la date YYYY-MM-DD

        rows.forEach(row => {
            const rowType = row.getAttribute('data-type').toLowerCase();
            const rowNiveau = row.getAttribute('data-niveau').toLowerCase();
            const rowTitre = row.getAttribute('data-titre').toLowerCase();
            const rowDate = row.getAttribute('data-date'); // Récupéré via l'attribut data-date en PHP

            const matchesType = typeValue === "" || rowType === typeValue;
            const matchesNiveau = niveauValue === "" || rowNiveau === niveauValue;
            const matchesTitre = rowTitre.includes(titreValue);
            
            // Logique de filtrage par date
            // Si le champ date est vide, on affiche tout.
            // Si une date est choisie, on compare avec l'attribut data-date de la ligne.
            const matchesDate = dateValue === "" || rowDate === dateValue;


            row.style.display = (matchesType && matchesNiveau && matchesTitre && matchesDate) ? "" : "none";
        });
    }


    if(filterType) filterType.addEventListener('change', filterTable);
    if(filterNiveau) filterNiveau.addEventListener('change', filterTable);
    if(filterTitre) filterTitre.addEventListener('input', filterTable);
    if(filterDate) filterDate.addEventListener('change', filterTable); // Ajout de l'écouteur sur la date
});