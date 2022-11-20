<html>
    <head>
        <link rel="stylesheet" type="text/css" href="./datatable_media/css/jquery.dataTables.css">
        <style type="text/css" class="init">
            .red {
            background-color: red !important;
            }
        </style>
        <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script type="text/javascript" language="javascript" src="./datatable_media/js/jquery.dataTables.js"></script>
        <script type="text/javascript" class="init">
            $(document).ready(function () {
                $('#mysql_general_log_table tfoot th').each(function (index) {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Search ' + title + '" id="' + index +'"/>');
                });
                var table = $('#mysql_general_log_table').DataTable({
                    processing: true,
                    serverSide: true,
                    search: {
                        return: true,
                    },
                    ajax: './includes/mysql_general_alert.inc.php',
                    "createdRow": function(row, data, dataIndex) {
                        if (data[5].match("^DROP") == "DROP") {
                            $(row).addClass('red');
                        }
                    },
                });
                $("#mysql_general_log_table tfoot th").children("input").on('keypress', function(e) {
                    var col_id = $(this).attr("id");
                    if (e.which == 13){
                        table.columns(col_id).search(this.value).draw();
                    }
                });
                // $('#mysql_general_log_table tbody tr td:contains("DROP")').css('background-color', 'red');
            });
        </script>
    </head>
    <body>
        <table id="mysql_general_log_table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Event Time</th>
                    <th>User Host</th>
                    <th>Thread ID</th>
                    <th>Server ID</th>
                    <th>Command Type</th>
                    <th>Argument</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Event Time</th>
                    <th>User Host</th>
                    <th>Thread ID</th>
                    <th>Server ID</th>
                    <th>Command Type</th>
                    <th>Argument</th>
                </tr>
            </tfoot>
        </table>
    </body>
</html>