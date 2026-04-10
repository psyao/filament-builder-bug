<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),

                Builder::make('body')
                    ->blocks([
                        Block::make('services')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Titre')
                                    ->required(),

                                Repeater::make('items')
                                    ->schema([
                                        Select::make('service')
                                            ->label('Service')
                                            ->options([
                                                1 => 'Service 1',
                                                2 => 'Service 2',
                                                3 => 'Service 3',
                                            ])
                                            ->required(),
                                    ]),

//                                Group::make()
//                                    ->schema([
                                        Select::make('link')
                                            ->label('Type de lien')
                                            ->options([
                                                'internal' => 'Internal link',
                                                'external' => 'External link',
                                            ])
                                            ->default('internal')
                                            ->native(false)
                                            ->live()
                                            ->required(),

                                        // Internal link fields (shown when link_type = 'internal')
                                        Group::make()
                                            ->columns(2)
                                            ->schema([
                                                TextInput::make('url')
                                                    ->label('URL interne')
                                                    ->placeholder('https://example.com')
                                                    ->rules('starts_with:/,'.config('app.url'))
                                                    ->required()
                                                    ->columnSpanFull(),

                                                Fieldset::make('Options')
                                                    ->schema([
                                                        Toggle::make('opens_in_new_tab')
                                                            ->label('Ouvrir dans un nouvel onglet')
                                                            ->helperText('Ajoute target="_blank" et rel="noopener noreferrer" automatiquement')
                                                            ->default(false)
                                                            ->inline(false),
                                                    ])
                                                    ->columns(3),
                                            ])
                                            ->visible(fn (Get $get) => $get('link') === 'internal'),

                                        // External link fields (shown when link_type = 'external')
                                        Group::make()
                                            ->schema([
                                                TextInput::make('url')
                                                    ->label('URL externe')
                                                    ->url()
                                                    ->placeholder('https://example.com')
                                                    ->required()
                                                    ->columnSpanFull(),

                                                Fieldset::make('Options')
                                                    ->schema([
                                                        Toggle::make('opens_in_new_tab')
                                                            ->label('Ouvrir dans un nouvel onglet')
                                                            ->helperText('Ajoute target="_blank" et rel="noopener noreferrer" automatiquement')
                                                            ->default(false)
                                                            ->inline(false),

                                                        Toggle::make('nofollow')
                                                            ->label('Nofollow')
                                                            ->helperText('Ajoute rel="nofollow" - indique aux moteurs de recherche de ne pas suivre ce lien')
                                                            ->default(false)
                                                            ->inline(false),

                                                        Toggle::make('sponsored')
                                                            ->label('Lien sponsorisé')
                                                            ->helperText('Ajoute rel="sponsored" - pour les liens publicitaires ou payants')
                                                            ->default(false)
                                                            ->inline(false),
                                                    ])
                                                    ->columns(3),
                                            ])
                                            ->visible(fn (Get $get) => $get('link') === 'external'),
//                                    ]),
                            ]),
                    ]),
            ]);
    }
}
