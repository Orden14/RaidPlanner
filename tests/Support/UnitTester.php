<?php

declare(strict_types = 1);

namespace App\Tests\Support;

use Codeception\Actor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Inherited Methods.
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
final class UnitTester extends Actor
{
    use _generated\UnitTesterActions;

    public function grabEntityManagerInterface(): EntityManagerInterface
    {
        return $this->grabService('doctrine.orm.entity_manager');
    }

    public function grabValidator(): ValidatorInterface
    {
        return $this->grabService(ValidatorInterface::class);
    }
}
