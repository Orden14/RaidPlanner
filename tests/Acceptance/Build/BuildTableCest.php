<?php

namespace App\Tests\Acceptance\Build;

use App\Entity\Build;
use App\Tests\Support\AcceptanceTester;

final class BuildTableCest
{
    public function testBuildTableIsFullyDisplayed(AcceptanceTester $I): void
    {
        $builds = $I->grabEntitiesFromRepository(Build::class);

        $I->loginAs('member');
        $I->amOnPage('/build/');

        $I->selectOption('select[name="DataTables_Table_0_length"]', 100);
        $I->click('button[data-id="statusFilter"]');
        $I->click('#bs-select-1-1');
        $I->click('#bs-select-1-2');
        $I->click('button[data-id="statusFilter"]');

        foreach ($builds as $build) {
            if ($build->getSpecialization()->getJob()->isDefaultJob() === false) {
                $I->seeElementInDOM("a[href=\"/build/{$build->getId()}\"]");
                $I->seeElementInDOM("img[src=\"/icon/{$build->getSpecialization()->getIcon()}\"]");
            } else {
                $I->dontSeeElementInDOM("a[href=\"/build/{$build->getId()}\"]");
            }
        }

        $I->logout();
    }
}
