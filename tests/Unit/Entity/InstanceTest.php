<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Encounter;
use App\Entity\Instance;
use App\Enum\InstanceTypeEnum;
use Override;

final class InstanceTest extends EntityTest
{
    private Encounter $encounter;

    public function _before(): void
    {
        $this->encounter = $this->tester->grabEntityFromRepository(Encounter::class, ['id' => 1]);
    }

    /**
     * @return Instance
     */
    #[Override]
    public function _generateEntity(): object
    {
        return (new Instance())
            ->setName('testInstance')
            ->setTag('testTag')
            ->setType(InstanceTypeEnum::STRIKE)
            ->addEncounter($this->encounter)
        ;
    }

    /**
     * @param Instance $generatedEntity
     */
    #[Override]
    public function _testBasicPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertSame('testInstance', $generatedEntity->getName());
        $this->tester->assertSame('testTag', $generatedEntity->getTag());
        $this->tester->assertSame(InstanceTypeEnum::STRIKE, $generatedEntity->getType());
    }

    /**
     * @param Instance $generatedEntity
     */
    #[Override]
    public function _testRelationalPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertContains($this->encounter, $generatedEntity->getEncounters());
    }

    #[Override]
    public function _expectedAssertionsErrorCount(): int
    {
        return 1;
    }
}
