<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Build;
use App\Entity\BuildCategory;
use Override;

final class BuildCategoryTest extends EntityTest
{
    private Build $build;

    public function _before(): void
    {
        $this->build = $this->tester->grabEntityFromRepository(Build::class, ['id' => 1]);
    }

    /**
     * @return BuildCategory
     */
    #[Override]
    public function _generateEntity(): object
    {
        return (new BuildCategory())
            ->setName('testCategory')
            ->setIcon('testIcon')
            ->addBuild($this->build);
    }

    /**
     * @param BuildCategory $generatedEntity
     */
    #[Override]
    public function _testBasicPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertSame('testCategory', $generatedEntity->getName());
        $this->tester->assertSame('testIcon', $generatedEntity->getIcon());
    }

    /**
     * @param BuildCategory $generatedEntity
     */
    #[Override]
    public function _testRelationalPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertContains($this->build, $generatedEntity->getBuilds());
    }

    #[Override]
    public function _expectedAssertionsErrorCount(): int
    {
        return 1;
    }
}
