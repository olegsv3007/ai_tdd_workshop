<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Generator;

abstract class BaseFixture extends Fixture
{
    protected Generator $faker;
}
