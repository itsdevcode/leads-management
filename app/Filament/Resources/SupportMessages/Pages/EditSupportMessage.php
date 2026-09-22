<?php

namespace App\Filament\Resources\SupportMessages\Pages;

use App\Filament\Resources\SupportMessages\SupportMessageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSupportMessage extends EditRecord
{
    protected static string $resource = SupportMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
