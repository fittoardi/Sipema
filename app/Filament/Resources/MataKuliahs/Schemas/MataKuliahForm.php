<?php

namespace App\Filament\Resources\MataKuliahs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MataKuliahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('prodi_id')
                    ->relationship('prodi', 'nama_prodi')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('kode')
                    ->required(),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('sks')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(10),
            ]);
    }
}
