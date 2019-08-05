<?php

namespace App\Controller;

use App\Serializer\JsonEncoder;
use App\Models\ContactModel;
use App\Services\ContactService;
use App\Validators\ContactValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;

/**
 * Class ContactController
 * @package App\Controller
 */
class ContactController extends AbstractController
{
    public function createContact(Request $request)
    {
        $encoder = new JsonEncoder();
        $model = $encoder->deserialize($request->getContent(), ContactModel::class);

        $service = new ContactService($this->getDoctrine());

        try {
            $id = $service->createContact($model->email, $model->message);
        } catch (Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'id' => $id,
        ], Response::HTTP_CREATED);
    }

    public function getContact($id)
    {
        $service = new ContactService($this->getDoctrine());
        $contract = $service->getContact($id);
        $encoder = new JsonEncoder();

        return $contract
            ? JsonResponse::fromJsonString($encoder->serialize($contract))
            : new JsonResponse([
                'error' => 'Not found',
            ], Response::HTTP_NOT_FOUND);
    }

    public function importContacts(Request $request)
    {
        $file = $request->files->get('file');
        $data = $this->readCsvFileData($file);

        $service = new ContactService($this->getDoctrine());
        $response = $service->importContacts($data);

        return new JsonResponse($response);
    }

    private function readCsvFileData($csvFile)
    {
        $handle = fopen($csvFile, 'r');
        if ($handle === false) {
            return [];
        }

        $result = [];
        $length = ContactValidator::EMAIL_LENGTH + ContactValidator::MESSAGE_LENGTH;

        while (($row = fgetcsv($handle, $length)) !== false) {
            $result[] = $row;
        }

        return $result;
    }
}
