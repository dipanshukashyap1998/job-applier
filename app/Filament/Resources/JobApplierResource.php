<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobApplierResource\Pages;
use App\Models\JobApplier;
use App\Models\Company;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Tables;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class JobApplierResource extends Resource
{
    protected static ?string $model = JobApplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\DatePicker::make('apply_date')->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'applied' => 'Applied',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('notes')->nullable(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('user.name')->label('User')->sortable()->searchable(),
                TextColumn::make('company.name')->label('Company')->sortable()->searchable(),
                TextColumn::make('apply_date')->date()->sortable(),
                TextColumn::make('status')->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobAppliers::route('/'),
            'create' => Pages\CreateJobApplier::route('/create'),
            'edit' => Pages\EditJobApplier::route('/{record}/edit'),
        ];
    }
}
