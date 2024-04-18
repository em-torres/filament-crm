<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Tabs::make('Label')
                ->tabs([
                    Tabs\Tab::make(__('Personal Info'))
                        ->schema([
                            ImageEntry::make('photo'),
                            TextEntry::make('first_name')
                                ->label(__('Name'))
                                ->weight(FontWeight::Bold)
                                ->size(TextEntry\TextEntrySize::Large)
                                ->formatStateUsing(fn($state, $record) => $record->first_name . ' ' . $record->last_name),
                            TextEntry::make('email')
                                ->copyable(),
                            TextEntry::make('phone'),
                            TextEntry::make('mobile'),
                            TextEntry::make('email'),
                            TextEntry::make('linkedin')
                                ->suffixAction(
                                    Action::make('Open LinkedIn Profile')
                                        ->icon('heroicon-o-link')
                                        ->url(fn($record) => $record->linkedin)
                                ),
                            TextEntry::make('active')
                                ->label(__('Status'))
                                ->badge()
                                ->color(fn($state) => $state ? 'success' : 'gray')
                                ->formatStateUsing(fn($state) => $state ? 'Active' : 'Inactive'),
                        ]),
                    Tabs\Tab::make(__('Business Info'))
                        ->schema([
                            TextEntry::make('company'),
                            TextEntry::make('title'),
                            TextEntry::make('role'),
                            TextEntry::make('company_website'),
                            TextEntry::make('business_details'),
                            TextEntry::make('business_type'),
                            TextEntry::make('company_size'),
                            TextEntry::make('temperature'),
                        ]),
                    Tabs\Tab::make(__('Notes'))
                        ->schema([
                            TextEntry::make('notes'),
                            TextEntry::make('referrals'),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make([
                    'md' => 3,
                ])->schema([
                    Forms\Components\Section::make()->schema([
                        Forms\Components\Section::make(__('Personal Info'))->schema([
                            Forms\Components\FileUpload::make('photo')
                                ->image()
                                ->maxSize('1024')
                                ->getUploadedFileNameForStorageUsing(
                                    function($file, $get): string {
                                        return "{$get('first_name')}{$get('last_name')}"
                                                    . Carbon::now()->format('Ymd')
                                                    . "."
                                                    . $file->getClientOriginalExtension();
                                    }
                                ),
                            Forms\Components\TextInput::make('first_name')
                                ->string()
                                ->minLength(2)
                                ->maxLength(255)
                                ->required(),
                            Forms\Components\TextInput::make('last_name')
                                ->string()
                                ->minLength(2)
                                ->maxLength(255)
                                ->required(),
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->required(),
                            Forms\Components\TextInput::make('phone')
                                ->tel(),
                            Forms\Components\TextInput::make('mobile'),
                            Forms\Components\TextInput::make('linkedin')
                                ->columnSpanFull(),
                            Forms\Components\Toggle::make('active')
                                ->required()
                                ->visibleOn('edit'),
                        ]),

                        Forms\Components\Section::make(__('Business Info'))->schema([
                            Forms\Components\TextInput::make('title'),
                            Forms\Components\TextInput::make('company'),
                            Forms\Components\TextInput::make('role'),
                            Forms\Components\TextInput::make('company_website'),
                            Forms\Components\Textarea::make('business_details')
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('business_type'),
                            Forms\Components\Select::make('company_size')
                                ->options([
                                    'small' => __('small'),
                                    'mid' => __('medium'),
                                    'big' => __('large'),
                                ]),
                            Forms\Components\Select::make(__('temperature'))
                                ->options([
                                    'cold' => __('cold'),
                                    'warm' => __('warm'),
                                    'hot' => __('hot'),
                                ]),
                        ])
                            ->disabledOn('create'),
                    ])->columnSpan(2),

                    Forms\Components\Section::make(__('Notes'))->schema([
                        Forms\Components\Textarea::make('notes')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('referrals')
                            ->columnSpanFull(),
                    ])->columnSpan(1),
                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\ImageColumn::make('photo')
                        ->circular(),
                    Tables\Columns\TextColumn::make('first_name')
                        ->label(__('Name'))
                        ->formatStateUsing(fn($record): string => "{$record->first_name} {$record->last_name}")
                        ->searchable(['first_name', 'last_name']),
                    Tables\Columns\TextColumn::make('email')
                        ->visibleFrom('md')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('phone')
                        ->toggleable()
                        ->searchable(),
                    Tables\Columns\TextColumn::make('mobile')
                        ->searchable(),
                ])->visibleFrom('md'),

                Tables\Columns\Layout\Split::make([
                    Tables\Columns\TextColumn::make('title')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('company')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('role')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('company_website')
                        ->toggleable()
                        ->searchable(),
                    Tables\Columns\TextColumn::make('business_type')
                        ->toggleable()
                        ->searchable(),
                    Tables\Columns\TextColumn::make('company_size')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('temperature')
                        ->searchable(),
                    Tables\Columns\IconColumn::make('active')
                        ->boolean(),
                ])->collapsible()->visibleFrom('lg'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->contentGrid([
                'md' => 1,
                'xl' => 2
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
            'view' => Pages\ViewClient::route('/{record}'),
        ];
    }
}
