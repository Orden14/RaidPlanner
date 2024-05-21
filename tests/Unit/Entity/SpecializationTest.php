<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Build;
use App\Entity\Job;
use App\Entity\Specialization;
use Override;

final class SpecializationTest extends EntityTest
{
    private Job $job;
    private Build $build;


    public function _before(): void
    {
        $this->job = $this->tester->grabEntityFromRepository(Job::class, ['id' => 1]);
        $this->build = $this->tester->grabEntityFromRepository(Build::class, ['id' => 1]);
    }

    /**
     * @return Specialization
     */
    #[Override]
    public function _generateEntity(): object
    {
        return (new Specialization())
            ->setName('testSpecialization')
            ->setIcon('testIcon')
            ->setJob($this->job)
            ->addBuild($this->build);
    }

    /**
     * @param Specialization $generatedEntity
     */
    #[Override]
    public function _testBasicPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertSame('testSpecialization', $generatedEntity->getName());
        $this->tester->assertSame('testIcon', $generatedEntity->getIcon());
    }

    #[Override]
    public function _testRelationalPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertSame($this->job, $generatedEntity->getJob());
        $this->tester->assertContains($this->build, $generatedEntity->getBuilds());
    }

    #[Override]
    public function _expectedCountAssertionErrors(): int
    {
        return 2;
    }
}
