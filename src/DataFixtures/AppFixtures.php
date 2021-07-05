<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    	$faker = Factory::create("Fr-fr");
        // Nous gérons les utilisateurs
        $users = [];
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();

            $user->setFirstName($faker->firstName)
                 ->setLastName($faker->lastName)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription("<p>". join("</p><p>", $faker->paragraphs(3)) . "</p>")
                 ->setHash("password");

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
