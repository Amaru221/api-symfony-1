<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use App\Entity\DragonTreasure;
use App\Factory\DragonTreasureFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        DragonTreasureFactory::createMany(40);
        UserFactory::createMany(10);
    }
}
