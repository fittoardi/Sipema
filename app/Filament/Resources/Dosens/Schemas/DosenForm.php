<?php

namespace App\Filament\Resources\Dosens\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class DosenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship(
                        'user',
                        'name',
                        modifyQueryUsing: fn (Builder $query): Builder => $query->where('role', 'dosen'),
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('nidn')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('nip'),
                FileUpload::make('image')
                    ->label('Foto')
                    ->image()
                    ->directory('dosen-images')
                    ->disk('public')
                    ->visibility('public')
                    ->imagePreviewHeight('200')
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeTargetWidth('200')
                    ->imageResizeTargetHeight('200'),
                Select::make('prodi_id')
                    ->relationship('prodi', 'nama_prodi')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}
