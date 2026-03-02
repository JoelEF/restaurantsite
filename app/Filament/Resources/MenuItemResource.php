<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Filament\Resources\MenuItemResource\RelationManagers;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Menu Items';
    protected static ?string $navigationGroup = 'Menu Beheer';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Algemeen')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->label('Categorie'),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Naam')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) =>
                                $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state) . '-' . \Illuminate\Support\Str::random(4)) : null
                            ),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull()
                            ->label('Omschrijving'),
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('€')
                            ->label('Prijs'),
                        Forms\Components\TextInput::make('allergens')
                            ->label('Allergenen'),
                        Forms\Components\TextInput::make('sort_order')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->label('Volgorde'),
                    ])->columns(2),
                Forms\Components\Section::make('Eigenschappen')
                    ->schema([
                        Forms\Components\Toggle::make('is_spicy')->label('Pittig'),
                        Forms\Components\Toggle::make('is_vegetarian')->label('Vegetarisch'),
                        Forms\Components\Toggle::make('is_popular')->label('Populair'),
                        Forms\Components\Toggle::make('is_active')->label('Actief')->default(true),
                    ])->columns(4),
                Forms\Components\Section::make('Afbeelding')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Productafbeelding')
                            ->image()
                            ->disk('public')
                            ->directory('menu-items'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->label(''),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable()
                    ->label('Categorie'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Naam'),
                Tables\Columns\TextColumn::make('price')
                    ->money('EUR')
                    ->sortable()
                    ->label('Prijs'),
                Tables\Columns\IconColumn::make('is_spicy')->boolean()->label('Pittig'),
                Tables\Columns\IconColumn::make('is_vegetarian')->boolean()->label('Vegetarisch'),
                Tables\Columns\IconColumn::make('is_popular')->boolean()->label('Populair'),
                Tables\Columns\IconColumn::make('is_active')->boolean()->label('Actief'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
