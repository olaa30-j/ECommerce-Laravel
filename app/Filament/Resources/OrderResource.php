<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'E-Commerce';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->columnSpan(2),

                Forms\Components\Repeater::make('cartItems')
                    ->relationship('cartItems')
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'name')
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                                $set('price', optional(Product::find($state))->price ?? 0)
                            ),

                        Forms\Components\TextInput::make('quantity')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $price = $get('price') ?? 0;
                                $set('total_item_price', $price * $state);
                                self::updateTotalPrice($set, $get);
                            }),

                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->disabled(),

                        Forms\Components\TextInput::make('total_item_price')
                            ->label('Total Item Price')
                            ->numeric()
                            ->disabled(),
                    ])
                    ->columns(4)
                    ->columnSpan('full')
                    ->reactive() 
                    ->afterStateUpdated(fn (callable $set, callable $get) => self::updateTotalPrice($set, $get)),  

                Forms\Components\TextInput::make('total_price')
                    ->label('Total Price')
                    ->numeric()
                    ->default(0)
                    ->disabled()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set, callable $get) => self::updateTotalPrice($set, $get))  
                    ->columnSpan(2),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => '🟡 Pending',
                        'completed' => '🟢 Completed',
                        'canceled' => '🔴 Canceled',
                    ])
                    ->required()
                    ->columnSpan(2),
            ])
            ->columns(2);
    }

    private static function updateTotalPrice(callable $set, callable $get)
    {
        $cartItems = collect($get('cartItems') ?? []);
        $total = $cartItems->sum(fn ($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0));
        $set('total_price', $total);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->searchable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Price')
                    ->sortable()
                    ->money('USD'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'pending' => 'warning',
                        'completed' => 'success',
                        'canceled' => 'danger',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'canceled' => 'Canceled',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['cartItems.product']));
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
