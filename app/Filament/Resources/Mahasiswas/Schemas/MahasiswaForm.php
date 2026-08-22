<?php

namespace App\Filament\Resources\Mahasiswas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class MahasiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship(
                        'user',
                        'name',
                        modifyQueryUsing: fn (Builder $query): Builder => $query->where('role', 'mahasiswa'),
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('nim')
                    ->required(),
                Select::make('prodi_id')
                    ->relationship('prodi', 'nama_prodi')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('angkatan')
                    ->required(),
            ]);
    }
}
