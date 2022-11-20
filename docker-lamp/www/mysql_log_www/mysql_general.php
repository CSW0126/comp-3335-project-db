<html>
    <head>
        <link rel="stylesheet" type="text/css" href="./datatable_media/css/jquery.dataTables.css">
        <style type="text/css" class="init">
        
        </style>
        <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script type="text/javascript" language="javascript" src="./datatable_media/js/jquery.dataTables.js"></script>
        <script type="text/javascript" class="init">
            $(document).ready(function () {
                $('#example').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: './includes/mysql_general.inc.php',
                });
            });
        </script>
    </head>
    <body>
        <table id="example" class="display" style="width:100%">
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