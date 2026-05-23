<?php
namespace App\Filament\Resources\ProductResource\Pages;

use App\Events\ProductUpdated;
use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function getRedirectUrl(): string
    {
        return '/admin/products';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Filament FileUpload returns array — extract filename
        if (is_array($data['image'] ?? null)) {
            $data['image'] = array_values($data['image'])[0] ?? null;
        }
        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['name'] ?? 'product') . '-' . time();
        }
        return $data;
    }

    protected function afterCreate(): void
    {
        try { event(new ProductUpdated($this->record, 'created')); } catch (\Exception $e) {}
    }
}