<?php

namespace App\DataFixtures;

//use App\Entity\Contract;
use App\Entity\User;
use App\Service\CheckSumCalculator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{

    private $calculator;
    private $passwordHasher;

    /**
     * UserFixtures constructor.
     * @param UserPasswordHasherInterface $passwordHasher
     * @param CheckSumCalculator $calculator
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher, CheckSumCalculator $calculator)
    {
        $this->calculator = $calculator;
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager)
    {
//        // create 14 users! Bam!
//        for($i = 0; $i < 14; $i++ ){
//            $user = new User();
//            $user->setFirstName($this->faker->firstName);
//            $user->setLastName($this->faker->lastName);
//            $user->setUsername('user_'.$i);
//            $user->setEnabled(1);
//            $user->setPassword(password_hash('user', PASSWORD_BCRYPT));
//            $user->setEmail('user'.$i.'@shinigami.com');
//            $user->setImage($i.'.jpg');
//
//            // BirthDate
//            $user->setBirthDate($this->faker->dateTimeBetween('-60 years', '-15 years'));
//
//            $user->setPhoneNumber($this->faker->phoneNumber);
//
//            // Role
//            $user->setRoles(['ROLE_USER']);
//
//            // Number card
//            for ($j = 0; $j < 3; $j++) {
//                $codeCenter = 124+$j;
//                $CodeCard = 100010+($i+$j);
//                $checkSum = $this->calculator->calculate($codeCenter, $CodeCard);
//
//                $user->updateCardNumbers($codeCenter . $CodeCard . $checkSum);
//            }
//
//            // addReference garde en référence notre $user sous un certain nom
//            // de façon à le rendre disponible dans les autres fixtures
//            $this->addReference('user'.$i, $user);
//            // $manager persist demande à doctrine de préparer l'insertion de
//            // l'entité en base de données -> INSERT INTO
//            $manager->persist($user);
//        }

        /* ADMIN USER */
        $admin = new User();

        $admin->setFirstName('Géraldine');
        $admin->setLastName('Leclerc');
//        $admin->setUsername('admin');
        $admin->setPassword($this->passwordHasher->hashPassword($admin,'password'));
        $admin->setEmail('admin@shinigami.com');
        $admin->setRoles(['ROLE_ADMIN']);
//        $admin->setEnabled(1);
        $admin->setImage('admin.jpg');

        $admin->setBirthDate(new \DateTimeImmutable('1977-07-23 07:00:00'));

        $admin->setPhoneNumber('0606060606');
        $admin->setCreatedAt(new \DateTimeImmutable());

//        $contract = new Contract();
//        $contract->setStartDate(new \DateTime());
//        $contract->setCenter($this->getReference('center0'));
//        $admin->setContract($contract);

        $this->addReference('admin', $admin);

        $manager->persist($admin);

        // flush() valide les requêtes SQL et les execute
        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            CenterFixtures::class,
        ];
    }
}
