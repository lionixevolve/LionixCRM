<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will
// be automatically rebuilt in the future.
 $hook_version = 1;
$hook_array = Array();
// position, file, function
$hook_array['after_login'] = Array();
$hook_array['after_login'][] = Array(1, 'SugarFeed old feed entry remover', 'modules/SugarFeed/SugarFeedFlush.php','SugarFeedFlush', 'flushStaleEntries');
$hook_array['after_login'][] = Array(2, 'setNewTrackerRecord login', 'custom/modules/Users/logic_hooks_after_login.php','LXUsersAfterLogInMethods', 'setNewTrackerRecord');

$hook_array['before_logout'] = Array();
$hook_array['before_logout'][] = Array(1, 'setNewTrackerRecord logout', 'custom/modules/Users/logic_hooks_before_logout.php','LXUsersBeforeLogOutMethods', 'setNewTrackerRecord');



?>
