<?php

namespace App\Filament\Resources\Kelas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KelasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('mata_kuliah_id')
                    ->relationship('mataKuliah', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('dosen_id')
                    ->relationship('dosen', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('tahun_akademik')
                    ->required(),
                Select::make('semester')
                    ->options([
                        'Ganjil' => 'Ganjil',
                        'Genap' => 'Genap',
                    ])
                    ->required(),
                Select::make('mahasiswas')
                    ->relationship('mahasiswas', 'nim')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->label('Peserta Kelas'),
            ]);
    }
}
