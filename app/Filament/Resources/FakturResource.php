<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FakturResource\Pages;
use App\Filament\Resources\FakturResource\RelationManagers;
use App\Models\Faktur;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;

use function Laravel\Prompts\text;

class FakturResource extends Resource
{
    protected static ?string $model = Faktur::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static?string $navigationLabel = 'Faktur';

    protected static?string $navigationGroup = 'Membuat Faktur';

    public static function form(Form $form): Form
    {
        return $form
        ->columns(2)
        ->schema([
                Section::make('Keterangan')
                    ->description('Masukkan Keterangan Faktur')
                    ->schema([
                        TextInput::make('no_faktur')
                            ->label('No Faktur')
                            ->default(fn () => 'FAK-' . now()->format('YmdHis'))
                            ->readonly()
                            ->required(),
                        DatePicker::make('tanggal')
                            ->label('Tanggal')
                            ->default(fn () => now()->toDateString())
                            ->required(),
                        Select::make('customer_id')
                            ->label('Customer ID')
                            ->relationship('customer', 'nama')
                            ->required(),
                        TextInput::make('total')
                            ->label('Total')
                            ->required(),
                        TextInput::make('keterangan')
                            ->label('Keterangan')
                            ->required(),
                        TextInput::make('charge')
                            ->label('Charge')
                            ->required(),
                        TextInput::make('nominal_charge')
                            ->label('Nominal')
                            ->required(),
                        ]),
                
                section::make('Pembelian')
                    ->schema([
                        //
                    Repeater::make('faktur_detail')
                        ->columns(4)
                        ->addActionLabel('Tambah Barang')
                        ->relationship() // Explicitly define the relationship name
                        ->schema([
                            Select::make('barang_id')
                                ->label('Barang ID')
                                ->relationship('barang', 'nama_barang')
                                ->createOptionForm([
                                    TextInput::make('nama_barang')
                                        ->required(),
                                    TextInput::make('harga')
                                        ->numeric()
                                        ->required(),
                                    TextInput::make('kode_barang')
                                        ->required(),
                                ])
                                ->live()
                                ->afterStateUpdated(function ($state, Forms\Set $set , Forms\Get $get) {
                                    $barang = \App\Models\Barang::find($state);
                                    $qty = $get('qty');
                                    if ($barang) {
                                        $set('harga', $barang->harga);
                                        $set('subtotal', $barang->harga * $qty);
                                        //UPDATE HARGA SUBTOTAL
                                    }
                                })
                                ->required(),
                            TextInput::make('qty')
                                ->label('Qty')
                                ->default(1)
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                    $harga = $get('harga');
                                    $set('subtotal', $state * $harga);
                                    //UPDATE HARGA SUBTOTAL BILA QTY DIUBAH
                                }),
                            TextInput::make('harga')
                                ->label('Harga')
                                ->prefix('Rp. ')
                                ->numeric(0, ',', '.')
                                ->disabled()
                                ->dehydrated(),
                            textInput::make('diskon')
                                ->label('Diskon')
                                ->prefix('Rp. ')
                                ->numeric(0, ',', '.')
                                ->default(0)
                                ->required(),
                            TextInput::make('subtotal')
                                ->label('Subtotal')
                                ->prefix('Rp. ')
                                ->numeric(0, ',', '.')
                                ->disabled()
                                ->dehydrated(),
                            
                        ]),
                    TextInput::make('grand_total')
                    ->placeholder(function (Forms\Set $set, Forms\Get $get) {
                        $grandTotal = collect($get ('faktur_detail'))->pluck('subtotal')->sum();

                        if ($grandTotal) {
                            $set('grand_total', $grandTotal);
                        } else {
                            $set('grand_total', 0);
                        }
                        return $grandTotal;
                        //MENGHITUNG GRAND TOTAL SEKALIGUS
                        })
                    ->label('Grand Total')
                    ->prefix('Rp. ')
                    ->numeric(0, ',', '.')
                    ->default(0)
                    ->readonly()
                    ->dehydrated(),
                    ]),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('no_faktur')
                    ->label('No Faktur')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->searchable()
                    ->sortable(),
                textcolumn::make('customer.nama')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                textcolumn::make('grand_total')
                    ->label('Total')
                    ->sortable()
                    ->numeric(0, ',', '.'), //MENGUBAH FORMAT RUPIAH Tanpa Desimal
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListFakturs::route('/'),
            'create' => Pages\CreateFaktur::route('/create'),
            'edit' => Pages\EditFaktur::route('/{record}/edit'),
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
