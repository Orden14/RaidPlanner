<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Job;
use App\Entity\Specialization;
use Override;

class JobTest extends EntityTest
{
    private Specialization $specialization;


    final public function _before(): void
    {
        $this->specialization = $this->tester->grabEntityFromRepository(Specialization::class, ['id' => 1]);
    }

    /**
     * @return Job
     */
    #[Override]
    final public function _generateEntity(): object
    {
        return (new Job())
            ->setName('testJob')
            ->setColor('testColor')
            ->setIcon('testIcon')
            ->addSpecialization($this->specialization)
            ->setDefaultJob(true);
    }

    /**
     * @param Job $generatedEntity
     */
    #[Override]
    final public function _testBasicPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertEquals('testJob', $generatedEntity->getName());
        $this->tester->assertEquals('testColor', $generatedEntity->getColor());
        $this->tester->assertEquals('testIcon', $generatedEntity->getIcon());
        $this->tester->assertTrue($generatedEntity->isDefaultJob());
    }

    #[Override]
    final public function _testRelationPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertContains($this->specialization, $generatedEntity->getSpecializations());
    }

    #[Override]
    final public function _expectedCountAssertionErrors(): int
    {
        return 2;
    }
}
