<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Build;
use App\Entity\BuildMessage;
use App\Entity\User;
use DateTime;
use Override;

final class BuildMessageTest extends EntityTest
{
    private Build $build;
    private User $author;

    public function _before(): void
    {
        $this->build = $this->tester->grabEntityFromRepository(Build::class, ['id' => 1]);
        $this->author = $this->tester->grabEntityFromRepository(User::class, ['id' => 1]);
    }

    /**
     * @return BuildMessage
     */
    #[Override]
    public function _generateEntity(): object
    {
        return (new BuildMessage())
            ->setContent('testContent')
            ->setPostedAt((new DateTime())->setTime(23, 59, 59))
            ->setAuthor($this->author)
            ->setBuild($this->build);
    }

    /**
     * @param BuildMessage $generatedEntity
     */
    #[Override]
    public function _testBasicPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertSame('testContent', $generatedEntity->getContent());
        $this->tester->assertEquals((new DateTime())->setTime(23, 59, 59), $generatedEntity->getPostedAt());
    }

    /**
     * @param BuildMessage $generatedEntity
     */
    #[Override]
    public function _testRelationalPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertSame($this->author, $generatedEntity->getAuthor());
        $this->tester->assertSame($this->build, $generatedEntity->getBuild());
    }

    #[Override]
    public function _expectedCountAssertionErrors(): int
    {
        return 1;
    }
}
