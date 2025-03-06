<?php

namespace App\Filament\Resources\FakturResource\Pages;

use App\Filament\Resources\FakturResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Penjualan;

class CreateFaktur extends CreateRecord
{
    protected static string $resource = FakturResource::class;

    protected function afterCreate(): void {
        Penjualan::create([
            'tanggal' => $this->record->tanggal,
            'kode' => 'PEN-' . now()->format('YmdHis'),
            'faktur_id' => $this->record->id,
            'customer_id' => $this->record->customer_id,
            'total' => $this->record->grand_total,
        ]);
    }
}
