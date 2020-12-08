<?php

$configJson = json_decode(file_get_contents(__DIR__ . "/../config.json"), true);
$GLOBALS["db_prefix"] = $configJson['db_prefix'];
$GLOBALS["dbh"] = new SqlHandler($configJson["host"], $configJson["username"], $configJson["password"], $configJson['db_name'], $configJson['port']);

class SqlHandler
{
    public $db;

    function __construct($host, $username, $password, $dbname, $port = null)
    {
        $this->prefix = $prefix;
        try {
            $this->db = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $username, $password);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    function init()
    {
        // Initialise la BDD
    }

    function sendQuerry($query)
    {
        $req = $this->db->prepare($query);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}


?>

