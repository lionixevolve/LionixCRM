<?php

class LXCasesAfterRetrieveMethods
{
    public function setElapsedTime(&$bean, $event, $arguments)
    {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'DetailView') {
            $bean->elapsedtime = lxHumanReadableElapsedTime($bean->elapsedtimeinmins_c, $GLOBALS['current_language']);
        }
    }
}
