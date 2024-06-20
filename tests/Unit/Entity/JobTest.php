<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Job;
use App\Entity\Specialization;
use Override;

final class JobTest extends EntityTest
{
    private Specialization $specialization;

    public function _before(): void
    {
        $this->specialization = $this->tester->grabEntityFromRepository(Specialization::class, ['id' => 1]);
    }

    /**
     * @return Job
     */
    #[Override]
    public function _generateEntity(): object
    {
        return (new Job())
            ->setName('testJob')
            ->setColor('testColor')
            ->setIcon('testIcon')
            ->addSpecialization($this->specialization)
            ->setDefaultJob(true)
        ;
    }

    /**
     * @param Job $generatedEntity
     */
    #[Override]
    public function _testBasicPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertSame('testJob', $generatedEntity->getName());
        $this->tester->assertSame('testColor', $generatedEntity->getColor());
        $this->tester->assertSame('testIcon', $generatedEntity->getIcon());
        $this->tester->assertTrue($generatedEntity->isDefaultJob());
    }

    #[Override]
    public function _testRelationalPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertContains($this->specialization, $generatedEntity->getSpecializations());
    }

    #[Override]
    public function _expectedAssertionsErrorCount(): int
    {
        return 2;
    }
}
