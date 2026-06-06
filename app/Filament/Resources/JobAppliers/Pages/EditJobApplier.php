<?php

namespace App\Filament\Resources\JobAppliers\Pages;

use App\Filament\Resources\JobAppliers\JobApplierResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJobApplier extends EditRecord
{
    protected static string $resource = JobApplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
