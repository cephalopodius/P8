<?php

namespace App\DataFixtures\Dev;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @codeCoverageIgnore
 */
class AppFixtures extends Fixture implements FixtureGroupInterface
{

  public static function getGroups(): array
   {
       return ['dev'];
   }

   private $encoder;

   public function __construct(UserPasswordEncoderInterface $encoder)
   {
       $this->encoder = $encoder;
   }

   public function load(ObjectManager $manager)
   {
         // Admin User
         $user = new User();
         $user->setUsername('admin')
              ->setEmail('boss@boss.fr')
              ->setPassword($this->encoder->encodePassword($user, 'test'))
              ->setRoles(['ROLE_ADMIN'])
         ;
         $manager->persist($user);
         $this->addReference('admin', $user);

         // Simple User
         $user = new User();
         $user->setUsername('user')
              ->setEmail('user@user.fr')
              ->setPassword($this->encoder->encodePassword($user, 'test'))
              ->setRoles(['ROLE_USER'])
         ;
         $manager->persist($user);
         $this->addReference('user', $user);

         // Task created by simple user
         $task = new Task();
         $task->setTitle('Tâche 1')
              ->setContent('Tâche crée par un user')
              ->setUser($this->getReference('user'))
         ;
         $manager->persist($task);

         // Task created by admin user
         $task = new Task();
         $task->setTitle('Tâche 2')
              ->setContent('Tâche  crée par un admin')
              ->setUser($this->getReference('admin'))
         ;
         $manager->persist($task);

         $manager->flush();
   }
}
