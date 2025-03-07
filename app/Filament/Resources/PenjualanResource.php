<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Filament\Resources\PenjualanResource\RelationManagers;
use App\Models\Penjualan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static?string $navigationGroup = 'Membuat Faktur';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->label('No Faktur')
                    ->searchable()
                    ->sortable(),
                textColumn::make('customer.nama')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                    TextColumn::make('total')
                    ->searchable()
                    ->prefix('Rp. ')
                    ->numeric(0, ',', '.')
                    ->label('Total'),
                TextColumn::make('faktur_detail_count')
                    ->label('Jumlah Item')
                    ->getStateUsing(function ($record) {
                        return $record->faktur?->faktur_detail()->sum('qty') ?? 0;
                    })
                    ->sortable(),
                TextColumn::make('status')
                    ->color(fn (string $state): string => match ($state) {
                        '0' => 'danger', // pending
                        '1' => 'success', // paid
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '0' => 'Belum Lunas', // pending
                        '1' => 'Lunas', // paid
                    })
                    ->badge()
                    ->label('Status')
                    ->sortable(),
                
            ])
            //* Konfigurasi Bila data kosong dan action menambah faktur baru
            ->emptyStateHeading('Tidak ada data penjualan')
            ->emptyStateDescription('Mulai dengan menambahkan data penjualan baru')
            ->emptyStateActions([
                    Action::make('Tambah Data Penjualan')
                        ->icon('heroicon-o-plus-circle')
                        ->url(route('filament.admin.resources.fakturs.create'))
                        //! route bisa di cek pada php artisan route:list
                    ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
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
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}
