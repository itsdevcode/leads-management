<?php

namespace App\Filament\Resources\Leads\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('contact_person')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('phone')
                    ->tel(),
                Select::make('status')
                    ->options([
            'new' => 'New',
            'contacted' => 'Contacted',
            'in_progress' => 'In progress',
            'qualified' => 'Qualified',
            'lost' => 'Lost',
            'converted' => 'Converted',
        ])
                    ->default('new')
                    ->required(),
                TextInput::make('source'),
                Select::make('priority')
                    ->options(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'])
                    ->default('medium')
                    ->required(),
                DatePicker::make('follow_up_date'),
                TextInput::make('notes_count')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
