<?php

namespace App\DataFixtures;

use App\Entity\Skill;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SkillFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $skill = (new Skill)->setCategory(Skill::CAT_FRAMEWORKS)->setName('Symfony')->setLearnedAt(new DateTimeImmutable());
        $manager->persist($skill);
        $skill = (new Skill)->setCategory(Skill::CAT_FRAMEWORKS)->setName('React js')->setLearnedAt(new DateTimeImmutable());
        $manager->persist($skill);
        $skill = (new Skill)->setCategory(Skill::CAT_FRAMEWORKS)->setName('Next js')->setLearnedAt(new DateTimeImmutable());
        $manager->persist($skill);
        $skill = (new Skill)->setCategory(Skill::CAT_LANGUAGES)->setName('HTML')->setLearnedAt(new DateTimeImmutable());
        $manager->persist($skill);
        $skill = (new Skill)->setCategory(Skill::CAT_LANGUAGES)->setName('CSS')->setLearnedAt(new DateTimeImmutable());
        $manager->persist($skill);
        $skill = (new Skill)->setCategory(Skill::CAT_LANGUAGES)->setName('PHP')->setLearnedAt(new DateTimeImmutable());
        $manager->persist($skill);
        $skill = (new Skill)->setCategory(Skill::CAT_LANGUAGES)->setName('JavaScript')->setLearnedAt(new DateTimeImmutable());
        $manager->persist($skill);
        $skill = (new Skill)->setCategory(Skill::CAT_LANGUAGES)->setName('TypeScript')->setLearnedAt(new DateTimeImmutable());
        $manager->persist($skill);
        $skill = (new Skill)->setCategory(Skill::CAT_UTILS)->setName('Git')->setLearnedAt(new DateTimeImmutable());
        $manager->persist($skill);
        $skill = (new Skill)->setCategory(Skill::CAT_UTILS)->setName('Github')->setLearnedAt(new DateTimeImmutable());
        $manager->persist($skill);
        $skill = (new Skill)->setCategory(Skill::CAT_UTILS)->setName('Docker')->setLearnedAt(new DateTimeImmutable());
        $manager->persist($skill);
        $skill = (new Skill)->setCategory(Skill::CAT_UTILS)->setName('Jenckins')->setLearnedAt(new DateTimeImmutable());
        $manager->persist($skill);
        $skill = (new Skill)->setCategory(Skill::CAT_UTILS)->setName('MySql')->setLearnedAt(new DateTimeImmutable());
        $manager->persist($skill);
        $skill = (new Skill)->setCategory(Skill::CAT_UTILS)->setName('phpMyAdmin')->setLearnedAt(new DateTimeImmutable());
        $manager->persist($skill);

        $manager->flush();
    }
}
