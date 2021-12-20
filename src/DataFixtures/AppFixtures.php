<?php

namespace App\DataFixtures;

use App\Entity\Mission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i<5; $i++) {
            $mission = new Mission();
            $mission->setName('Mission name ' . $i);
            $mission->setDescription('Mission description');
            $mission->setPriority(1);
            $mission->setDateReal(new \DateTime('06/04/2014'));
            $mission->setStatut(0);
            $mission->setHeroes('Spiderman');

            $manager->persist($mission);
        }

        $manager->flush();
    }
}
