          </div>
        </div>
      </div>
    </div>

    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <?php if (!empty($loadDataTables)): ?>
      <script src="assets/js/plugin/datatables/datatables.min.js"></script>
    <?php endif; ?>
    <script src="assets/js/kaiadmin.min.js"></script>
    <?php if (!empty($loadDataTables)): ?>
      <script>
        $(function () {
          var selector = <?= json_encode((string) ($dataTableSelector ?? '#questionsTable'), JSON_UNESCAPED_SLASHES); ?>;
          if ($.fn.DataTable && $(selector).length) {
            $(selector).DataTable({
              pageLength: 10,
              order: [[0, 'desc']],
              columnDefs: [{ targets: 3, orderable: false, searchable: false }],
              language: {
                search: 'Rechercher:',
                lengthMenu: 'Afficher _MENU_ lignes',
                info: 'Affichage de _START_ a _END_ sur _TOTAL_ questions',
                infoEmpty: 'Aucune question disponible',
                zeroRecords: 'Aucun resultat trouve',
                paginate: {
                  first: 'Premier',
                  last: 'Dernier',
                  next: 'Suivant',
                  previous: 'Precedent'
                }
              }
            });
          }
        });
      </script>
    <?php endif; ?>
    <?php if (!empty($loadValidationJs)): ?>
      <script src="js/validation.js"></script>
    <?php endif; ?>
  </body>
</html>
