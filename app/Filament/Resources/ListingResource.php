<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Listing;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ListingResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ListingResource\RelationManagers;
use Filament\Tables\Filters\TrashedFilter;

class ListingResource extends Resource
{
    protected static ?string $model = Listing::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->required()->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))->live(debounce: 250),
                TextInput::make('slug')->disabled(),
                Textarea::make('description')->required(),
                TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                TextInput::make('sqft')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('wifi_speed')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('max_person')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('price_per_day')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('full_suppport_available')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('gym_area_available')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('mini_cafe_available')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('cinema_available')
                    ->required()
                    ->numeric()
                    ->default(0),
                FileUpload::make('attachments')
                    ->directory('listings')
                    ->image()
                    ->openable()
                    ->multiple()->reorderable()->appendFiles()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->weight(FontWeight::Bold)->sortable()->isSearchable(),
                TextColumn::make('sqft'),
                TextColumn::make('wifi_speed'),
                TextColumn::make('max_person'),
                TextColumn::make('price_per_day')->weight(FontWeight::Bold)->format(fn ($value) => 'Rp' . number_format($value, 0, ',', '.'))->sortable(),
                TextColumn::make('created_at')
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make()->color('warning'),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
            'index' => Pages\ListListings::route('/'),
            'create' => Pages\CreateListing::route('/create'),
            'edit' => Pages\EditListing::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
