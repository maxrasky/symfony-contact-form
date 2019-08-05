<?php

namespace App\Services;

use App\Entity\Contact;
use App\Models\ImportResponseModel;
use App\Repository\ContactRepository;
use App\Validators\ContactValidator;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Exception\ValidatorException;
use Exception;
use InvalidArgumentException;

/**
 * Class ContactService
 * @package App\Services
 */
class ContactService
{
    /**
     * @var ContactValidator
     */
    private $validator;

    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->validator = new ContactValidator();
        $this->registry = $registry;
    }

    public function createContact(string $email, string $message)
    {
        $contact = new Contact();
        $contact->setEmail($email);
        $contact->setMessage($message);

        $errors = $this->validator->validate($contact);

        if (count($errors)) {
            throw new ValidatorException(implode(PHP_EOL, $errors));
        }

        if ($this->contactExists($email)) {
            throw new InvalidArgumentException('User already exists');
        }

        $manager = $this->registry->getManager();
        $manager->persist($contact);
        $manager->flush();

        return $contact->getId();
    }

    public function importContacts(array $data): ImportResponseModel
    {
        return array_reduce(
            $data,
            function (ImportResponseModel $acc, $row) {
                try {
                    if (count($row) < 2) {
                        $acc->errors[] = 'not enough parameters';
                        $acc->failed++;
                        return $acc;
                    }

                    [$email, $message] = $row;
                    $this->createContact($email, $message);

                    $acc->succeeded++;
                    return $acc;
                } catch (Exception $e) {
                    $acc->failed++;
                    $acc->errors[] = $e->getMessage();
                    return $acc;
                }
            },
            new ImportResponseModel()
        );

    }

    public function getContact($id)
    {
        $entityManager = $this->registry->getManager();
        $repository = $entityManager->getRepository(Contact::class);
        return $repository->find($id);
    }

    public function contactExists($email)
    {
        $entityManager = $this->registry->getManager();
        /** @var ContactRepository $repository */
        $repository = $entityManager->getRepository(Contact::class);
        return $repository->findByEmail($email) !== null;
    }
}
