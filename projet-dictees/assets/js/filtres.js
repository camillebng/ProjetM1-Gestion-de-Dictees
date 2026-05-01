document.addEventListener('DOMContentLoaded', function(){

    // Récupère les filtres
    const filterType = document.getElementById('filter-type');
    const filterNiveau = document.getElementById('filter-niveau');
    const filterTitre = document.getElementById('filter-titre');
    const filterDate = document.getElementById('filter-date'); 
    const filterVersion = document.getElementById('filter-version'); 
    const rows = document.querySelectorAll('.dictees-row');

    function filterTable() {
        // Récupération des valeurs sélectionnées
        const typeValue = filterType.value.toLowerCase();
        const niveauValue = filterNiveau.value.toLowerCase();
        const titreValue = filterTitre.value.toLowerCase();
        const dateValue = filterDate.value; 
        const versionValue = filterVersion ? filterVersion.value.toLowerCase() : ""; 

        rows.forEach(row => {
            // Récupère les attributs de données de la ligne
            const rowType = row.getAttribute('data-type').toLowerCase();
            const rowNiveau = row.getAttribute('data-niveau').toLowerCase();
            const rowTitre = row.getAttribute('data-titre').toLowerCase();
            const rowDate = row.getAttribute('data-date'); 
            const rowVersion = row.getAttribute('data-version').toLowerCase();

            // Correspondance pour chaque ligne
            const matchesType = typeValue === "" || rowType === typeValue;
            const matchesNiveau = niveauValue === "" || rowNiveau === niveauValue;
            const matchesTitre = rowTitre.includes(titreValue);
            const matchesDate = dateValue === "" || rowDate === dateValue;
            const matchesVersion = versionValue === "" || rowVersion === versionValue; 

            // Affiche la ligne si toutes les conditions sont remplies
            row.style.display = (matchesType && matchesNiveau && matchesTitre && matchesDate && matchesVersion) ? "" : "none";
        });
    }

    // Ecouteurs d'événements sur le formulaire
    if(filterType) filterType.addEventListener('change', filterTable);
    if(filterNiveau) filterNiveau.addEventListener('change', filterTable);
    if(filterTitre) filterTitre.addEventListener('input', filterTable);
    if(filterDate) filterDate.addEventListener('change', filterTable); 
    if(filterVersion) filterVersion.addEventListener('change', filterTable); 
});