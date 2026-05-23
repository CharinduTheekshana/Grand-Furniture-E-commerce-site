<?php
namespace App\Filament\Resources;
use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(function($state, callable $set, $record) {
                    $slug = \Str::slug($state);
                    // Add ID to make unique when editing
                    if ($record) {
                        $slug = $slug . '-' . $record->id;
                    } else {
                        $slug = $slug . '-' . time();
                    }
                    $set('slug', $slug);
                }),
            Forms\Components\TextInput::make('slug')
                ->required()
                ->visibleOn('create'),
            Forms\Components\RichEditor::make('content')->columnSpanFull()->required(),
            Forms\Components\FileUpload::make('image')
                ->label('Blog Image')
                ->image()
                ->disk('public')
                ->directory('blogs')
                ->nullable()
                ->dehydrateStateUsing(fn($state) => is_array($state) ? array_values($state)[0] ?? null : $state),
            Forms\Components\Toggle::make('is_published')->label('Published')->default(false),
        ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\IconColumn::make('is_published')->boolean()->label('Published'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }
    public static function getRelations(): array { return []; }
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit'   => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
