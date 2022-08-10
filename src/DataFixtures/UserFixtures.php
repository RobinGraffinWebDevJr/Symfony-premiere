<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{

    public function __construct(UserPasswordEncoderInterface $encoder)
    {

    }


    public function load(ObjectManager $manager): void
    {
        
        $user = new User();
        $user->setUsername('demo');
        $user->setPassword('demo');

        $manager->flush();
    }
}
