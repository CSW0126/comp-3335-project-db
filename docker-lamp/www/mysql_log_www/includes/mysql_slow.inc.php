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
$table = 'slow_log';
 
// Table's primary key
$primaryKey = 'start_time';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'start_time', 'dt' => 0 ),
    array( 'db' => 'user_host',  'dt' => 1 ),
    array( 'db' => 'query_time',   'dt' => 2 ),
    array( 'db' => 'lock_time',     'dt' => 3 ),
    array( 'db' => 'rows_sent',   'dt' => 4 ),
    array( 'db' => 'rows_examined',     'dt' => 5 ),
    array( 'db' => 'db',     'dt' => 6 ),
    array( 'db' => 'last_insert_id',     'dt' => 7 ),
    array( 'db' => 'insert_id',     'dt' => 8 ),
    array( 'db' => 'server_id',     'dt' => 9 ),
    array( 'db' => 'sql_text',     'dt' => 10 ),
    array( 'db' => 'thread_id',     'dt' => 11 ),
);
 
// SQL server connection information
$sql_details = array(
    'user' => 'root',
    'pass' => 'test',
    'db'   => 'mysql',
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