<?php

require_once("config.php");

/* 
 * Giorgio Desideri
 */
class WnMapConnection {

    private $conn = null;

    
    public function __construct() {
        $this->open_connection();
    }

    public function __destruct() {
        $this->close_connection();
    }

    
    /**
     * Function to open the THIS resident connection.
     *
     */
    private function open_connection() {
        // host=sheep port=5432 dbname=mary user=lamb password=foo"
        $conn_str = 
            "host=".PSQL_HOST." ".
            "port=".PSQL_PORT." ".
            "dbname=".PSQL_DB." ".
            "user=".PSQL_USER." ".
            "password=".PSQL_PASS;

        $this->conn = pg_connect($conn_str) or die("Impossible to connect to DB ".pg_errormessage());
    }

    /**
     * Function to close resident connection
     */
    private function close_connection() {
        pg_close($this->conn);
    }


    /**
     * Function to execute sql statement with its parameters.
     *
     * @param sql   statement
     * @param params    sql-statement parameters
     *
     * @return resutl of statement
     */
    public function executeQuery($sql='', $params=array() ){
        //var_dump("execute: ".$sql);
        
        $result = pg_execute($this->conn, $sql, $params);

        //var_dump("return: ".$result);

        return $result;
    }

    
}
?>