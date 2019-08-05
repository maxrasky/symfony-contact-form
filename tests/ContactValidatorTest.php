<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Contact;
use App\Validators\ContactValidator;
use UnexpectedValueException;

class ContactValidatorTest extends TestCase
{
    /**
     * @var ContactValidator
     */
    private $validator;

    /**
     * @var Contact
     */
    private $contact;

    protected function setUp()
    {
        $this->validator = new ContactValidator();
        $this->contact = new Contact();
    }


    public function testContactValidatorThrowsException()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->validator->validate(12);
    }

    /**
     * @dataProvider provider
     */
    public function testContactValidatorFails($email, $message)
    {
        $this->contact->setEmail($email)->setMessage($message);
        $errors = $this->validator->validate($this->contact);
        $this->assertCount(1, $errors);

    }

    public function testContactValidatorSucceeds()
    {
        $this->contact->setEmail('correct@email.io')->setMessage('non empty');
        $errors = $this->validator->validate($this->contact);
        $this->assertCount(0, $errors);
    }

    public function provider()
    {
        return [
            ['', ''],
            ['', 'non empty'],
            ['correct@email.io', ''],
        ];
    }
}
