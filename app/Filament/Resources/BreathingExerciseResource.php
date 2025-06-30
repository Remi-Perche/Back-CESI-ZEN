<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BreathingExerciseResource\Pages;
use App\Filament\Resources\BreathingExerciseResource\RelationManagers;
use App\Models\BreathingExercise;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BreathingExerciseResource extends Resource
{
    protected static ?string $model = BreathingExercise::class;

    protected static ?string $navigationIcon = null;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required(),
                Forms\Components\TextInput::make('inspirationDuration')
                    ->label('Inspiration duration')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('apneaDuration')
                    ->label('Apnea duration')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('expirationDuration')
                    ->label('Expiration duration')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('inspirationDuration')->label('Inspiration duration'),
                Tables\Columns\TextColumn::make('apneaDuration')->label('Apnea duration'),
                Tables\Columns\TextColumn::make('expirationDuration')->label('Expiration duration'),
                Tables\Columns\TextColumn::make('created_at')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListBreathingExercises::route('/'),
            'create' => Pages\CreateBreathingExercise::route('/create'),
            'edit' => Pages\EditBreathingExercise::route('/{record}/edit'),
        ];
    }
}
