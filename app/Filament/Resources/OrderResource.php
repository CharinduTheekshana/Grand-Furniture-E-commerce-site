<?php
namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Customer Information')
                ->schema([
                    Forms\Components\TextInput::make('name')->label('Customer Name')->disabled(),
                    Forms\Components\TextInput::make('email')->disabled(),
                    Forms\Components\TextInput::make('phone')->disabled(),
                    Forms\Components\TextInput::make('address')->disabled()->columnSpanFull(),
                    Forms\Components\TextInput::make('total')->label('Total (LKR)')->disabled(),
                    Forms\Components\Select::make('status')
                        ->options([
                            'pending'    => 'Pending',
                            'paid'       => 'Paid',
                            'processing' => 'Processing',
                            'completed'  => 'Completed',
                            'cancelled'  => 'Cancelled',
                        ])->required(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Order #')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Customer')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('total')->money('LKR')->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match($state) {
                        'pending'    => 'warning',
                        'paid'       => 'info',
                        'processing' => 'primary',
                        'completed'  => 'success',
                        'cancelled'  => 'danger',
                        default      => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')->label('Date')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view_items')
                    ->label('Items')
                    ->icon('heroicon-o-shopping-bag')
                    ->modalHeading(fn(Order $record) => 'Order #' . $record->id . ' — Items')
                    ->modalContent(fn(Order $record) => view('filament.order-items', [
                        'order' => $record->load('items.product')
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}