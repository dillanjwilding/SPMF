<?php
/** 
 * InteractDB is a MySQL database wrapper class meant to 
 * eliminate the use of in-model DB queries by providing a 
 * data-type which performs simple INSERT, SELECT, and UPDATE queries
 * but that also has a mechanism for providing custom queries.
 */
class InteractDB {
    // as defined in config.php
    private $dbName = DATABASE_NAME;
    private $host = DATABASE_HOST;
    private $dbPass = DATABASE_PASSWORD;
    private $user = DATABASE_USER;
    private $dsn = null; // for PDO
    private $error = false; // if we catch an error set to true

    private $connection = null;

    private $action = null; // this is the action to be performed on the DB (SELECT INSERT etc)
    private $numberEntries = null; // this is the number of total items to be acted upon
    private $data = array(); // the array of data to be INSERTED (or whatever)
    private $returnedRows = array();

    /**
     * 
     */
    public function __construct($action = null, $data = null)
    {
        $this->dsn = "mysql:dbname=".DATABASE_NAME.";host=".DATABASE_HOST;
        $this->data = $data;
        $this->connection = $this->_dbConnect();
        $this->action = $action;
        $this->_parseActions();
    }

    /**
     * 
     */
    private function _parseActions()
    {
        switch($this->action) {
        case "insert":
            $this->_insertStatement();
            break;
        case "select":
            $this->_selectStatement();
            break;
        case "update":
            $this->_updateStatement();
            break;
        case "custom":
            $this->_customStatement();
            break;
        }

    } // end _parseActions

    /**
     * 
     */
    private function _dbConnect()
    {
        // Connect to the database and select the database
        try {
            $connection = new PDO($this->dsn, DATABASE_USER, DATABASE_PASSWORD);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            return $connection;
        } catch(PDOException $err) {
            $this->error = true;
        } // if we cant connect, return null
    } // end dbConnect

    /**
     * 
     */
    private function _selectStatement()
    {
        $data = $this->data;
        $numEntries = $this->numberEntries;
        $tableName = $data['tableName']; // tableName must be specified
        unset($data['tableName']);

        // A temporary array to hold the fields in an intermediate state
        $whereClause = array();

        // Iterate over the data and convert to individual clause elements
        foreach ($data as $key => $value) {
            $whereClause[] = "$key = :$key";
        }
        // Construct the query
        $query = 'SELECT * FROM '.$tableName.(count($whereClause) > 0 ? ' WHERE '.implode(' OR ', $whereClause) : '').';';

        // Prepare the query
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($data);
            $this->returnedRows = $stmt->fetchAll();
        } catch(Exception $e) {
            $this->error = true;
        }
    } // end _selectStatement

    /**
     * 
     */
    private function _insertStatement()
    {
        $data = $this->data;
        $tableName = $data['tableName']; // tableName must be specified
        unset($data['tableName']);
        $questionMarks = "";

        // A temporary array to hold the fields in an intermediate state
        $fieldName = array();
        $fieldValue = array();

        // Iterate over the data and convert to individual clause elements
        /*foreach ($data as $key => $value) {
            $fieldName[] = $key;
            $fieldValue[] = ":".$key;
        }*/
        $fieldNames = implode(",", array_keys($data));
        $fieldValues = ":".implode(",:", array_keys($data));

        try {
            // Construct the query
            $query = 'INSERT INTO '.$tableName.' ('.$fieldNames.') VALUES ('.$fieldValues.');';
            // // Prepare and execute the query
            $this->connection->prepare($query)->execute($data);
        } catch(Exception $e) {
            $this->error = true;
        }

    } // end insertStatement

    /**
     * 
     */
    private function _customStatement($query)
    {
        try {
            // Prepare the query
            $stmt = $this->connection->prepare($query);
            // Execute the query
            $stmt->execute();
            $this->returnedRows = $stmt->fetchAll();
        } catch (Exception $e) {
            $this->error = true;
        }
    } // customStatement

    /**
     * 
     */
    private function _updateStatement()
    {
        // the data array must contain an entry for $data['tableName'] for the SQL WHERE clause
        // the data array must contain an entry for $data['tableKeyName'] for the SQL WHERE clause
        // the data array must contain an entry for $data['tableKey'] for the SQL WHERE clause
        $data = $this->data;
        if (!isset($data['tableName']) || (!isset($data['tableKeyName'])) || (!isset($data['tableKey']))) {
            // do error because we don't have enough info to do a proper update
            echo "not working";
        } else {
            $numEntries = $this->numberEntries;
            $tableName = $data['tableName']; // tableName must be specified
            $tableKeyName = $data['tableKeyName']; // tableKeyName must be specified
            $tableKey = $data['tableKey']; // tableKey must be specified
            unset($data['tableKeyName']);
            unset($data['tableKey']);
            unset($data['tableName']);

            // A temporary array to hold the fields in an intermediate state
            $whereClause = array();

            // Iterate over the data and convert to individual clause elements
            foreach ($data as $key => $value) {
                $whereClause[] = "$key = :$key";
            }
            try {
                // Construct the query
                $query = 'UPDATE '.$tableName.' SET '.implode(', ', $whereClause).' WHERE '.$tableKeyName.' ="'.$tableKey.'";';
                // Prepare and execute the query
                $this->connection->prepare($query)->execute($data);
            } catch (Exception $e) {
                $this->error = true;
            }
        } // end error checking else clause
    } // end insertStatement
} // end InteractDB class
