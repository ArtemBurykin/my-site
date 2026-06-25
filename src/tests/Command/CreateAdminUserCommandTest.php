<?php

namespace App\Tests\Command;

use App\Entity\User;
use App\Tests\Traits\DependenciesTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;

class CreateAdminUserCommandTest extends KernelTestCase
{
    use DependenciesTrait;

    public function testSuccessful()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:create-admin-user');

        $email = 'ivanov@test.ru';
        $password = 'abc123';

        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'email' => $email,
            'password' => $password,
        ]);

        $commandTester->assertCommandIsSuccessful();

        $users = $this->getUserRepository()->findAll();

        $this->assertCount(1, $users);

        $user = $users[0];

        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals(['ROLE_USER', 'ROLE_ADMIN'], $user->getRoles());
        $this->assertTrue($this->getPasswordHasher()->isPasswordValid($user, $password));
    }

    public static function dataProviderParams()
    {
        return [
            'without email' => [['password' => '123456']],
            'without password' => [['email' => 'test@email.com']],
        ];
    }

    #[DataProvider('dataProviderParams')]
    public function testFailWithoutParam(array $params)
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:create-admin-user');

        $commandTester = new CommandTester($command);

        try {
            $commandTester->execute($params);
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('Not enough arguments', $e->getMessage());
        }

        $users = $this->getUserRepository()->findAll();

        $this->assertCount(0, $users);
    }

    public function testFailUserNotUnique()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $user = new User();
        $email = 'test@example.com';
        $user->setEmail($email);
        $hashedPwd = $this->getPasswordHasher()->hashPassword($user, '12345');
        $user->setPassword($hashedPwd);
        $this->getUserRepository()->save($user);

        $this->getEntityManager()->clear();

        $command = $application->find('app:create-admin-user');

        $commandTester = new CommandTester($command);

        $password = 'pass';

        $commandTester->execute(['email' => $email, 'password' => $password]);
        $this->assertNotEquals(0, $commandTester->getStatusCode());
        $error = $commandTester->getDisplay();
        $this->assertStringContainsString('The user already exists', $error);

        $users = $this->getUserRepository()->findAll();

        $this->assertCount(1, $users);
        $this->assertEquals(['ROLE_USER'], $users[0]->getRoles());
        $this->assertFalse($this->getPasswordHasher()->isPasswordValid($users[0], $password));
    }
}
