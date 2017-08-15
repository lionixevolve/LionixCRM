<?php //LxDBConnections creates an array of possible database connections for this CRM
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once("custom/lionix/adodb5/adodb.inc.php");

class LxDBConnections {
   protected $db;            //Array for adodb5 database connections

   public function __construct() {
      $this->setDBConnections();
    }//fin __construct()

   protected final function setDBConnections() {
   // Retrieve $GLOBALS['sugar_config'] array
      global $sugar_config;
   // Create CRM adodb5 connection using default SugarCRM database configuration
      $tempdb   = null;
      $name     = "crm";
      $engine   = $sugar_config['dbconfig']['db_type'];
      $server   = $sugar_config['dbconfig']['db_host_name'];
      $user     = $sugar_config['dbconfig']['db_user_name'];
      $pass     = $sugar_config['dbconfig']['db_password'];
      $database = $sugar_config['dbconfig']['db_name'];

      $tempdb =& ADONewConnection($engine);
      $tempdb->NConnect($server, $user, $pass, $database);
      $tempdb->SetFetchMode(ADODB_FETCH_ASSOC);

      $this->db[$name] = $tempdb;

   // Create aditional adodb5 database connections defined on file config_override.php on SugarCRM root directory
      if(!empty($sugar_config['lx_adodb'])){
         foreach($sugar_config['lx_adodb'] as $a){
            $tempdb   = null;
            $name     = $a['name'];
            $engine   = ($a['engine'] == "oracle") ? "oci8" : $a['engine'];
            $server   = $a['server'];
            $user     = $a['user'];
            $pass     = $a['pass'];
            $database = $a['database'];
            $active   = $a['active'];
            $port     = empty($a['port']) ? "1521" : $a['port'];

            if($active){

                try{
                    $tempdb =& ADONewConnection($engine);
                    switch($engine){
                        case "oci8":
                            $oracle_cstr = "( DESCRIPTION = ( ADDRESS_LIST = ( ADDRESS = (PROTOCOL = TCP)(HOST = {$server})(PORT = {$port}) ) ) (CONNECT_DATA = (SERVICE_NAME = {$database}) ) )";
                            $tempdb->NConnect($oracle_cstr, $user, $pass);
                        break;
                        default:
                            $tempdb->NConnect($server, $user, $pass, $database);
                            $tempdb->SetFetchMode(ADODB_FETCH_ASSOC);
                    }
                    $this->db[$name] = $tempdb;
                }catch(Exception $e){
                    error_log("Connection problem on custom/lionix/lxdb/dbconn.php line 43");
                    error_log(print_r($a));
                    error_log($e->getMessage().'<pre>'.$e->getTraceAsString().'</pre>');
                }//fin try

            }//fin active

         }//fin foreach
      }//fin if
   }//fin function

   public function execute($query,$connection_name = 'crm') {
      //Return adodb5 resultset after query execution
      if($connection_name != 'crm'){ error_log("Before run query for connection {$connection_name}"); }
      return $this->db[$connection_name]->execute($query);
      if($connection_name != 'crm'){ error_log("After run query for connection {$connection_name}"); }
   }

   public function newId(){
      $query = "select uuid() as 'new_id'";
      $rs = $this->execute($query);
      if(!$rs->EOF){
         $new_id = $rs->fields['new_id'];
      }//fin if
      return $new_id;
   }

    public function utc($func='none',$expr=0,$unit='minute'){
        switch($func){
            case "add": $query = "select date_add(utc_timestamp(), interval $expr $unit ) as 'utc'"; break;
            case "sub": $query = "select date_sub(utc_timestamp(), interval $expr $unit ) as 'utc'"; break;
            default   : $query = "select utc_timestamp() as 'utc'";
        }
        $rs = $this->execute($query);
        if(!$rs->EOF){
            $utc = $rs->fields['utc'];
            $utc = date("Y-m-d", strtotime($utc));
        }//fin if
        return $utc;
   }

}//fin class LxDBConnections