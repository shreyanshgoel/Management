jQuery(document).ready(function($) {
                var $table1 = jQuery('#table-1');
                // Initialize DataTable
                $table1.DataTable({
                    "aLengthMenu": [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ],
                    "bStateSave": true
                });
                // Initalize Select Dropdown after DataTables is created
                $table1.closest('.dataTables_wrapper').find('select').select2({
                    minimumResultsForSearch: -1
                });
            });