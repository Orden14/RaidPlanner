<?php

namespace App\Tests\Unit\Entity;

use App\Entity\UserActivityLog;
use App\Enum\UserActivityLogTypeEnum;
use Override;

final class UserActivityLogTest extends EntityTest
{
    /**
     * @return UserActivityLog
     */
    #[Override]
    public function _generateEntity(): object
    {
        return (new UserActivityLog())
            ->setLogMessage('testLogMessage')
            ->setType(UserActivityLogTypeEnum::BUILD);
    }

    /**
     * @param UserActivityLog $generatedEntity
     */
    #[Override]
    public function _testBasicPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertSame('testLogMessage', $generatedEntity->getLogMessage());
        $this->tester->assertSame(UserActivityLogTypeEnum::BUILD, $generatedEntity->getType());
        $this->tester->assertNotNull($generatedEntity->getDate());
    }

    /**
     * @param UserActivityLog $generatedEntity
     */
    #[Override]
    public function _testRelationalPropertiesOf(mixed $generatedEntity): void
    {
        // No relational properties to test
    }

    #[Override]
    public function _expectedCountAssertionErrors(): int
    {
        return 0;
    }
}
