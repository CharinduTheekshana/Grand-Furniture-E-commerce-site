<?php
namespace App\Filament\Resources\ProductResource\Pages;

use App\Events\ProductUpdated;
use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function getRedirectUrl(): string
    {
        return '/admin/products';
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (is_array($data['image'] ?? null)) {
            $data['image'] = array_values($data['image'])[0] ?? null;
        }
        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['name'] ?? 'product') . '-' . time();
        }
        return $data;
    }

    protected function afterSave(): void
    {
        try { event(new ProductUpdated($this->record, 'updated')); } catch (\Exception $e) {}
    }
}