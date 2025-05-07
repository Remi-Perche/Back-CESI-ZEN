<?php

namespace App\Filament\Resources\BreathingExerciseResource\Pages;

use App\Filament\Resources\BreathingExerciseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBreathingExercises extends ListRecords
{
    protected static string $resource = BreathingExerciseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
