<?php

$configJson = json_decode(file_get_contents(__DIR__ . "/../config.json"), true);
$GLOBALS["db_prefix"] = $configJson['db_prefix'];
$GLOBALS["dbh"] = new SqlHandler($configJson["host"], $configJson["username"], $configJson["password"], $configJson['db_name'], $configJson['port']);
class SqlHandler
{
    public $db;

    function __construct($host, $username, $password, $dbname, $port = null)
    {
        try {
            $this->db = new PDO("mysql:host=$host;port=$port", $username, $password);
            if(count($this->sendQuery("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'")) == 0){
                $this->init($dbname);
            }
            $this->sendQuery("USE ".$dbname);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    function init($dbname)
    {
        $tmp = str_replace('basededonnee', $dbname ,file_get_contents(__DIR__ . "/../init.sql") );
        $this->sendQuery($tmp);
    }

    function sendQuery($query, $args = [])
    {
        $req = $this->db->prepare($query);
        $req->execute($args);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

