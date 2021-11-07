<?php

namespace App\DataFixtures;

//use App\Entity\Contract;
use App\Entity\Address;
use App\Entity\Contract;
use App\Entity\User;
use App\Service\CheckSumCalculator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{

    private $calculator;
    private $passwordHasher;
    private $faker;

    /**
     * UserFixtures constructor.
     * @param UserPasswordHasherInterface $passwordHasher
     * @param CheckSumCalculator $calculator
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher, CheckSumCalculator $calculator)
    {
        $this->calculator = $calculator;
        $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create('fr_FR');
    }


    public function load(ObjectManager $manager)
    {
        // create 14 users! Bam!
        for($i = 0; $i < 14; $i++ ){
            $user = new User();
            $user->setFirstName($this->faker->firstName);
            $user->setLastName($this->faker->lastName);
            $user->setUsername('user_'.$i);
            $user->setEnabled(1);
            $user->setPassword(password_hash('user', PASSWORD_BCRYPT));
            $user->setEmail('user'.$i.'@shinigami.com');
            $user->setImage($i.'.jpg');
            // BirthDate
            $user->setBirthDate($this->faker->dateTimeBetween('-65 years', '-15 years'));
            $user->setPhoneNumber($this->faker->phoneNumber);
            // Role
            $user->setRoles(['ROLE_USER']);
            // Address
            $address = new Address();
            $address->setAddress($this->faker->address);
            $address->setZipCode((int) $this->faker->postcode);
            $address->setCity($this->faker->city);
            $user->setAddress($address);

            // Number card
            for ($j = 0; $j < 3; $j++) {
                $centerCode = 124+$j;
                $cardCode = 100010+($i+$j);
                $checkSum = $this->calculator->calculate($centerCode, $cardCode);

                $user->addCardNumber($centerCode . $cardCode . $checkSum);
            }

            // addReference garde en référence notre $user sous un certain nom
            // de façon à le rendre disponible dans les autres fixtures
            $this->addReference('user'.$i, $user);
            // $manager persist demande à doctrine de préparer l'insertion de
            // l'entité en base de données -> INSERT INTO
            $manager->persist($user);
        }

        /* ADMIN USER */
        $admin = new User();

        $admin->setFirstName('Géraldine');
        $admin->setLastName($this->faker->lastName);
        $admin->setUsername('admin');
        $admin->setPassword($this->passwordHasher->hashPassword($admin,'password'));
        $admin->setEmail('admin@shinigami.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setEnabled(true);
        $admin->setImage('admin.jpg');
        $admin->setBirthDate($this->faker->dateTimeBetween('-30 years', '-20 years'));
        $admin->setPhoneNumber($this->faker->phoneNumber);

        $contract = new Contract();
        $contract->setStartDate(new \DateTime());
        $contract->setCenter($this->getReference('center0'));
        $admin->setContract($contract);

        // Address
        $address = new Address();
        $address->setAddress($this->faker->address);
        $address->setZipCode((int) $this->faker->postcode);
        $address->setCity($this->faker->city);
        $admin->setAddress($address);

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
