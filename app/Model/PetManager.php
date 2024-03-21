<?php

namespace App\Model;

class PetManager
{
    private XmlManager $xmlManager;

    public function __construct(XmlManager $xmlManager)
    {
        $this->xmlManager = $xmlManager;
    }

    public function getPets(): array
    {
        $xml = $this->xmlManager->loadXml();

        if (count($xml->pet) === 0) {
            return [];
        }

        $newXml = $this->xmlManager->unifyStructure($xml);

        return $newXml->pet;
    }

    public function getPet($petId): ?array
    {
        $xml = $this->xmlManager->loadXml();

        foreach ($xml->pet as $pet) {
            if ((string)$pet->id === $petId) {
                return json_decode(json_encode($pet), true);
            }
        }

        return null;
    }

    public function addPet($petData): bool
    {
        $xml = $this->xmlManager->loadXml();

        $newPet = $xml->addChild('pet');
        $newPet->id = uniqid();

        foreach ($petData as $key => $value) {
            if (strlen($value)) {
                $newPet->$key = $value;
            }
        }

        return $this->xmlManager->saveXml($xml);
    }

    public function editPet(string $petId, $newData): bool
    {
        $xml = $this->xmlManager->loadXml();

        $petToUpdate = null;
        foreach ($xml->pet as $pet) {
            if ((string)$pet->id === $petId) {
                $petToUpdate = $pet;
                break;
            }
        }

        if ($petToUpdate === null) {
            return false;
        }

        foreach ($newData as $key => $value) {
            if (strlen($value)) {
                $petToUpdate->$key = $value;
            } else {
                unset ($petToUpdate->$key);
            }
        }

        return $this->xmlManager->saveXml($xml);
    }

    public function deletePet($petId): bool
    {
        $xml = $this->xmlManager->loadXml();
        $xmlArray = $this->xmlManager->unifyStructure($xml);

        foreach ($xmlArray->pet as $index => $pet) {
            if ($pet->id === $petId) {
                unset($xml->pet[$index]);
                break;
            }
        }

        return $this->xmlManager->saveXml($xml);
    }

    public function findPetsByStatus(string $status = null): array
    {
        $pets = $this->getPets();

        if (!isset($status)) {
            return $pets;
        }

        return array_filter($pets, function ($pet) use ($status) {
            return isset($pet->status) && $pet->status === $status;
        });
    }

    public function getStatuses(): array
    {
        return [
            'available' => 'Available',
            'pending' => 'Pending',
            'sold' => 'Sold',
        ];
    }
}