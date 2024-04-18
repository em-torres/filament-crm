<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                                ->image(),
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
                                ->required(),
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
                        ]),
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
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mobile')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_website')
                    ->searchable(),
                Tables\Columns\TextColumn::make('business_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_size')
                    ->searchable(),
                Tables\Columns\TextColumn::make('temperature')
                    ->searchable(),
                Tables\Columns\TextColumn::make('photo')
                    ->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
