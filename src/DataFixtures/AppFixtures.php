<?php
/*
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product();
        $manager->persist($product);

        $manager->flush();
    }
}
*/


namespace App\DataFixtures;

use App\Entity\Pluviometrie;
use App\Entity\Cuve;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // 📌 Insérer des données dans Pluviometrie
        $dates = new \DateTimeImmutable('2025-02-10 08:00:00');

        for ($i = 0; $i < 24; $i++) {
            $pluvio = new Pluviometrie();
            $pluvio->setPluvioHeure(rand(0, 10));
            $pluvio->setHorodatage($dates->modify("+$i hours"));
            $manager->persist($pluvio);
        }

        // 📌 Insérer des données dans Cuve
        for ($i = 0; $i < 24; $i++) {
            $cuve = new Cuve();
            $cuve->setNiveauCm(rand(100, 150));
            $cuve->setHorodatage($dates->modify("+$i hours"));
            $manager->persist($cuve);
        }

        $manager->flush();
    }
}


//V3

/*
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@pluvio.com'); // Change selon ton formulaire
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'adminpass'));

        $manager->persist($user);
        $manager->flush();
    }
}
*/