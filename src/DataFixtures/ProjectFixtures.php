<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use DateTimeZone;
use Faker\Generator;
use DateTimeImmutable;
use App\Entity\Comment;
use App\Entity\Project;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProjectFixtures extends Fixture
{
    private Generator $faker; 

    public function __construct(
        private UserPasswordHasherInterface $hasher
    )
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $projects = [];

        //noalia
        $project = new Project;
        $project->setTitle('Noalia fée des gâteaux')
                ->setType('Site vitrine')
                ->setUrl('https://noaliafeedesgateaux-88d169640ab4.herokuapp.com/')
                ->setShortDescription('Un site vitrine fait avec Symfony.')
                ->setLongDescription('Interface d\'administration, formulaire de contact')
                ->setStartedAt(new DateTimeImmutable('2022:12:01 12:00:00', new DateTimeZone('Europe/Paris')))
                ->setEndAt(new DateTimeImmutable('2023:01:15 12:00:00', new DateTimeZone('Europe/Paris')))
                ;
        $projects[] = $project;
        $manager->persist($project);

        //larimar
        $project = new Project;
        $project->setTitle('Larimar France')
                ->setType('Site e-commerce')
                ->setUrl('https://larimar-france.com')
                ->setShortDescription('Un site e-commerce fait avec Symfony et React')
                ->setLongDescription('Vente de bijoux à fontaine de vaucluse')
                ->setStartedAt(new DateTimeImmutable('2022:12:01 12:00:00', new DateTimeZone('Europe/Paris')))
                ->setEndAt(new DateTimeImmutable('2023:01:15 12:00:00', new DateTimeZone('Europe/Paris')))
                ;
        $projects[] = $project;
        $manager->persist($project);

        //cocktailissimo
        $project = (new Project)
                ->setTitle('Cocktailissimo')
                ->setType('Marketplace')
                ->setUrl('https://cocktailissimo.com')
                ->setShortDescription('Une marketplace faite avec symfony et react.')
                ->setLongDescription('en 4 langues')
                ->setStartedAt(new DateTimeImmutable('2022:12:01 12:00:00', new DateTimeZone('Europe/Paris')))
                ->setEndAt(new DateTimeImmutable('2023:01:15 12:00:00', new DateTimeZone('Europe/Paris')))
                ;
        $projects[] = $project;
        $manager->persist($project);


        $comments = [];
        for ($i=0; $i < 50; $i++) { 
            $comment = (new Comment)
            ->setFullName($this->faker->name())
            ->setCompany($this->faker->company())
            ->setContent($this->faker->paragraph(random_int(1, 3)))
            ->setCreatedAt(new DateTimeImmutable($this->faker->date()))
            ;
            /** @var Project */
            $project = $this->faker->randomElement($projects);
            $project->addComment($comment);

            $manager->persist($comment);
            $comments[] = $comment;
        }

        for ($i=0; $i < 150; $i++) { 
            $answer = (new Answer)
            ->setFullName($this->faker->name())
            ->setCompany($this->faker->company())
            ->setContent($this->faker->paragraph(random_int(1, 3)))
            ->setCreatedAt(new DateTimeImmutable($this->faker->date()))
            ;
            if(random_int(1, 9) > 3)
            {
                $answer->setByAdmin(true);
            }
            /** @var Comment */
            $comment = $this->faker->randomElement($comments);
            $comment->addAnswer($answer);

            $manager->persist($answer);
        }

        $manager->flush();
    }
}
