<?php

namespace App\Tests\Unit\Entity;

use App\Tests\Support\UnitTester;
use App\Tests\Unit\Entity\Interface\EntityTestInterface;
use Codeception\Test\Unit;

abstract class EntityTest extends Unit implements EntityTestInterface
{
    protected UnitTester $tester;

    final public function testPersistEntity(): void
    {
        $entity = $this->_generateEntity();
        $this->tester->grabEntityManagerInterface()->persist($entity);
        $this->tester->grabEntityManagerInterface()->flush();

        $this->tester->assertNotNull($entity->getId());
        $this->tester->seeInRepository($entity::class, ['id' => $entity->getId()]);
    }

    final public function testRightEntity(): void
    {
        $generatedEntity = $this->_generateEntity();
        $this->_testBasicPropertiesOf($generatedEntity);
        $this->_testRelationPropertiesOf($generatedEntity);
    }

    final public function testFalseEntity(): void
    {
        $entityName = $this->_generateEntity()::class;
        $this->assertCountErrors($this->_expectedCountAssertionErrors(), new $entityName());
    }

    final public function assertCountErrors(int $expected, mixed $entity): void
    {
        $this->tester->assertCount($expected, $this->tester->grabValidator()->validate($entity));
    }
}
