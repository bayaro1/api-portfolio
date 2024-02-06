<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    )
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('ibai')
            ->setPassword(
                $this->hasher->hashPassword($user, 'Ibai64600')
            )
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ;

        $manager->persist($user);
        $manager->flush();
    }
}
