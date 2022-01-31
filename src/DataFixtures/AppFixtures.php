<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\User;
use App\Factory\AdFactory;
use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use App\Factory\TagFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(10);
        TagFactory::createMany(5);
        AdFactory::createMany(10);
        AnswerFactory::createMany(30);

    }
}
