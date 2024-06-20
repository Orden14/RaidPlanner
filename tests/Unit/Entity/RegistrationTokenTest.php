<?php

namespace App\Tests\Unit\Entity;

use App\Entity\RegistrationToken;
use DateTime;
use Override;

final class RegistrationTokenTest extends EntityTest
{
    /**
     * @return RegistrationToken
     */
    #[Override]
    public function _generateEntity(): object
    {
        return (new RegistrationToken())
            ->setExpiryDate((new DateTime())->setTime(23, 59, 59))
            ->setUses(10)
            ->setUsed(2)
        ;
    }

    /**
     * @param RegistrationToken $generatedEntity
     */
    #[Override]
    public function _testBasicPropertiesOf(mixed $generatedEntity): void
    {
        $this->tester->assertNotNull($generatedEntity->getToken());
        $this->tester->assertEquals((new DateTime())->setTime(23, 59, 59), $generatedEntity->getExpiryDate());
        $this->tester->assertSame(10, $generatedEntity->getUses());
        $this->tester->assertSame(2, $generatedEntity->getUsed());
    }

    /**
     * @param RegistrationToken $generatedEntity
     */
    public function _testRelationalPropertiesOf(mixed $generatedEntity): void
    {
        // No relational properties to test
    }

    #[Override]
    public function _expectedAssertionsErrorCount(): int
    {
        return 0;
    }
}
