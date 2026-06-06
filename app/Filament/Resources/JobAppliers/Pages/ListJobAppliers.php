<?php

namespace App\Filament\Resources\JobAppliers\Pages;

use App\Filament\Resources\JobAppliers\JobApplierResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJobAppliers extends ListRecords
{
    protected static string $resource = JobApplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
