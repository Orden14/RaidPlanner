<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Build;
use App\Entity\BuildCategory;
use App\Entity\BuildMessage;
use App\Entity\Specialization;
use App\Entity\User;
use App\Enum\BuildStatusEnum;
use DateTime;
use Override;

final class BuildTest extends EntityTest
{
    private Specialization $specialization;
    private BuildCategory $buildCategory;
    private BuildMessage $buildMessage;
    private User $author;

    public function _before(): void
    {
        $this->specialization = $this->tester->grabEntityFromRepository(Specialization::class, ['id' => 1]);
        $this->buildCategory = $this->tester->grabEntityFromRepository(BuildCategory::class, ['id' => 1]);
        $this->buildMessage = $this->tester->grabEntityFromRepository(BuildMessage::class, ['id' => 1]);
        $this->author = $this->tester->grabEntityFromRepository(User::class, ['id' => 1]);
    }

    /**
     * @return Build
     */
    #[Override]
    public function _generateEntity(): object
    {
        $build = new Build();

        $build->setName('testName')
            ->setStatus(BuildStatusEnum::META)
            ->setLastEditedAt((new DateTime())->setTime(23, 59, 59))
            ->setBenchmark(100)
            ->setLink('https://test1.com')
            ->setVideoLink('https://test2.com')
            ->setBenchmarkLink('https://test3.com')
            ->setSpecialization($this->specialization)
            ->addCategory($this->buildCategory)
            ->addBuildMessage($this->buildMessage)
            ->setAuthor($this->author);

        return $build;
    }

    /**
     * @param Build $generatedEntity
     */
    #[Override]
    public function _testBasicPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertSame('testName', $generatedEntity->getName());
        $this->tester->assertSame(BuildStatusEnum::META, $generatedEntity->getStatus());
        $this->tester->assertEquals((new DateTime())->setTime(23, 59, 59), $generatedEntity->getLastEditedAt());
        $this->tester->assertSame(100, $generatedEntity->getBenchmark());
        $this->tester->assertSame('https://test1.com', $generatedEntity->getLink());
        $this->tester->assertSame('https://test2.com', $generatedEntity->getVideoLink());
        $this->tester->assertSame('https://test3.com', $generatedEntity->getBenchmarkLink());
    }

    /**
     * @param Build $generatedEntity
     */
    #[Override]
    public function _testRelationalPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertSame($this->author, $generatedEntity->getAuthor());
        $this->tester->assertSame($this->specialization, $generatedEntity->getSpecialization());
        $this->tester->assertContains($this->buildCategory, $generatedEntity->getCategories());
        $this->tester->assertContains($this->buildMessage, $generatedEntity->getBuildMessages());
    }

    #[Override]
    public function _expectedAssertionsErrorCount(): int
    {
        return 1;
    }
}
