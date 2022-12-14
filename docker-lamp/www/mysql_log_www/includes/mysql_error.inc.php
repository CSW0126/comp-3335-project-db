<?php
 
session_start();
if ($_SESSION['role'] != "admin") {
    header("Location: ../index.php");
    exit();
}
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
// DB table to use
$table = 'error_log';
 
// Table's primary key
$primaryKey = 'LOGGED';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'LOGGED', 'dt' => 0 ),
    array( 'db' => 'THREAD_ID',  'dt' => 1 ),
    array( 'db' => 'PRIO',   'dt' => 2 ),
    array( 'db' => 'ERROR_CODE',     'dt' => 3 ),
    array( 'db' => 'SUBSYSTEM',   'dt' => 4 ),
    array( 'db' => 'DATA',     'dt' => 5 ),
);
 
// SQL server connection information
$sql_details = array(
    'user' => 'mysql_log_monitoring_user',
    'pass' => 'password+++',
    'db'   => 'performance_schema',
    'host' => 'db'
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( './ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);