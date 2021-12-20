<?php

namespace App\DataFixtures;

use App\Entity\Mission;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UserFixtures extends Fixture
{
    /** @var UserPasswordHasherInterface */
    private $userPasswordHasher;
    private $client;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher, HttpClientInterface $client)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->client = $client;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        //$user -> setEmail('ProfessorX@AIDSH.fr');
        $user -> setUsername('ProfessorX');
        $user -> setRoles(['ROLE_ADMIN']);
        $user -> setPassword($this->userPasswordHasher->hashPassword($user,'password'));
        $manager -> persist($user);

        for ($i=1;$i<11; $i++) {
            $response = $this->client->request(
                'GET',
                'https://superheroapi.com/api/248762983994539/'. $i
            );

            $statusCode = $response->getStatusCode();
            // $statusCode = 200
            $contentType = $response->getHeaders()['content-type'][0];
            // $contentType = 'application/json'
            $content = $response->getContent();
            // $content = '{"id":521583, "name":"symfony-docs", ...}'
            $content = $response->toArray();
            // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
            //$email = str_replace(' ', '', strtolower($content['name']) . '@AIDSH.fr');
            //$name = $content['name'];

            $user = new User();
            //$user -> setEmail('test.test@gmail.com');
            $user->setUsername($content['name']);
            $user->setRoles(['ROLE_SUPER_HERO']);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, 'password'));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
