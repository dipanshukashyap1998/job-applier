<?php

namespace App\Filament\Resources\JobAppliers;

use App\Filament\Resources\JobAppliers\Pages\CreateJobApplier;
use App\Filament\Resources\JobAppliers\Pages\EditJobApplier;
use App\Filament\Resources\JobAppliers\Pages\ListJobAppliers;
use App\Filament\Resources\JobAppliers\Schemas\JobApplierForm;
use App\Filament\Resources\JobAppliers\Tables\JobAppliersTable;
use App\Models\JobApplier;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JobApplierResource extends Resource
{
    protected static ?string $model = JobApplier::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    public static function form(Schema $schema): Schema
    {
        return JobApplierForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobAppliersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJobAppliers::route('/'),
            'create' => CreateJobApplier::route('/create'),
            'edit' => EditJobApplier::route('/{record}/edit'),
        ];
    }
}
