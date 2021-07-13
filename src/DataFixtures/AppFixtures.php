<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
    	$faker = Factory::create("Fr-fr");

    	$adminRole = new Role();
    	$adminRole->setTitle("ROLE_ADMIN");
    	$manager->persist($adminRole);

    	$adminUser = new User();
    	$adminUser->setFirstName("Pier")
            ->setLastName("Philippon")
            ->setEmail("pier.philippon@gmail.com")
            ->setHash($this->passwordHasher->hashPassword($adminUser, "password"))
            ->setPicture("https://randomuser.me/api/portraits/15.jpg")
            ->setIntroduction($faker->sentence())
            ->setDescription("<p>". join("</p><p>", $faker->paragraphs(3)) . "</p>")
            ->addUserRole($adminRole);
    	$manager->persist($adminUser);

        // Nous gérons les utilisateurs
        $users = [];
        $genders = ["male", "female"];
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();

            $gender = $faker->randomElement($genders);

            $picture = "https://randomuser.me/api/portraits/";
            $pictureId = $faker->numberBetween(1, 99) . ".jpg";

            $picture .= ($gender === "female" ? "women/" : "men/") . $pictureId;

            $hash = $this->passwordHasher->hashPassword($user, 'password');

            $user->setFirstName($faker->firstName($gender))
                 ->setLastName($faker->lastName)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription("<p>". join("</p><p>", $faker->paragraphs(3)) . "</p>")
                 ->setHash($hash)
                 ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }

    	// Nous gérons les annonces
    	for ($i = 1; $i <= 30; $i++){
			$ad = new Ad();

			$title = $faker->sentence();
			$coverImage = $faker->imageUrl(1000, 350);
			$introduction = $faker->paragraph(2);
			$content = "<p>". join("</p><p>", $faker->paragraphs(5)) . "</p>";

			$user = $users[mt_rand(0, count($users) - 1)];

			$ad->setTitle($title)
				->setCoverImage("https://picsum.photos/1000/400?random=" . mt_rand(1, 40000))
				->setIntroduction($introduction)
				->setContent($content)
				->setPrice(mt_rand(40, 200))
				->setRooms(mt_rand(1, 5))
                ->setAuthor($user);

			for($j =1; $j <= mt_rand(2, 5); $j++){
				$image = new Image();

				$image->setUrl("https://picsum.photos/1000/400?random=" . mt_rand(1, 40000))
					->setCaption($faker->sentence())
					->setAd($ad);

				$manager->persist($image);
			}

			$manager->persist($ad);
		}

        $manager->flush();
    }
}
