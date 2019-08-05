<?php

namespace App\Tests;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use App\Services\ContactService;
use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Validator\Exception\ValidatorException;

class ContactServiceTest extends TestCase
{
    private $registryMock;

    protected function setUp()
    {
        $repositoryMock = $this->createMock(ContactRepository::class);
        $repositoryMock
            ->expects($this->any())
            ->method('findByEmail')
            ->willReturn(null);

        $managerMock = $this->createMock(ObjectManager::class);
        $managerMock
            ->expects($this->any())
            ->method('getRepository')
            ->with(Contact::class)
            ->willReturn($repositoryMock);

        $managerMock->method('persist');
        $managerMock->method('flush');

        $this->registryMock = $this->createMock(ManagerRegistry::class);
        $this->registryMock
            ->expects($this->any())
            ->method('getManager')
            ->willReturn($managerMock);
    }

    public function testCreateContactFail()
    {
        $this->expectException(ValidatorException::class);

        $service = new ContactService($this->registryMock);
        $service->createContact('wrong@email', 'Some message');
        $service->createContact('empty_message@email.io', '');
    }

    public function testCreateContactSuccess()
    {
        $service = new ContactService($this->registryMock);
        $this->assertNull($service->createContact('correct@mail.io', 'some message'));
    }

    public function testImportContacts()
    {
        $data = [
            ['wrong@email', ''],
            ['marilyn@manson.io', 'lunch box'],
            ['kurt@cobain.io', 'smells like teen spirit'],
            ['empty_message@email.io', ''],
        ];

        $service = new ContactService($this->registryMock);
        $response = $service->importContacts($data);

        $this->assertEquals(2, $response->succeeded);
        $this->assertEquals(2, $response->failed);
        $this->assertCount(2, $response->errors);
    }
}
