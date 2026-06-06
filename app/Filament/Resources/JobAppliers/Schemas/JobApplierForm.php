<?php

namespace App\Filament\Resources\JobAppliers\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class JobApplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('data')
                    ->rows(12)
                    ->columnSpanFull(),
            ]);
    }
}
