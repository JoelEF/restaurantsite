<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Bestellingen';
    protected static ?string $navigationGroup = 'Bestellingen';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Klantgegevens')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')->required()->label('Bestelnummer'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'In behandeling',
                                'confirmed' => 'Bevestigd',
                                'preparing' => 'In bereiding',
                                'ready' => 'Klaar',
                                'delivered' => 'Bezorgd',
                                'cancelled' => 'Geannuleerd',
                            ])->required()->label('Status'),
                        Forms\Components\TextInput::make('customer_name')->required()->label('Naam'),
                        Forms\Components\TextInput::make('customer_email')->email()->required()->label('E-mail'),
                        Forms\Components\TextInput::make('customer_phone')->tel()->required()->label('Telefoon'),
                        Forms\Components\Select::make('type')
                            ->options(['delivery' => 'Bezorging', 'pickup' => 'Afhalen'])
                            ->required()->label('Type'),
                    ])->columns(2),
                Forms\Components\Section::make('Betaling')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')->numeric()->prefix('€')->label('Subtotaal'),
                        Forms\Components\TextInput::make('delivery_fee')->numeric()->prefix('€')->label('Bezorgkosten'),
                        Forms\Components\TextInput::make('total')->numeric()->prefix('€')->label('Totaal'),
                        Forms\Components\Select::make('payment_status')
                            ->options(['pending' => 'Wachtend', 'paid' => 'Betaald', 'failed' => 'Mislukt'])
                            ->required()->label('Betaalstatus'),
                    ])->columns(2),
                Forms\Components\Textarea::make('delivery_address')->columnSpanFull()->label('Bezorgadres'),
                Forms\Components\Textarea::make('notes')->columnSpanFull()->label('Opmerkingen'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('order_number')->searchable()->label('Bestelnr.'),
                Tables\Columns\TextColumn::make('customer_name')->searchable()->label('Klant'),
                Tables\Columns\TextColumn::make('customer_phone')->label('Telefoon'),
                Tables\Columns\BadgeColumn::make('type')
                    ->colors(['primary' => 'delivery', 'success' => 'pickup'])
                    ->formatStateUsing(fn ($state) => $state === 'delivery' ? 'Bezorging' : 'Afhalen')
                    ->label('Type'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'confirmed',
                        'info' => 'preparing',
                        'success' => fn ($state) => in_array($state, ['ready', 'delivered']),
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending' => 'In behandeling',
                        'confirmed' => 'Bevestigd',
                        'preparing' => 'In bereiding',
                        'ready' => 'Klaar',
                        'delivered' => 'Bezorgd',
                        'cancelled' => 'Geannuleerd',
                        default => $state,
                    })->label('Status'),
                Tables\Columns\TextColumn::make('total')->money('EUR')->sortable()->label('Totaal'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d-m-Y H:i')->sortable()->label('Datum'),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
