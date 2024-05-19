<?php

namespace App\Tests\Unit\Entity\Interface;

interface EntityTestInterface
{
    /**
     * Test if a complete entity is valid
     */
    public function testRightEntity(): void;

    /**
     * Test an empty or incomplete entity and verify error count
     */
    public function testFalseEntity(): void;

    /**
     * Test if Entity could be successfully saved
     */
    public function testPersistEntity(): void;

    /**
     * Generates an entity for testing
     */
    public function _generateEntity(): object;

    /**
     * Contains every tests related to the entity's basic properties.
     */
    public function _testBasicPropertiesOf(mixed $generatedEntity): void;

    /**
     * Contains every tests related to the entity's relations.
     */
    public function _testRelationPropertiesOf(mixed $generatedEntity): void;

    /**
     * Defines the number of constraint errors expected.
     */
    public function _expectedCountAssertionErrors(): int;

}
