<?php

namespace App\Filament\Resources;

use App\Events\ProductUpdated;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Basic Product Information')
                ->schema([
                    Forms\Components\Select::make('category_id')
                        ->label('Category')
                        ->relationship('category', 'name')
                        ->required(),

                    Forms\Components\TextInput::make('name')
                        ->label('Product Name')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn($state, callable $set) =>
                            $set('slug', \Str::slug($state) . '-' . time())
                        ),

                    Forms\Components\Hidden::make('slug')->default(''),

                    Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->rows(4)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Pricing & Stock')
                ->schema([
                    Forms\Components\TextInput::make('price')
                        ->label('Price')
                        ->numeric()
                        ->prefix('LKR')
                        ->required(),

                    Forms\Components\TextInput::make('old_price')
                        ->label('Old Price (Optional)')
                        ->numeric()
                        ->prefix('LKR')
                        ->nullable(),

                    Forms\Components\TextInput::make('discount')
                        ->label('Discount %')
                        ->numeric()
                        ->default(0)
                        ->nullable(),

                    Forms\Components\TextInput::make('stock')
                        ->label('Stock')
                        ->numeric()
                        ->default(0)
                        ->required(),

                    Forms\Components\Toggle::make('is_featured')
                        ->label('Featured Product'),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ])
                ->columns(2),

            Forms\Components\Section::make('Product Image')
                ->schema([
                    Forms\Components\FileUpload::make('image')
                        ->label('Product Image')
                        ->image()
                        ->disk('public')
                        ->directory('products')
                        ->visibility('public')
                        ->nullable(),
                ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category.name')->label('Category')->sortable(),
                Tables\Columns\ImageColumn::make('image')->disk('public')->label('Image'),
                Tables\Columns\TextColumn::make('price')->money('LKR')->sortable(),
                Tables\Columns\TextColumn::make('old_price')->money('LKR')->sortable()->placeholder('—'),
                Tables\Columns\TextColumn::make('stock')->sortable(),
                Tables\Columns\IconColumn::make('is_featured')->boolean(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function ($record) {
                        try { event(new ProductUpdated($record, 'updated')); } catch (\Exception $e) {}
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}