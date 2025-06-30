<?php

namespace App\Filament\Resources\BreathingExerciseResource\Pages;

use App\Filament\Resources\BreathingExerciseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBreathingExercise extends EditRecord
{
    protected static string $resource = BreathingExerciseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
