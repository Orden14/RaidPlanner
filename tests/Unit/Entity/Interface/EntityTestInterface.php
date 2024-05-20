<?php

namespace App\Tests\Unit\Entity\Interface;

interface EntityTestInterface
{
    public function testRightEntity(): void;
    public function testFalseEntity(): void;
    public function testPersistEntity(): void;
    public function _generateEntity(): object;
    public function _testBasicPropertiesOf(mixed $generatedEntity): void;
    public function _testRelationalPropertiesOf(mixed $generatedEntity): void;
    public function _expectedCountAssertionErrors(): int;

}
