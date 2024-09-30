<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->required()
                ->label('Product Name'),
            TextInput::make('price')
                ->required()
                ->numeric()
                ->label('Price'),
            Textarea::make('description')
                ->label('Description'),
            Toggle::make('status')
                ->default(true)
                ->label('Active'),
            FileUpload::make('images') 
                ->label('Product Images') 
                ->multiple() 
                ->preserveFilenames() 
                ->required() 
                ->maxSize(5 * 1024) // 5 MB
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg']) 
                ->disk('public') 
                ->directory('products/images') 
                ->visibility('public'), 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Product Name'),
                Tables\Columns\TextColumn::make('price')->label('Price'),
                Tables\Columns\BooleanColumn::make('status')->label('Active'),
                Tables\Columns\ImageColumn::make('images')->label('Images')->disk('public'),
            ])
            ->filters([
                // Add any filters if necessary
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
