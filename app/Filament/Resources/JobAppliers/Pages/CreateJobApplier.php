<?php

namespace App\Filament\Resources\JobAppliers\Pages;

use App\Filament\Resources\JobAppliers\JobApplierResource;
use App\Models\Company;
use Filament\Resources\Pages\CreateRecord;
use Throwable;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Filament\Resources\Events\RecordCreated;
use Filament\Resources\Events\RecordSaved;
use Illuminate\Support\Facades\Event;

use function PHPSTORM_META\type;

class CreateJobApplier extends CreateRecord
{
    protected static string $resource = JobApplierResource::class;

     public function create(bool $another = false): void
    {
        if ($this->isCreating) {
            return;
        }

        $this->isCreating = true;

        $this->authorizeAccess();

        if ($another) {
            $preserveRawState = $this->preserveFormDataWhenCreatingAnother($this->form->getRawState());
        }

        try {
            $this->beginDatabaseTransaction();

            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeCreate($data);

            $this->jobProcess($data);

            $this->callHook('beforeCreate');

            $this->record = $this->handleRecordCreation($data);

            $this->form->model($this->getRecord())->saveRelationships();

            $this->callHook('afterCreate');
            Event::dispatch(RecordCreated::class, ['record' => $this->record, 'data' => $data, 'page' => $this]);
            Event::dispatch(RecordSaved::class, ['record' => $this->record, 'data' => $data, 'page' => $this]);
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction() ?
                $this->rollBackDatabaseTransaction() :
                $this->commitDatabaseTransaction();

            $this->isCreating = false;

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            $this->isCreating = false;

            throw $exception;
        }

        $this->commitDatabaseTransaction();

        $this->rememberData();

        $this->getCreatedNotification()?->send();

        if ($another) {
            // Ensure that the form record is anonymized so that relationships aren't loaded.
            $this->form->model($this->getRecord()::class);
            $this->record = null;

            $this->fillForm();

            $this->form->rawState([
                ...$this->form->getRawState(),
                ...$preserveRawState,
            ]);

            // Rebuild child schemas without double-firing `afterStateHydrated()` hooks.
            $hydratedDefaultState = null;
            $this->form->hydrateState($hydratedDefaultState, shouldCallHydrationHooks: false);
            $this->form->dispatchClientSideStateReset();

            $this->isCreating = false;

            return;
        }

        $redirectUrl = $this->getRedirectUrl();

        $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode($redirectUrl));
    }

    public function jobProcess($data)
    {
        $this->jobSeparateProcess($data);
    }

    public function jobSeparateProcess($data)
    {
       $string = explode("\n", $data['data']);

       $jobs = [];

       foreach($string as $item){

            [$email, $designation] = array_map('trim', explode(',', $item, 2));

             $jobs[] = [
                    'email' => $email,
                    'designation' => $designation,
                ];

                Company::firstOrCreate([
                    'email' => $email,
                    'designation' => $designation,
                ]);
       }
    }
}
