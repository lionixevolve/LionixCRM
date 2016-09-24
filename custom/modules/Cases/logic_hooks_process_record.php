<?php

class LXCasesProcessRecordMethods
{
    public function setElapsedTime(&$bean, $event, $arguments)
    {
        $bean->elapsedtime = lxHumanReadableElapsedTime($bean->elapsedtimeinmins_c, $GLOBALS['current_language'], 2);
    }
}
