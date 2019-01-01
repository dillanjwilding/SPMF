<?php
/**
 * 
 */
class SQLQuery
{
    protected $dbHandle;
    protected $result;

    /**
     * Connects to database
     */
    function connect($address, $account, $pwd, $name)
    {
        $this->dbHandle = @mysql_connect($address, $account, $pwd);
        if ($this->dbHandle != 0) {
            if (mysql_select_db($name, $this->dbHandle)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    /**
     * Disconnects from database
     */
    public function disconnect()
    {
        if (@mysql_close($this->dbHandle) != 0) {
            return 1;
        } else {
            return 0;
        }
    }
    
    /**
     * 
     */
    public function selectAll()
    {
        return $this->query('select * from `'.$this->table.'`');
    }
    
    /**
     * 
     */
    public function select($id)
    {
        $query = 'select * from `'.$this->_table.'` where `id` = \''.mysql_real_escape_string($id).'\'';
        return $this->query($query, 1);    
    }

    /**
     * Custom SQL Query
     */
    function query($query, $singleResult = 0)
    {
        $this->result = mysql_query($query, $this->dbHandle);

        if (preg_match("/select/i", $query)) {
            $result = array();
            $table = array();
            $field = array();
            $tempResults = array();
            $numOfFields = mysql_num_fields($this->result);
            for ($i = 0; $i < $numOfFields; ++$i) {
                array_push($table, mysql_field_table($this->result, $i));
                array_push($field, mysql_field_name($this->result, $i));
            }

        
            while ($row = mysql_fetch_row($this->result)) {
                for ($i = 0; $i < $numOfFields; ++$i) {
                    $table[$i] = trim(ucfirst($table[$i]), "s");
                    $tempResults[$table[$i]][$field[$i]] = $row[$i];
                }
                if ($singleResult == 1) {
                    mysql_free_result($this->result);
                    return $tempResults;
                }
                array_push($result, $tempResults);
            }
            mysql_free_result($this->result);
            return $result;
        }
    }

    /**
     * Get number of rows
     */
    function getNumRows()
    {
        return mysql_num_rows($this->result);
    }

    /**
     * Free resources allocated by a query 
     */
    function freeResult()
    {
        mysql_free_result($this->result);
    }

    /**
     * Get error string
     */
    function getError()
    {
        return mysql_error($this->dbHandle);
    }
}
