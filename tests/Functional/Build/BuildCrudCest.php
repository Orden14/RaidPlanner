<?php

namespace App\Tests\Functional\Build;

use App\Entity\Build;
use App\Entity\BuildCategory;
use App\Entity\Specialization;
use App\Enum\BuildStatusEnum;
use App\Tests\Support\FunctionalTester;

final class BuildCrudCest
{
    private Build $build;

    public function _before(FunctionalTester $I): void
    {
        $I->loginAs('admin');
    }

    public function _after(FunctionalTester $I): void
    {
        $I->logout();
    }

    public function testBuildCrud(FunctionalTester $I): void
    {
        $this->_testCreation($I);
        $this->_testEdition($I);
        $this->_testDeletion($I);
    }

    private function _testCreation(FunctionalTester $I): void
    {
        $I->amOnRoute('build_index');
        $I->seeResponseCodeIsSuccessful();

        $specialization = $I->grabEntityFromRepository(Specialization::class, ['name' => 'Renegade']);
        $categories = [];
        $categories[] = $I->grabEntityFromRepository(BuildCategory::class, ['id' => 1]);
        $categories[] = $I->grabEntityFromRepository(BuildCategory::class, ['id' => 2]);

        $csrfToken = $I->grabValueFrom('input[name="build[_token]"]');
        $I->submitSymfonyForm('build', [
            '[name]' => 'BuildCestCreate',
            '[status]' => 1,
            '[specialization]' => $specialization->getId(),
            '[categories]' => array_map(static fn (BuildCategory $category) => $category->getId(), $categories),
            '[benchmark]' => '444444',
            '[link]' => 'BuildCestCreateLink',
            '[_token]' => $csrfToken,
        ]);

        $I->seeResponseCodeIsSuccessful();

        $createdBuild = $I->grabEntityFromRepository(Build::class, ['name' => 'BuildCestCreate']);
        $I->assertEquals('BuildCestCreate', $createdBuild->getName());
        $I->assertEquals(BuildStatusEnum::NOT_META, $createdBuild->getStatus());
        $I->assertEquals($specialization, $createdBuild->getSpecialization());
        $I->assertEquals($categories, $createdBuild->getCategories()->toArray());
        $I->assertEquals('444444', $createdBuild->getBenchmark());
        $I->assertEquals('BuildCestCreateLink', $createdBuild->getLink());
        $I->assertNull($createdBuild->getBenchmarkLink());
        $I->assertNull($createdBuild->getVideoLink());

        $this->build = $createdBuild;
    }

    private function _testEdition(FunctionalTester $I): void
    {
        $I->amOnRoute('build_edit', ['id' => $this->build->getId()]);
        $I->seeResponseCodeIsSuccessful();

        $specialization = $I->grabEntityFromRepository(Specialization::class, ['name' => 'Renegade']);
        $categories = [];
        $categories[] = $I->grabEntityFromRepository(BuildCategory::class, ['id' => 3]);
        $categories[] = $I->grabEntityFromRepository(BuildCategory::class, ['id' => 4]);

        $csrfToken = $I->grabValueFrom('input[name="build[_token]"]');
        $I->submitSymfonyForm('build', [
            '[name]' => 'BuildCestEdit',
            '[status]' => 0,
            '[categories]' => array_map(static fn (BuildCategory $category) => $category->getId(), $categories),
            '[benchmark]' => '555555',
            '[link]' => 'BuildCestEditLink',
            '[videoLink]' => 'BuildCestEditVideoLink',
            '[benchmarkLink]' => 'BuildCestEditBenchmarkLink',
            '[_token]' => $csrfToken,
        ]);

        $I->seeResponseCodeIsSuccessful();

        $editedBuild = $I->grabEntityFromRepository(Build::class, ['name' => 'BuildCestEdit']);
        $I->assertEquals('BuildCestEdit', $editedBuild->getName());
        $I->assertEquals(BuildStatusEnum::META, $editedBuild->getStatus());
        $I->assertEquals($specialization, $editedBuild->getSpecialization());
        $I->assertEquals($categories, $editedBuild->getCategories()->toArray());
        $I->assertEquals('555555', $editedBuild->getBenchmark());
        $I->assertEquals('BuildCestEditLink', $editedBuild->getLink());
        $I->assertEquals('BuildCestEditVideoLink', $editedBuild->getVideoLink());
        $I->assertEquals('BuildCestEditBenchmarkLink', $editedBuild->getBenchmarkLink());
    }

    private function _testDeletion(FunctionalTester $I): void
    {
        $I->amOnRoute('build_edit', ['id' => $this->build->getId()]);
        $I->seeResponseCodeIsSuccessful();

        $csrfToken = $I->grabValueFrom('input[name="_token"]');
        $I->submitSymfonyForm("delete", ['_token' => $csrfToken]);
        $I->seeResponseCodeIsSuccessful();

        $I->dontSeeInRepository(Build::class, ['id' => $this->build->getId()]);
    }
}
