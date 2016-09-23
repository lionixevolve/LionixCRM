<?php
Class LXCasesAfterRetrieveMethods{

//$hook_array['after_retrieve'][] = Array(1, 'setElapsedTime', 'custom/modules/Cases/logic_hooks_after_retrieve.php','LXCasesAfterRetrieveMethods', 'setElapsedTime');
   function setElapsedTime(&$bean, $event, $arguments){

      if ($_REQUEST['action'] == "DetailView"){
         $bean->elapsedtime = $bean->elapsedtimeinsecs_c." en segundos";
      }

   }

}
