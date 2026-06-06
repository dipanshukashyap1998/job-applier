<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                TextInput::make('designation')
                    ->maxLength(255),
                DatePicker::make('apply_date'),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'applied' => 'Applied',
                        'interview' => 'Interview',
                        'selected' => 'Selected',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }
}
