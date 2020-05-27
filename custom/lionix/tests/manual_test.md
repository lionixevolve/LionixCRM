- Recommended: To make easy the testing copy this lines as required on config_override.php
$sugar_config['lionixcrm']['environment'] = 'testing'; // This show a blue banner with "Testing Environment" on top in every view
$sugar_config['lionixcrm']['business_type'] = 'b2c'; // This makes account not required on contact and opportunity creation
$sugar_config['lionixcrm']['allow_smartchat'] = false; // This disable lx_chat
- Change environment to testing and check blue Testing notice appears on top
- Verify global search hides
- Create a new opportunity, with a new account and a new contact and Upload a file in a new opportunity (no record_id) and verify it exists on history panel in one step
- Send an email by clicking on email address on contact detailview
- Send an email selecting multiple contacts with bulk action button on contacts listview
- Verify that lx.chat is working
- Change business_type to b2c and check that account name is no longer required
- Add a new panel on detailview on any module (opportunities is a good choice) and check it can be viewed as tab
- Check for contacts duplicates after create a contact with a valid TSECR cédula and a linked account as well (Opportunities module)
- Check for contacts duplicates after create a contact with a valid TSECR cédula and a linked account as well (Contacts module)
- Check inline editing is working on any module (opportunities is a good choice)
- Optional: Make a call to testQuery method on lxajax with postman
- Optional: Export on any module (opportunities is a good choice) and check it out on excel (commas were replaced with semicolons on installation to revert it go to Admin->Locale)
