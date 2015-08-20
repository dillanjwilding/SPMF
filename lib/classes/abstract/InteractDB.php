<?php
    // Author: Joshua Dickerson
    // InteractDB() is a MySQL database wrapper class
    // meant to eliminate the use of in-model DB queries
    // by providing a data-type which performs simple 
    // INSERT, SELECT, and UPDATE queries
    // but that also has a mechanism for providing
    // custom queries. 

    class InteractDB{
        // as defined in config.php
        private $dbName = DATABASE_NAME;
        private $host = DATABASE_HOST;
        private $dbPass = DATABASE_PASSWORD;
        private $user = DATABASE_USER;
        public $dsn = null;	// for PDO
        public $error = false; // if we catch an error set to true 

        public $connection = null;

        public $action = null;			// this is the action to be performed on the DB (SELECT INSERT etc)
        public $numberEntries = null;	// this is the number of total items to be acted upon
        private $data = array();		// the array of data to be INSERTED (or whatever)
        public $returnedRows = array();


        function __construct($action = null, $data=null){
            // logThis('InteractDB called');
            $this->dsn = "mysql:dbname=".DATABASE_NAME.";host=".DATABASE_HOST;
            $this->data = $data;
            $this->connection = $this->dbConnect();
            $this->action = $action;
            $this->parseActions();
        }



        private function parseActions(){
            switch($this->action){
                case "insert":
                $this->insertStatement();
                break;

                case "select":
                $this->selectStatement();
                break;

                case "update":
                $this->updateStatement();
                break;

                case "custom":
                $this->customStatement();
                break;
            }

        } // end parseActions



        public function dbConnect(){
            //Connect to the database and select the database		
            try{
                $connection = new PDO($this->dsn, DATABASE_USER, DATABASE_PASSWORD);
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                return $connection;
            }catch(PDOException $err){$this->error = true;}	// if we cant connect, return null	
        } // end dbConnect


        private function selectStatement(){
            $data = $this->data;
            $connection = $this->connection;
            $numEntries = $this->numberEntries;
            $tableName = $data['tableName'];	// tableName must be specified
            unset($data['tableName']);


            // A temporary array to hold the fields in an intermediate state
            $whereClause = array();

            // Iterate over the data and convert to individual clause elements
            foreach ($data as $key => $value) {
                $whereClause[] = "$key = :$key";
            }
            // Construct the query
            if (count($whereClause) > 0 ){
                // we have a conditional
                $query = 'SELECT * FROM '.$tableName.' WHERE '.implode(' OR ', $whereClause).'';

            }else{
                // grab all rows from table
                $query = 'SELECT * FROM '.$tableName.';';
            }

            // logThis($query);


            // Prepare the query
            try{
                $stmt = $connection->prepare($query);
                $stmt->execute($data);
                $this->returnedRows = $stmt->fetchAll();
            }catch(Exception $e){
                $this->error = true;
            }
        } // end selectStatement

        private function insertStatement(){
            $data = $this->data;
            $connection = $this->connection;
            $numEntries = $this->numberEntries;
            $tableName = $data['tableName'];	// tableName must be specified
            unset($data['tableName']);
            $questionMarks = "";

            // A temporary array to hold the fields in an intermediate state
            $fieldName = array();
            $fieldValue = array();

            // Iterate over the data and convert to individual clause elements
            foreach ($data as $key => $value) {
                $fieldName[] = "$key,";
                $fieldValue[] = ":$key,";
            }
            $fieldNames = rtrim(implode($fieldName), ",");
            $fieldValues = rtrim(implode($fieldValue), ",");

            try{
                // Construct the query
                $query = 'INSERT INTO '.$tableName.' ('.$fieldNames.') VALUES ('.$fieldValues.');';
                // var_dump($query);
                // // Prepare the query
                $stmt = $connection->prepare($query);
                // // Execute the query
                $stmt->execute($data);
            }catch(Exception $e){
                $this->error = true;
            }

        } // end insertStatement


        public function customStatement($query){
            $connection = $this->connection;
            try{
                // var_dump($query);
                $stmt = $connection->prepare($query);
                // Execute the query
                $stmt->execute();
                $this->returnedRows = $stmt->fetchAll();
            }catch (Exception $e){
                // logThis($e);
                $this->error = true;
            }	
        } // customStatement

        private function updateStatement(){

            // the data array must contain an entry for $data['tableName'] for the SQL WHERE clause
            // the data array must contain an entry for $data['tableKeyName'] for the SQL WHERE clause
            // the data array must contain an entry for $data['tableKey'] for the SQL WHERE clause
            $data = $this->data;
            if(!isset($data['tableName']) || (!isset($data['tableKeyName'])) || (!isset($data['tableKey']))){
                // do error because we don't have enough info to do a proper update
                echo "not working";
            }else{
            $connection = $this->connection;
            $numEntries = $this->numberEntries;
            $tableName = $data['tableName'];	// tableName must be specified
            $tableKeyName = $data['tableKeyName'];	// tableKeyName must be specified
            $tableKey = $data['tableKey'];	// tableKey must be specified
            unset($data['tableKeyName']);
            unset($data['tableKey']);
            unset($data['tableName']);



            // A temporary array to hold the fields in an intermediate state
            $whereClause = array();

            // Iterate over the data and convert to individual clause elements
            foreach ($data as $key => $value) {
                $whereClause[] = "$key = :$key";
            }
            try{
                // Construct the query
                $query = 'UPDATE '.$tableName.' SET '.implode(', ', $whereClause).' WHERE '.$tableKeyName.' ="'.$tableKey.'";';
                // Prepare the query
                // logThis($query);

                // var_dump($query);
                $stmt = $connection->prepare($query);
                // var_dump($stmt);
                // Execute the query
                $stmt->execute($data);
            }catch (Exception $e){
                echo $e;
                $this->error = true;
            }	

            } // end error checking else clause
        } // end insertStatement

    } // end InteractDB class

?>
