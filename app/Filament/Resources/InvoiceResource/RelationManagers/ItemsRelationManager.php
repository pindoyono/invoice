<?php

namespace App\Filament\Resources\InvoiceResource\RelationManagers;

use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(function (Set $set, Get $get, $state) {
                        if ($state) {
                            $product = Product::find($state);
                            if ($product) {
                                $set('description', $product->name);
                                $set('unit', $product->unit);
                                $set('unit_price', $product->price);
                                $quantity = $get('quantity') ?: 1;
                                $set('amount', $product->price * $quantity);
                            }
                        }
                    }),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('notes')
                    ->label('Sub Description')
                    ->placeholder('e.g. Bulan Januari 2026')
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->default(1)
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (Set $set, Get $get, $state) {
                        $unitPrice = $get('unit_price') ?: 0;
                        $set('amount', $state * $unitPrice);
                    }),
                Forms\Components\TextInput::make('unit')
                    ->default('pcs')
                    ->required(),
                Forms\Components\TextInput::make('unit_price')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (Set $set, Get $get, $state) {
                        $quantity = $get('quantity') ?: 1;
                        $set('amount', $state * $quantity);
                    }),
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->disabled()
                    ->dehydrated(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->description(fn ($record) => $record->notes)
                    ->wrap(),
                Tables\Columns\TextColumn::make('quantity')
                    ->alignRight(),
                Tables\Columns\TextColumn::make('unit'),
                Tables\Columns\TextColumn::make('unit_price')
                    ->money('IDR')
                    ->alignRight(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR')
                    ->alignRight()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('IDR')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
