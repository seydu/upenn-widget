<?php
namespace App\Tests\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class UserData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $source = [
            ['email' => 'email@email.com', 'code' => 'superuser'],
        ];
        foreach ($source as $data) {
            $record = new User();
            $record->setEmail($data['email']);
            $record->setUsername($data['email']);
            $manager->persist($record);
            $this->setReference("User-{$data['code']}", $record);
        }
        $manager->flush();
    }
}