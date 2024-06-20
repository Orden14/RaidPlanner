<?php

namespace App\Tests\Support\Helper\Acceptance;

use Codeception\Exception\ModuleException;
use Codeception\Module;
use Codeception\Module\WebDriver;

final class AcceptanceHelper extends Module
{
    /**
     * @throws ModuleException
     */
    public function waitAjaxLoad(int $timeout = 5): void
    {
        /** @var WebDriver $webDriver */
        $webDriver = $this->getModule('WebDriver');
        $webDriver->waitForJS('return !!window.jQuery && window.jQuery.active === 0;', $timeout);
        $webDriver->wait(1);
    }
}
