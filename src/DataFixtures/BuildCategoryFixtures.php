<?php

namespace App\DataFixtures;

use App\DataFixtures\util\FileMockUploader;
use App\Entity\BuildCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class BuildCategoryFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly FileMockUploader $fileMockUploader
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getBuildCategoryList() as $categoryName) {
            $manager->persist($this->createCategory($categoryName));
        }

        $manager->flush();
    }

    private function createCategory(string $categoryName): BuildCategory
    {
        $filename = $this->fileMockUploader->mockFileUpload(strtolower($categoryName));

        return (new BuildCategory())
            ->setName($categoryName)
            ->setIcon($filename)
        ;
    }

    /**
     * @return string[]
     */
    private function getBuildCategoryList(): array
    {
        return [
            'Pdps',
            'Cdps',
            'Heal',
            'Quickness',
            'Alacrity',
        ];
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            JobFixtures::class,
        ];
    }
}
