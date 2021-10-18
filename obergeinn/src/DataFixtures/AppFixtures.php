<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\MeasureUnit;
use App\Entity\Need;
use App\Entity\Type;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;



class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();


        // Measure-units creation //
        for ($measureUnitId=0; $measureUnitId < 3; $measureUnitId++) { 
            $measureUnits = [];
            $measureUnitArray = [
                'litre',
                'kg',
                'piece',
            ];

            $measureUnits[$measureUnitId] = new MeasureUnit();
            $measureUnits[$measureUnitId]->setUnit($measureUnitArray[$measureUnitId]);
            $manager->persist($measureUnits[$measureUnitId]);
        }

        // Types creation //
        for ($typeId=0; $typeId < 3; $typeId++) { 
            $types = [];
            $typeArray = [
                'Boisson',
                'Alimentaire',
                'Mobilier',
            ];
            $types[$typeId] = new Type();
            $types[$typeId]->setName($typeArray[$typeId]);
            $manager->persist($types[$typeId]);
        }

        // Users creation //
        for ($userId=0; $userId < 2 ; $userId++) { 
            $users = [];
            $userEmailArray = [
                'm.plancheron@gmail.com',
                'petitemo_1011@hotmail.fr',
            ];
            $users[$userId] = new User();
            $users[$userId]->setEmail($userEmailArray[$userId]);
            $users[$userId]->setPassword('$2y$13$Fgl3MbMF1oJpQNSKrFHaKut4rA2QhX0FeVRIOM.lTq5k9FXvB7Wie');
            $users[$userId]->setPseudo($faker->city);
            $users[$userId]->setFirstname($faker->firstName());
            $users[$userId]->setLastname($faker->lastName());
            $manager->persist($users[$userId]);
        }

        // Events creation //
        for ($eventId=0; $eventId < 10 ; $eventId++) { 
            $events = [];
            $events[$eventId] = new Event();
            $events[$eventId]->setTitle($faker->sentence($nbWords = 6, $variableNbWords = true));
            $events[$eventId]->setDescription($faker->text($maxNbChars = 150));
            $events[$eventId]->setAdress($faker->address);

            // Adding a random user to the event
            $randomUser = $users[array_rand($users, rand(1,1))];
            $events[$eventId]->setUser($randomUser);
            $manager->persist($events[$eventId]);
        }
    
        // Needs creation //
        for ($needId=0; $needId < 3 ; $needId++) {
            $needs = [];
            $needs[$needId] = new Need();
            $needs[$needId]->setName($faker->word);
            $needs[$needId]->setQuantity($faker->randomDigitNotNull);
            
            // Adding random measure-unit to the need
            $randomMeasureUnit = $measureUnits[array_rand($measureUnits, rand(1,1))];
            $needs[$needId]->setMeasureUnit($randomMeasureUnit);

            // Adding random type to the need 
            $randomType = $types[array_rand($types, rand(1,1))];
            $needs[$needId]->setType($randomType);

            $manager->persist($needs[$needId]);
        }  
        $manager->flush();
    }
}
