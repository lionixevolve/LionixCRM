<?php
Class LXCasesAfterRetrieveMethods{

   function setElapsedTime(&$bean, $event, $arguments){

      if ($_REQUEST['action'] == "DetailView"){
         $bean->elapsedtime = $bean->elapsedtimeinsecs_c." en segundos";
      }

   }

}
