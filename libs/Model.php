<?php

abstract class Model 
{
    // Other model classes will inherit the mysqli connection and template methods for certain queries
    private $conn = null;
    public function __construct()
    {
        if($this->conn === null)
        {
            $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
            if ($this->conn->connect_error) {
                die('Connect Error (' . $this->conn->connect_errno . ':'  . $this->conn->connect_error . ') '
                    . '<br>Please try again later');
            }
        }
    }
    // for INSERT/UPDATE/DELETE queries
    protected function ExecuteEditQuery(string $query, string $datatypes, array $parameters)
    {
        $succes = null;
        try
        {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param($datatypes, ...$parameters);
            $stmt->execute();
            $stmt->close();
            $succes = true;
        }
        catch(Exception $e)
        {
            $succes = false;
        }
        finally
        {
            return $succes;
        }
    }
    // for SELECT queries
    protected function ExecuteSelectQuery(string $query, string $datatypes, array $parameters)
    {
        $stmt = null;
        if($stmt = $this->conn->prepare($query))
        {
            $stmt->bind_param($datatypes, ...$parameters);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            return $result;
        }
        return false;
    } 
}
