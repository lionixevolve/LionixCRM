<?php

namespace Step\Acceptance;

class Calls extends \AcceptanceTester
{
    /**
     * Navigate to calls module
     */
    public function gotoCalls()
    {
        $I = new NavigationBar($this->getScenario());
        $I->clickAllMenuItem('Calls');
    }

    /**
     * Create a call
     *
     * @param $name
     */
    public function createCall($name)
    {
        $I = new EditView($this->getScenario());
        $DetailView = new DetailView($this->getScenario());
        $Sidebar = new SideBar($this->getScenario());
        $faker = $this->getFaker();

        $I->see('Log Call', '.actionmenulink');
        $Sidebar->clickSideBarAction('Log');
        $I->waitForEditViewVisible();
        $I->fillField('#name', $name);
        $I->fillField('#date_start_date', '01/19/2038');
        $I->fillField('#description', $faker->text());

        $I->waitForElementVisible('#date_start_hours', 120);

        $I->clickSaveButton();
        $DetailView->waitForDetailViewVisible();
    }
}