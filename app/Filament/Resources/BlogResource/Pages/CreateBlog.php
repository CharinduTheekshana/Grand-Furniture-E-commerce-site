<?php
namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlog extends CreateRecord
{
    protected static string $resource = BlogResource::class;

    protected function getRedirectUrl(): string
    {
        return '/admin/blogs';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (is_array($data['image'] ?? null)) {
            $data['image'] = array_values($data['image'])[0] ?? null;
        }
        if (empty($data['slug'])) {
            $data['slug'] = \Str::slug($data['title'] ?? 'blog') . '-' . time();
        }
        return $data;
    }
}