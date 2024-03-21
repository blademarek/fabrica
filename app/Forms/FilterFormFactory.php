<?php

namespace App\Forms;

use App\Model\PetManager;
use Nette\Application\UI\Form;

class FilterFormFactory
{
    public function __construct(
        private PetManager $petManager
    ) {
    }

    public function create(string $status = null): Form
    {
        $form = new Form;
        $form->onSuccess[] = [$this, 'formSucceeded'];

        $form->addSelect('filter', 'Filter', $this->petManager->getStatuses())
            ->setHtmlAttribute('class', 'form-control')
            ->setPrompt('Select filter')
            ->setDefaultValue($status);

        $form->addSubmit('submit', 'Apply')
            ->setHtmlAttribute('class', 'btn btn-outline-dark');

        $form->onSuccess[] = [$this, 'callback'];

        return $form;
    }

    public function formSucceeded(Form $form, $values): void
    {
        $status = $values->filter;

        $this->onCallback = function () use ($status) {
            $this->onSubmit->__invoke($status);
        };
    }

    public function callback(): void
    {
        $this->onCallback?->__invoke();
    }
}