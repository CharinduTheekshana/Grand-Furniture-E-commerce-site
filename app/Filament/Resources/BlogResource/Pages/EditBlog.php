<?php
namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBlog extends EditRecord
{
    protected static string $resource = BlogResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function getRedirectUrl(): string
    {
        return '/admin/blogs';
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (is_array($data['image'] ?? null)) {
            $data['image'] = array_values($data['image'])[0] ?? null;
        }
        unset($data['slug']);
        return $data;
    }
}