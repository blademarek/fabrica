<?php

namespace App\Forms;

use App\Model\PetManager;
use Nette\Application\UI\Form;

class PetFormFactory
{
    public function __construct(
        private PetManager $petManager
    ) {
    }

    public function create($petId): Form
    {
        $defaults = [];

        $form = new Form;
        $form->onSuccess[] = [$this, 'formSucceeded'];

        $form->addText('name', 'Name:')
            ->setRequired('Name is required')
            ->setHtmlAttribute('class', 'form-control');

        $form->addText('category', 'Category:')
            ->setHtmlAttribute('class', 'form-control');

        $form->addText('image', 'Image:')
            ->setHtmlAttribute('class', 'form-control')
            ->setHtmlAttribute('placeholder', 'Enter valid url');

        $form->addSelect('status', 'Status:', $this->petManager->getStatuses())
            ->setHtmlAttribute('class', 'form-control');

        $form->addSubmit('send', $petId ? 'Save' : 'Add')
            ->setHtmlAttribute('class', 'btn btn-primary');

        if (isset($petId)) {
            $form->addSubmit('delete', 'Delete')
                ->setHtmlAttribute('class', 'btn btn-danger');

            $pet = $this->petManager->getPet($petId);

            $form->addHidden('id', $petId);
            $defaults = $pet;
        }

        $form->setDefaults($defaults);
        $form->onSuccess[] = [$this, 'callback'];

        return $form;
    }

    public function formSucceeded(Form $form, $values): void
    {
        $submitValue = $form->isSubmitted()->getValue();

        if ($submitValue === 'Delete') {
            $this->onCallback = function () use ($values) {
                $this->onDelete->__invoke($values);
            };
        }

        if ($submitValue === 'Add') {
            $this->onCallback = function () use ($values) {
                $this->onAdd->__invoke($values);
            };
        }

        if ($submitValue === 'Save') {
            $this->onCallback = function () use ($values) {
                $this->onSave->__invoke($values);
            };
        }
    }

    public function callback(): void
    {
        $this->onCallback?->__invoke();
    }
}