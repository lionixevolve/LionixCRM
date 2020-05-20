<?php

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

require_once 'data/SugarBean.php';
require_once 'include/entryPoint.php';
require_once 'config.php';
require_once 'include/utils.php';
require_once 'include/TimeDate.php';
require_once 'include/SugarLogger/LoggerManager.php';
require_once 'modules/Opportunities/Opportunity.php';
require_once 'modules/Contacts/Contact.php';
require_once 'modules/Accounts/Account.php';
require_once 'modules/Calls/Call.php';
require_once 'modules/Leads/Lead.php';
require_once 'modules/AOS_Contracts/AOS_Contracts.php';
require_once 'modules/AOS_Invoices/AOS_Invoices.php';
require_once 'modules/Prospects/Prospect.php';
require_once 'modules/ProspectLists/ProspectList.php';
require_once 'modules/Cases/Case.php';
require_once 'modules/Meetings/Meeting.php';
require_once 'modules/Tasks/Task.php';
require_once 'modules/Documents/Document.php';
require_once 'include/utils.php';
require_once 'include/formbase.php';

//Library require to instance an ADODB5 Object
require_once 'custom/lionix/adodb5/adodb.inc.php';
//Connection to CRM database using ADODB5 Object
require_once 'custom/lionix/lxdb/dbconn.php';
//Custom specific functions for each client
require_once 'lxajax_custom.php';
class LxAJAX extends LxAJAXCustom
{
    public $debug; //Local instance of Debug LioniX CLASS.
    public $db;   //Local instance of database manager.
    public $data;  //Local copy of $_GET and $_POST global variables.
    public $user_id;

    public function __construct()
    {
        // session_start(); // unneeded, session starts when instanced
        global $sugar_config, $current_user;
        $mock_user = new User();
        $current_user = $mock_user->retrieve("{$_SESSION['authenticated_user_id']}");
        $this->current_user = $mock_user->retrieve("{$_SESSION['authenticated_user_id']}");
        $this->user_id = $_SESSION['authenticated_user_id'];
        $this->debug = $this->data['debug'];
        $this->data = (empty($_GET)) ? $_POST : $_GET;
        if (!isset($this->data['use_adodb5'])) {
            $this->data['use_adodb5'] = false;
        }
        $this->db = ($this->data['use_adodb5']) ? new LxDBConnections() : DBManagerFactory::getInstance();
        $method = $this->data['method'];
        if (method_exists($this, $method)) {
            echo $this->$method();
        } else {
            echo "LxAJAX [404]: Method {$method} doesn't exists";
        }
        if ($this->debug) {
            //lxlog
            file_put_contents($_SERVER["DOCUMENT_ROOT"]."/lx.log", PHP_EOL. date_format(date_create(), "Y-m-d H:i:s ")  .__FILE__ .":". __LINE__." -- ".print_r('LxAJAX instance created', 1).PHP_EOL, FILE_APPEND);
        }
    }//end function LxAJAX

    public function testPOST()
    {
        $msg = 'POST PROBADO';
        if ($this->debug) {
            //lxlog
            file_put_contents($_SERVER["DOCUMENT_ROOT"]."/lx.log", PHP_EOL. date_format(date_create(), "Y-m-d H:i:s ")  .__FILE__ .":". __LINE__." -- ".print_r($msg, 1).PHP_EOL, FILE_APPEND);
        }
        return $msg;
    }

    public function testSecurity()
    {
        session_start();
        if ($this->debug) {
            //lxlog
            file_put_contents($_SERVER["DOCUMENT_ROOT"]."/lx.log", PHP_EOL. date_format(date_create(), "Y-m-d H:i:s ")  .__FILE__ .":". __LINE__." -- ".print_r('testSecurity called', 1).PHP_EOL, FILE_APPEND);
        }
        return session_id();
    }

    public function testQuery()
    {
        $query = "SELECT @@VERSION as mysql_version";
        $tests = array();

        if ($this->data['use_adodb5']) {
            $result = $this->db->execute($query, 'crm');
            if (!$result->EOF) {
                $tests['using_adodb5'] = $result->fields;
            }
            if ($this->data['use_pdo']) {
                $result = $this->db->execute($query, 'crm'.'pdo', 'pdo');
                while ($row = $result->fetch()) {
                    $tests['using_pdo'] = $row;
                }
            }
        } else {
            $result = $this->db->query($query, true, 'FAILD');
            while (($row = $this->db->fetchByAssoc($result))) {
                $tests['using_native_crm_connection'] = $row;
            }
        }

        if ($this->debug) {
            //lxlog
            file_put_contents($_SERVER["DOCUMENT_ROOT"]."/lx.log", PHP_EOL. date_format(date_create(), "Y-m-d H:i:s ")  .__FILE__ .":". __LINE__." -- ".print_r('testQuery called', 1).PHP_EOL, FILE_APPEND);
        }
        return json_encode($tests);
    }

    public function sendQuoteEmail()
    {
        $emails = explode(",", $this->data['toEmails']);
        // error_log('####emails. '.print_r($emails,1));
        $dataMail = array(
               'sender' => $this->data['sender'],
               'from' => $this->data['from'],
               'subject' => $this->data['subject'],
               'body' => $this->data['body'],
               'template' => $this->data['template'],
               'attachmentid' => $this->data['attachmentid']  ? $this->data['attachmentid'] : '',
               'attachmentname' => $this->data['attachmentname']  ? $this->data['attachmentname'] : '',
               'replaceSubject' => $this->data['replaceSubject'],
               'replaceBody' => $this->data['replaceBody'],
               'fromName' => $this->data['fromName'],
               'toEmails' => $emails,
           );

        return lxSendEmail2($dataMail);
    }

    public function checkRole()
    {
        global $timedate,$current_user, $sugar_config;
        if ($GLOBALS['current_user']->is_admin) {
            return 'admin';
        }
        require_once 'modules/ACLRoles/ACLRole.php';
        $role_to_check = $this->data['role'];

        $objACLRole = new ACLRole();
        $roles = $objACLRole->getUserRoles($GLOBALS['current_user']->id);
        if (in_array($role_to_check, $roles)) {
            return 'ok';
        } else {
            return "The user hasn't the role: {$role_to_check}";
        }
    }

    public function getSuiteCRMConfigOption()
    {
        global $sugar_config;
        return json_encode($sugar_config[$this->data['option']]);
        //eg given: $this->data['option'] = 'default_currency_symbol';
        //eg given: $this->data['option'] = 'lionixcrm';
    }

    public function getLionixCRMConfigOption()
    {
        global $sugar_config;
        if ($this->data['option']=='all') {
            return json_encode($sugar_config['lionixcrm']);
        } else {
            return json_encode($sugar_config['lionixcrm'][$this->data['option']]);
        }
        //eg given: $this->data['option'] = 'business_type';
        //eg given: $this->data['option'] = 'environment';
    }

    public function getOpportunityMainContactList()
    {
        $currentValue = null;
        $query = "
        select maincontact_c
        from opportunities_cstm
        where id_c = '{$this->data['opportunityId']}'
        ";
        $rs = $GLOBALS['db']->query($query, false);
        if (($row = $GLOBALS['db']->fetchByAssoc($rs)) != null) {
            if (!empty($row['maincontact_c'])) {
                $currentValue = $row['maincontact_c'];
            }
        }

        $query = "
            select count(1)
            from accounts_contacts ac
            left join contacts c on ac.contact_id = c.id
            where ac.deleted = 0
            and c.deleted = 0
            and ac.account_id = '{$this->data['accountId']}'
        ";
        $qty = $GLOBALS['db']->getOne($query);

        $query = "
            select c.id as 'value', concat(ifnull(first_name,''),' ',ifnull(last_name,'')) as 'name'
            from accounts_contacts ac
            left join contacts c on ac.contact_id = c.id
            where ac.deleted = 0
            and c.deleted = 0
            and ac.account_id = '{$this->data['accountId']}'
            order by c.date_modified desc
        ";
        $rs = $GLOBALS['db']->query($query, false);

        $list = array();

        if ($qty != 1) {
            // only when the account have only one contact created must be always selected
            $list[] = array('' => ''); // uncomment when empty value is needed
        }

        while (($row = $GLOBALS['db']->fetchByAssoc($rs)) != null) {
            if (empty($this->data['currentValue'])) {
                $this->data['currentValue'] = $currentValue;
            }
            if ($row['value'] == $this->data['currentValue']) {
                $row['selected'] = true;
            }
            //$list[] = array('value' => $value, 'name' => $name, 'default' => $default, 'selected' => $selected); //this is the below format example
            $list[] = $row;
        }
        $newSelected = ($this->data['currentValue'] == 'new') ? true : false;
        $list[] = array('value' => 'new', 'name' => 'Nuevo Contacto', 'selected' => $newSelected);

        return json_encode($list);
    }

    public function callbackOrderArrayByName($a, $b)
    {
        //dai - ordenar lista alfabetica por el campo nombre, se llama en la funcion getSuiteCRMList para ordenar alreves cambiar el  orden entre $a $b
        if ($b['name'] == $a['name']) {
            return 0;
        }
        return ($b['name'] > $a['name']) ? -1 : 1;
    }

    public function getSuiteCRMList()
    {
        global $app_list_strings,$sugar_config;
        $default_language = $sugar_config['default_language'];
        $app_list_strings = return_app_list_strings_language($default_language);
        $suitecrm_list = $GLOBALS['app_list_strings'][$this->data['suitecrm_list']];
        $filters_array = explode(',', $this->data['filter_csv']);
        $selected_array = explode(',', $this->data['selected_csv']);
        $list = array();
        if ($this->data['empty_value']) {
            $list[] = array('value' => '', 'name' => '', 'selected' => false); // uncomment when empty value is needed
        }
        $found = false;
        $selected = false;
        foreach ($suitecrm_list as $value => $name) {
            foreach ($filters_array as $f) {
                if (empty($f)) {
                    $found = true;
                } else {
                    $found = strpos(strtolower($value), strtolower($f));
                }
                //this is to avoid non-Boolean evaluated to false
                if ($found !== false) {
                    //$list[] = array('value' => $value, 'name' => $name, 'default' => $default, 'selected' => $selected); //this is the below format example
                    foreach ($selected_array as $s) {
                        if (empty($s)) {
                            $selected = false;
                        } else {
                            $selected = strpos(strtolower($value), strtolower($s));
                        }
                        if ($selected !== false) {
                            $selected = true;
                            break 1;
                        }
                    }
                    $list[] = array('value' => $value, 'name' => $name, 'selected' => $selected);
                }
            }
        }
        usort($list, array($this, "callbackOrderArrayByName")); //this orders $list array alphabetically by name
        return json_encode($list);
    }

    // functions working together getCurrentUserId, lxChatGetSmartChatField and lxChat.
    public function getCurrentUserId()
    {
        return $GLOBALS['current_user']->id;
    }

    public function lxChatGetSmartChatField()
    {
        return json_encode($GLOBALS['sugar_config']['lionixcrm']['smartchat']);
    }

    public function lxChat()
    {
        $messagesArray = array();
        if ($this->data['module'] && $this->data['record_id']) {
            global $moduleList,$beanList,$beanFiles,$app_list_strings;
            $class_name = $beanList[$this->data['module']];
            $module_object = new $class_name();
            $module_object->retrieve($this->data['record_id']);

            if (in_array($this->data['field_name'], $GLOBALS['sugar_config']['lionixcrm']['smartchat'])) {
                $smart_chat_field = $this->data['field_name'];

                if ($this->data['save']) {
                    if (!empty($module_object->id)) {
                        $module_object->$smart_chat_field = $this->data['chat_c'];
                        $module_object->save();
                    }
                }

                $messagesArray = json_decode(html_entity_decode($module_object->$smart_chat_field, ENT_QUOTES));
                foreach ($messagesArray as &$msg) {
                    $query = "
                        SELECT u.id,
                               ifnull(first_name,'') AS 'first_name',
                               ifnull(last_name,'') AS 'last_name',
                               concat(ifnull(first_name,''),' ',ifnull(last_name,'')) AS 'full_name',
                               if('{$GLOBALS['current_user']->id}'=u.id,1,0) as 'current_user'
                        FROM users u
                        WHERE u.id = '{$msg->id}'
                    ";
                    $rs = $GLOBALS['db']->query($query, false);
                    if (($row = $GLOBALS['db']->fetchByAssoc($rs)) != null) {
                        $msg->firstName = $row['first_name'];
                        $msg->lastName = $row['last_name'];
                        $msg->fullName = $row['full_name'];
                        $msg->currentUser = ($row['current_user']) ? true : false;
                    }
                }
            }
        }
        return json_encode($messagesArray);
    }

    // functions working together uploadFileTemplate, lxUploadAnyDocumentFormat, uploadExcelFileOrGivenFormat and createSuiteCRMNote.
    public function uploadFileTemplate()
    {
        $url = 'lxajax.php?method=lxUploadAnyDocumentFormat';
        $url .='&module_name='.urlencode($this->data['module_name']);
        $url .='&record_id='.urlencode($this->data['record_id']);
        $url .='&ok_message='.urlencode($this->data['ok_message']);

        return '
            <!-- bof -->
            <div id="'.$this->data['field_name'].'_loader" style="position: absolute; margin: 5px">
                <span id="'.$this->data['field_name'].'-title"><center style="color: black; font-size: 20px;">Seleccione "'.$this->data['label'].'" a subir</center></span><br/>
                <form id="'.$this->data['field_name'].'-form" method="post" enctype="multipart/form-data" action="'.$url.'">
                    <span id="span-'.$this->data['field_name'].'-file">
                        <input type="file" id="excel-file" name="excel-file" />
                    </span>
                </form>
                <br/>
                <!-- div preview is handled afterwards by jQuery -->
                <div id="preview_'.$this->data['field_name'].'" align="center" style="color: navy; font-size: 17px;" />
                <br/>
                <center>
                    <button type="button" class="btn btn-primary btn-sm" id="upload-'.$this->data['field_name'].'-btn">Subir</button>
                    <button type="button" class="btn btn-primary btn-sm" id="exit-'.$this->data['field_name'].'-btn">Salir</button>
                </center>
            </div>
            <!-- eof -->
        ';
    }

    public function lxUploadAnyDocumentFormat()
    {
        $data = array();
        $ok = false;
        $this->data['regular_expression'] = '/.+/si'; //anything regexp
        $this->data['valid_formats'] = array('xls', 'XLS', 'xlsx', 'XLSX', 'doc', 'docx', 'pdf', 'jpg', 'jpeg', 'png', 'cmg', 'svg', 'dwg'); //add the formats you want to upload - cmg is for images
        $uploaded = $this->uploadExcelFileOrGivenFormat();
        $message = $uploaded['message'];
        if ($uploaded['ok']) {
            if ($this->createSuiteCRMNote($this->data, $this->data['module_name'], $this->data['record_id'])) {
                $ok = true;
                if (empty($this->data['ok_message'])) {
                    $message = "Archivo agregado correctamente.";
                } else {
                    $message = $this->data['ok_message'];
                }
            }
        }
        $data['message'] = $message;
        $data['ok'] = $ok;
        $data['document_name'] = $this->data['document_name'];
        $data['document_id'] = $this->data['note_id'];
        $data['document_file'] = $this->data['note_file'];

        return json_encode($data);
    }

    public function uploadExcelFileOrGivenFormat()
    {
        $data = array();
        $ok = false;
        $message = '404: GET not allowed to upload files';
        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == 'POST') {
            $path = 'upload/'; //set your folder path
            $filename = $_FILES['excel-file']['tmp_name']; //get the temporary uploaded file name
            $valid_formats = empty($this->data['valid_formats']) ? array('xls', 'XLS', 'xlsx', 'XLSX', 'xlt', 'XLT', 'xltx', 'XLTX') : $this->data['valid_formats'];
            $name = $_FILES['excel-file']['name']; //get the name of the file
            $type = $_FILES['excel-file']['type']; //get the mime_type of the file
            $size = $_FILES['excel-file']['size']; //get the size of the file
            $tmp = $_FILES['excel-file']['tmp_name']; //get the temporal name of the file

            $this->data['original_filename'] = $name;
            $this->data['original_file_mime_type'] = $type;

            //check if the file is selected or cancelled after pressing the browse button.
            if (strlen($name)) {
                //extract the name and extension of the file
                list($txt, $ext) = explode('.', $name);
                $this->data['original_file_ext'] = $ext;
                //if the file is valid go on.
                if (in_array($ext, $valid_formats)) {
                    // check if the file size is more than 2 Gb
                    if ($size < 2000000000) {
                        // This regular expression is assign the final name on the file system
                        $re = $this->data['regular_expression'];
                        preg_match($re, $name, $matches);
                        if (!empty($matches)) {
                            $this->data['document_name'] = $matches[0];
                            //SUBSTR to 36 chars -  We need to use the filename as the NOTE id
                            $actual_file_name = uniqid();
                            //check if it the file move successfully.
                            if (move_uploaded_file($tmp, $path.$actual_file_name)) {
                                $this->data['current_filename'] = $actual_file_name;
                                $ok = true;
                                $message = 'Archivo subido correctamente.';
                            } else {
                                $message = 'Hubo un error al cargar el archivo por favor intente de nuevo.';
                            }
                        } else {
                            $message = 'El archivo no contiene un nombre válido.';
                        }
                    } else {
                        $size_on_gigabytes = $size / pow(1024, 3);
                        $message = "El tamaño máximo para el archivo es 2 GB, este archivo mide {$size_on_gigabytes} GB.";
                    }
                } else {
                    $message = 'Formato de archivo incorrecto.';
                }
            } else {
                $message = 'Por favor seleccione el archivo a cargar.';
            }
        }
        $data['message'] = $message;
        $data['ok'] = $ok;

        return $data;
    }

    public function createSuiteCRMNote($data, $parentType, $parentId)
    {
        // Try to upload file
        try {
            // Success!
            $note = new Note();
            $note->id = $data['current_filename'];
            $note->new_with_id = true;
            $note->name = $data['document_name'];
            $note->filename = $data['document_name'];
            $note->file_mime_type = $data['mime'];
            $note->assigned_user_id = $user_id;
            $note->parent_type = $parentType;
            $note->parent_id = $parentId;
            if (strtolower($parentType) == 'contacts') {
                // The following lines are to avoid incorrect "related to" links to contacts since Notes has a one-to-many relationship
                // This if is to avoid overwrite an existing linked contact
                if (empty($note->contact_id)) {
                    $note->contact_id = $parentId;
                }
                // This is the default behaviour when linking a contact
                $note->parent_type = 'Accounts';
                $note->parent_id = '';
            }
            $note->save();
            // $contents = file_get_contents("upload://{$data['name']}");
            $this->data['note_id'] = $note->id;
            $this->data['note_file'] = "index.php?entryPoint=download&type=Notes&id={$note->id}";
            return true;
        } catch (\Exception $e) {
            // Fail!
            $errors = "Cannot create Note";
            return $errors;
        }
    }

    public function getContactDuplicates()
    {
        $list = array();
        if (!empty($this->data['first_name']) || !empty($this->data['last_name']) || !empty($this->data['lastname2_c']) || !empty($this->data['cedula_c']) || !empty($this->data['email_address'])) {
            $query = "
                SELECT distinct c.id, ifnull(cc.cedula_c,'') as 'cedula_c', ifnull(c.first_name,'') as 'first_name', ifnull(c.last_name,'') as 'last_name', ifnull(cc.lastname2_c,'') as 'lastname2_c', ifnull(c.phone_work,'') as 'phone_work', ifnull(c.phone_mobile,'') AS 'phone_mobile', ifnull(c.title,'') as 'title'
                FROM contacts c
                LEFT JOIN contacts_cstm cc ON c.id = cc.id_c
                LEFT JOIN email_addr_bean_rel er ON er.bean_id = c.id AND er.bean_module = 'Contacts' AND er.deleted = 0
                LEFT JOIN email_addresses e ON er.email_address_id = e.id AND e.deleted = 0
                LEFT JOIN accounts_contacts acco ON c.id = acco.contact_id
                LEFT JOIN accounts a ON acco.account_id = a.id
                WHERE c.deleted = 0
                AND acco.deleted = 0
                AND a.deleted = 0
            ";
            if (!empty($this->data['cedula_c'])) {
                $query .= " AND cc.cedula_c LIKE '%{$this->data['cedula_c']}%' ";
            }
            if (!empty($this->data['first_name'])) {
                $query .= " AND c.first_name LIKE '%{$this->data['first_name']}%' ";
            }
            if (!empty($this->data['last_name'])) {
                $query .= " AND c.last_name LIKE '%{$this->data['last_name']}%' ";
            }
            if (!empty($this->data['lastname2_c'])) {
                $query .= " AND cc.lastname2_c LIKE '%{$this->data['lastname2_c']}%' ";
            }
            if (!empty($this->data['email_address'])) {
                $query .= " AND e.email_address LIKE '%{$this->data['email_address']}%' ";
            }
            $query .= " ORDER BY c.first_name ,c.last_name, cc.lastname2_c";
            $rs = $GLOBALS['db']->query($query, false);
            while (($row = $GLOBALS['db']->fetchByAssoc($rs)) != null) {
                $row['emails'] = array();
                $query = "
                    SELECT e.id, e.email_address, er.primary_address
                    FROM contacts c
                    LEFT JOIN email_addr_bean_rel er ON er.bean_module = 'Contacts' AND er.bean_id = c.id
                    LEFT JOIN email_addresses e ON er.email_address_id = e.id
                    WHERE c.deleted = 0
                    AND er.deleted = 0
                    AND e.deleted = 0
                    AND c.id = '{$row['id']}'
                    ORDER BY er.primary_address desc, e.date_created desc
                ";
                $rse = $GLOBALS['db']->query($query, false);
                while (($rowe = $GLOBALS['db']->fetchByAssoc($rse)) != null) {
                    $row['emails'][] = $rowe;
                }
                $row['accounts'] = array();
                $query = "
                    SELECT a.id, a.name, ifnull(ac.tipocedula_c,'') AS 'tipocedula_c'
                    FROM contacts c
                    LEFT JOIN accounts_contacts acco ON c.id = acco.contact_id
                    LEFT JOIN accounts a ON acco.account_id = a.id
                    LEFT JOIN accounts_cstm ac ON a.id = ac.id_c
                    WHERE c.deleted = 0
                    AND acco.deleted = 0
                    AND a.deleted = 0
                    AND c.id = '{$row['id']}'
                    ORDER BY ac.tipocedula_c
                ";
                $rsa = $GLOBALS['db']->query($query, false);
                while (($rowa = $GLOBALS['db']->fetchByAssoc($rsa)) != null) {
                    $row['accounts'][] = $rowa;
                }
                //$list[] = array('value' => $value, 'name' => $name, 'default' => $default, 'selected' => $selected); //this is the below format example
                $list[] = $row;
            }
        }
        return json_encode($list);
    }

    public function setSuiteCRMList()
    {
        lxSetSuiteCRMList($this->data['suitecrm_list'], $this->data['list_value'], $this->data['list_lang']);
        return $this->getSuiteCRMList();
    }

    public function getTSEData()
    {
        // IMPORTANT NOTE: if this function isn't returning data is because you haven't granted select permission for the crm user on config.php to infoticos database
        // grant select on infoticos.* to {user}@{server} identified by '{password}';
        $list = array();
        if (!empty($this->data['cedula_c'])) {
            $query = "
                SELECT cedula as cedula_c, nombre as first_name, `1.apellido` as last_name, `2.apellido` as lastname2_c, true as found
                FROM infoticos.padron
                WHERE cedula = '{$this->data['cedula_c']}'
            ";
            $rs = $GLOBALS['db']->query($query, false);
            while (($row = $GLOBALS['db']->fetchByAssoc($rs)) != null) {
                //$list[] = array('value' => $value, 'name' => $name, 'default' => $default, 'selected' => $selected); //this is the below format example
                $list[] = $row;
            }
        }
        return json_encode($list);
    }

    public function runSpoonFile()
    {
        global $db,$sugar_config;
        $answer = "Archivo no existe";
        $log_file_path = $sugar_config['lionixcrm']['log_file_path'];
        $spoon_path = $sugar_config['lionixcrm']['spoon_path'];
        $crm_path = $sugar_config['lionixcrm']['crm_path'];
        $file = $this->data['file'];
        if (file_exists($crm_path.$file)) {
            $answer = $this->data['answer'];
            $command = "cd ".$spoon_path." ; ./kitchen.sh -file='".$crm_path.$file."' --level=Detailed 2>&1 >> ".$log_file_path;
            file_put_contents($_SERVER["DOCUMENT_ROOT"]."/lx.log", PHP_EOL. date_format(date_create(), "Y-m-d H:i:s ")  .__FILE__ .":". __LINE__." -- ".print_r($command, 1).PHP_EOL, FILE_APPEND);
            exec($command);
        }
        return json_encode($answer);
    }
}//end class LxAJAX

// Session variables passed to this page
session_start();
if (isset($_SESSION['authenticated_user_id'])) {
    if (isset($_GET['sudo'])) {
        if (($_SESSION['authenticated_user_id'] == "1" || $_SESSION['sudo'] == "1") && !empty($_GET['sudo'])) {
            $_SESSION['authenticated_user_id'] = $_GET['sudo'];
            $_SESSION['sudo'] = "1";
        }
    }
    $lxajax = new LxAJAX();
} else {
    $problem = 'Problem creating an LxAJAX instance.';
    error_log($problem);
}
