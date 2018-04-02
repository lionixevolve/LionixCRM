<?php //LxDBConnections creates an array of possible database connections for this CRM
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
require_once("custom/lionix/adodb5/adodb.inc.php");

class LxDBConnections
{
    protected $db;            //Array for adodb5 database connections

    public function __construct()
    {
        $this->setDBConnections();
    }//fin __construct()

    final protected function setDBConnections()
    {
        // Retrieve $GLOBALS['sugar_config'] array
        global $sugar_config;
        // Create CRM adodb5 connection using default SugarCRM database configuration
        if (version_compare(phpversion(), '5.6', '<=')) {
            $engine   = $sugar_config['dbconfig']['db_type'];
        } else {
            //PHP 7.0 and onwards
            $engine   = 'mysqli';
        }
        $tempdb   = null;
        $name     = "crm";
        $server   = $sugar_config['dbconfig']['db_host_name'];
        $user     = $sugar_config['dbconfig']['db_user_name'];
        $pass     = $sugar_config['dbconfig']['db_password'];
        $database = $sugar_config['dbconfig']['db_name'];

        $tempdb =& ADONewConnection($engine);
        $tempdb->NConnect($server, $user, $pass, $database);
        $tempdb->setCharset('utf8');
        $tempdb->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->db[$name] = $tempdb;

        $mysql_cstr = "mysql:host={$server};dbname={$database}";
        $cstr = $mysql_cstr;
        $tempdb = new \PDO($cstr, $user, $pass);
        $tempdb->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->db[$name.'pdo'] = $tempdb;
        $query = "SET NAMES utf8";
        $this->execute($query, $name.'pdo', 'pdo');

        // Create aditional adodb5 database connections defined on file config_override.php on SugarCRM root directory
        if (!empty($sugar_config['lx_adodb'])) {
            foreach ($sugar_config['lx_adodb'] as $a) {
                $tempdb   = null;
                $name     = $a['name'];
                $engine   = ($a['engine'] == "oracle") ? "oci8" : $a['engine'];
                $server   = $a['server'];
                $user     = $a['user'];
                $pass     = $a['pass'];
                $database = $a['database'];
                $active   = $a['active'];
                $port     = empty($a['port']) ? "1521" : $a['port'];
                $pdo      = $a['pdo'];

                if ($active) {
                    if ($pdo) {
                        $cstr = "";
                        try {
                            switch ($engine) {
                                case "oracle":
                                case "oci8":
                                    $oracle_cstr = "( DESCRIPTION = ( ADDRESS_LIST = ( ADDRESS = (PROTOCOL = TCP)(HOST = {$server})(PORT = {$port}) ) ) (CONNECT_DATA = (SERVICE_NAME = {$database}) ) )";
                                    $cstr = $oracle_cstr;
                                break;
                                case "postgresql":
                                case "postgres9":
                                    $postgresql_cstr = "pgsql:host={$server};port={$port};dbname={$database}";
                                    $cstr = $postgresql_cstr;
                                break;
                                case "mysql":
                                case "mysqli":
                                    $mysql_cstr = "mysql:host={$server};dbname={$database}";
                                    $cstr = $mysql_cstr;
                                break;
                            }
                            $tempdb = new \PDO($cstr, $user, $pass);
                            $tempdb->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                            $this->db[$name] = $tempdb;
                        } catch (Exception $e) {
                            error_log("Connection problem on custom/lionix/lxdb/dbconn.php line 43");
                            error_log(print_r($a));
                            error_log($e->getMessage().'<pre>'.$e->getTraceAsString().'</pre>');
                        }
                    } else {
                        try {
                            $tempdb =& ADONewConnection($engine);
                            $tempdb->debug = false; //Only turn debug on testing, if leave it on while production ready json encodes fail.
                            switch ($engine) {
                                case "oci8":
                                    $oracle_cstr = "( DESCRIPTION = ( ADDRESS_LIST = ( ADDRESS = (PROTOCOL = TCP)(HOST = {$server})(PORT = {$port}) ) ) (CONNECT_DATA = (SERVICE_NAME = {$database}) ) )";
                                    $tempdb->NConnect($oracle_cstr, $user, $pass);
                                break;
                                default:
                                    $tempdb->NConnect($server, $user, $pass, $database);
                            }
                            $tempdb->setCharset('utf8');
                            $tempdb->setFetchMode(ADODB_FETCH_ASSOC);
                            $this->db[$name] = $tempdb;
                        } catch (Exception $e) {
                            error_log("Connection problem on custom/lionix/lxdb/dbconn.php line 43");
                            error_log(print_r($a));
                            error_log($e->getMessage().'<pre>'.$e->getTraceAsString().'</pre>');
                        }//fin try
                    }//fin pdo
                }//fin active
            }//fin foreach
        }//fin if
    }//fin function

   public function execute($query, $connection_name = 'crm', $connection_type = "adodb", $query_params = array())
   {
       if ($connection_type=='adodb') {
           //Return adodb5 resultset after query execution
           if ($connection_name != 'crm') {
               error_log("Before run query for connection {$connection_name}");
           }
           error_log($query);
           $answer = $this->db[$connection_name]->execute($query);
           if ($connection_name != 'crm') {
               error_log("After run query for connection {$connection_name}");
           }
       }
       if ($connection_type=='pdo') {
           $stmt = $this->db[$connection_name]->prepare($query);
           $stmt->setFetchMode(\PDO::FETCH_ASSOC);
           $stmt->execute($query_params);
           $answer = $stmt;
       }
       return $answer;
   }

    public function newId()
    {
        $query = "select uuid() as 'new_id'";
        $rs = $this->execute($query);
        if (!$rs->EOF) {
            $new_id = $rs->fields['new_id'];
        }//fin if
        return $new_id;
    }

    public function utc($func='none', $expr=0, $unit='minute')
    {
        switch ($func) {
            case "add": $query = "select date_add(utc_timestamp(), interval $expr $unit ) as 'utc'"; break;
            case "sub": $query = "select date_sub(utc_timestamp(), interval $expr $unit ) as 'utc'"; break;
            default: $query = "select utc_timestamp() as 'utc'";
        }
        $rs = $this->execute($query);
        if (!$rs->EOF) {
            $utc = $rs->fields['utc'];
            $utc = date("Y-m-d", strtotime($utc));
        }//fin if
        return $utc;
    }

    public function isConnected($connection_name = 'crm')
    {
        //Return adodb5 resultset after query execution
        if ($connection_name != 'crm') {
            error_log("Before run isConnected for connection {$connection_name}");
        }
        $answer = $this->db[$connection_name]->isConnected();
        if ($connection_name != 'crm') {
            error_log("After run isConnected for connection {$connection_name}");
        }
        return $answer;
    }
}//fin class LxDBConnections
