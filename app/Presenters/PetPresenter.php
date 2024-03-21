<?php

namespace App\Presenters;

use App\Forms\FilterFormFactory;
use App\Forms\PetFormFactory;
use App\Model\PetManager;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

class PetPresenter extends Presenter
{
    private FilterFormFactory $filterFormFactory;
    private PetFormFactory $petFormFactory;
    private PetManager $petManager;

    public function __construct(
        FilterFormFactory $filterFormFactory,
        PetFormFactory $petFormFactory,
        PetManager $petManager
    ) {
        $this->filterFormFactory = $filterFormFactory;
        $this->petFormFactory = $petFormFactory;
        $this->petManager = $petManager;
    }

    public function renderShop($status = null): void
    {
        $pets = $this->petManager->findPetsByStatus($status);
        $this->template->pets = $pets;

        $this->template->statuses = $this->petManager->getStatuses();
    }

    public function renderEdit(string $petId): void
    {
        $this->template->petId = $petId;
    }

    public function actionDelete(string $petId): void
    {
        $pet = $this->petManager->getPet($petId);
        if (!$pet) {
            $this->flashMessage('Failed to delete pet', 'error');
            $this->redirect('Pet:shop');
        }

        $success = $this->petManager->deletePet($petId);
        if ($success) {
            $this->flashMessage('Pet deleted successfully', 'success');
        } else {
            $this->flashMessage('Failed to delete pet', 'error');
        }

        $this->redirect('Pet:shop');
    }

    public function actionPut($petData): void
    {
        if ($this->petManager->editPet($petData->id, $petData)) {
            $this->flashMessage('Pet updated', 'success');
        } else {
            $this->flashMessage('Error saving pet', 'error');
        }

        $this->redirect('Pet:shop');
    }

    public function actionPost($petData): void
    {
        if ($this->petManager->addPet($petData)) {
            $this->flashMessage('Pet added', 'success');
        } else {
            $this->flashMessage('Error adding pet', 'error');
        }

        $this->redirect('Pet:shop');
    }

    public function actionFindByStatus(string $status = null): void
    {
        $pets = $this->petManager->findPetsByStatus($status);
        $this->template->pets = $pets;
        $this->template->statuses = $this->petManager->getStatuses();
    }

    public function createComponentPetForm(): Form
    {
        $id = null;
        if (isset($this->params['petId'])) {
            $id = $this->params['petId'];
        }

        $form = $this->petFormFactory->create($id);

        $this->petFormFactory->onDelete = function ($petData) {
            $this->actionDelete($petData->id);
        };

        $this->petFormFactory->onAdd = function ($petData) {
            $this->actionPost($petData);
        };

        $this->petFormFactory->onSave = function ($petData) {
            $this->actionPut($petData);
        };

        return $form;
    }

    protected function createComponentFilterForm(): Form
    {
        $status = $this->getParameter('status');
        $form = $this->filterFormFactory->create($status);

        $this->filterFormFactory->onSubmit = function ($status) {
            $this->actionFindByStatus($status);
            $this->redirect('this', ['status' => $status]);
        };

        return $form;
    }
}