<?php
session_start();
if ($_SESSION['role'] != "admin") {
    header("Location: ../index.php");
    exit();
}
require "header.php";
?>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<!-- css -->
<link rel="stylesheet" href="./css/log_table.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
<style type="text/css" class="init">
    .red {
    background-color: red !important;
    }
</style>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
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
            ajax: './includes/mysql_general.inc.php',
            order: [[0, 'desc']],
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
    });
</script>

<!-- body -->
<div class="container body-container">
    <div class="table-wrapper">
        <div class="table-title">
            <div class="row">
                <div class="col-sm-6">
                    <h2><b>MySQL General Log</b></h2>
                </div>
            </div>
        </div>
        <table id="mysql_general_log_table" class="table table-striped table-border">
            <thead>
                <tr class="text-center">
                    <th>Event Time</th>
                    <th>User Host</th>
                    <th>Thread ID</th>
                    <th>Server ID</th>
                    <th>Command Type</th>
                    <th class="th-lg">Argument</th>
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
    </div>
</div>



<?php
require "footer.php";
?>