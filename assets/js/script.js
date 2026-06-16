// fichier: assets/js/script.js
$(document).ready(function() {
    // Confirmation de suppression
    $('.btn-delete, form[onsubmit*="confirm"]').on('submit', function(e) {
        if (!confirm('Êtes-vous sûr de vouloir effectuer cette action ?')) {
            e.preventDefault();
        }
    });
    
    // Validation des formulaires
    $('form').on('submit', function(e) {
        let isValid = true;
        $(this).find('[required]').each(function() {
            if (!$(this).val().trim()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires');
        }
    });
    
    // Auto-dismiss des alertes
    $('.alert').delay(5000).fadeOut('slow');
});

function exportToCSV(tableId, filename) {
    let csv = [];
    let rows = document.querySelectorAll('#' + tableId + ' tr');
    
    rows.forEach(row => {
        let rowData = [];
        row.querySelectorAll('th, td').forEach(cell => {
            rowData.push('"' + cell.innerText.replace(/"/g, '""') + '"');
        });
        csv.push(rowData.join(','));
    });
    
    let blob = new Blob([csv.join('\n')], { type: 'text/csv' });
    let link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename || 'export.csv';
    link.click();
}