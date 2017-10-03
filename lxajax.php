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

class LxAJAX
{
    public $debug; //Local instance of Debug LioniX CLASS.
    public $db;   //Local instance of database manager.
    public $data;  //Local copy of $_GET and $_POST global variables.
    public $user_id;

    public function LxAJAX()
    {
        // session_start(); // unneeded, session starts when instanced
        global $sugar_config, $current_user;
        $mock_user = new User();
        $current_user = $mock_user->retrieve("{$_SESSION['authenticated_user_id']}");
        $this->current_user = $mock_user->retrieve("{$_SESSION['authenticated_user_id']}");
        $this->user_id = $_SESSION['authenticated_user_id'];
        $this->debug = $this->data['debug'];
        $this->data = (empty($_GET)) ? $_POST : $_GET;
        $this->db = ($this->data['use_adodb5']) ?  new LxDBConnections() : DBManagerFactory::getInstance();
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
        $query = 'SELECT @@VERSION;';
        $result = $this->db->query($query, true, 'FAILD');
        $r = null;
        while (($row = $this->db->fetchByAssoc($result))) {
            $r = $row;
        }
        if ($this->debug) {
            //lxlog
            file_put_contents($_SERVER["DOCUMENT_ROOT"]."/lx.log", PHP_EOL. date_format(date_create(), "Y-m-d H:i:s ")  .__FILE__ .":". __LINE__." -- ".print_r('testQuery called', 1).PHP_EOL, FILE_APPEND);
        }
        return json_encode($r);
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
        $suitecrm_list = $GLOBALS['app_list_strings'][$this->data['suitecrm_list']];
        $filters_array = explode(',', $this->data['filter_csv']);
        $selected_array = explode(',', $this->data['selected_csv']);
        $list = array();
        if ($this->data['empty_value']) {
            $list[] = array('' => ''); // uncomment when empty value is needed
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
        if ($this->data['module']) {
            global $moduleList,$beanList,$beanFiles,$app_list_strings;
            $class_name = $beanList[$this->data['module']];
            $module_object = new $class_name();
            $module_object->retrieve($this->data['record_id']);
            $smart_chat_field = $GLOBALS['sugar_config']['lionixcrm']['smartchat'][$this->data['array_position']];

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
        return json_encode($messagesArray);
    }

    // functions working together uploadFileTemplate, lxUploadAnyDocumentFormat, uploadExcelFileOrGivenFormat and createSuiteCRMNote.
    public function uploadFileTemplate()
    {
        $url = 'lxajax.php?method=lxUploadAnyDocumentFormat&ok_message='.urlencode($this->data['ok_message']);
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
            if ($this->createSuiteCRMNote($this->data, "Opportunities", $this->data['opportunity_id'])) {
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
                $note->contact_id = $parentId;
            }
            $note->save();
            // $contents = file_get_contents("upload://{$data['name']}");
            $this->data['note_id'] = $note->id;
            $this->data['note_file'] = "index.php?entryPoint=download&type=Notes&id={$note->id}";
            return true;
        } catch (\Exception $e) {
            // Fail!
            $errors = "Cannot create Document";
            return $errors;
        }
    }
}//end class LxAJAX

// Session variables passed to this page
session_start();
if (isset($_SESSION['authenticated_user_id'])) {
    if ($_SESSION['authenticated_user_id']=="1" && !empty($_GET['sudo']) || $_SESSION['sudo']=="1" && !empty($_GET['sudo'])) {
        $_SESSION['authenticated_user_id']=$_GET['sudo'];
        $_SESSION['sudo']="1";
    }
    $lxajax = new LxAJAX();
} else {
    $problem = 'Problem creating an LxAJAX instance.';
    error_log($problem);
}
