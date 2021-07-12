<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    const DEFAULT_USER = ['email' => 'benopen@ben.open', 'password' => 'password','login' => 'ben', 'firstname' => 'ben', 'lastname' => 'open'];
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $fake = Factory::create();

        $defaultUser = new User();
        $passHash = $this->encoder->encodePassword($defaultUser, self::DEFAULT_USER['password']);

        $defaultUser->setEmail(self::DEFAULT_USER['email'])
            ->setLogin(self::DEFAULT_USER['login'])
            ->setFirstname(self::DEFAULT_USER['firstname'])
            ->setLastname(self::DEFAULT_USER['lastname'])
            ->setPassword($passHash);

        $manager->persist($defaultUser);

        for ($u = 0; $u < 10; ++$u) {
            $user = new User();
            

            $passHash = $this->encoder->encodePassword($user, 'password');

            $user->setEmail($fake->email)
                ->setFirstname($fake->firstName)
                ->setLastname($fake->lastName)
                ->setLogin($fake->name)
                ->setPassword($passHash);

            $manager->persist($user);
        }
        for ($u = 0; $u < 10; ++$u) {
            $product = new Product();

            $product->setName($fake->name)
                    ->setDescription($fake->text)
                    ->setPhoto($fake->randomDigitNotNull)
                    ->setPrice($fake->randomDigit);

            $manager->persist($product);
        }
        $manager->flush();
    }
}
