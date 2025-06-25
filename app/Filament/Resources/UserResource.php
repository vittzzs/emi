<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\DocumentType;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Utils\IdentificationService;
use App\Services\Utils\PasswordGenerator;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tapp\FilamentCountryCodeField\Forms\Components\CountryCodeSelect;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation-panel.Administration');
    }

    public static function getNavigationLabel(): string
    {
        return __('users.navegation_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('users.navegation_label');
    }

    public static function getModelLabel(): string
    {
        return __('users.navegation_label_singel');
    }

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('users.User_information'))
                    ->icon('heroicon-m-user')
                    ->collapsible()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('users.Name user'))
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->translateLabel()
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->required(),
                    ]),

                Section::make(__('users.Security'))
                    ->icon('heroicon-m-adjustments-vertical')
                    ->collapsible()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->translateLabel()
                            ->password()
                            ->visibleOn('create')
                            ->revealable()
                            ->required()
                            ->prefixAction(
                                Forms\Components\Actions\Action::make('generate')
                                    ->icon('heroicon-m-key')
                                    ->action(function ($set) {
                                        $set('password', app(PasswordGenerator::class)->generate());
                                    })
                            )
                            ->maxLength(255),
                        Forms\Components\Select::make('roles')
                            ->translateLabel()
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),
                    ]),

                Section::make(__('users.Profile'))
                    ->icon('heroicon-m-adjustments-vertical')
                    ->collapsible()
                    ->live()
                    ->columns(1)
                    ->schema([
                        Forms\Components\Repeater::make('profile')
                            ->translateLabel()
                            ->relationship('profile')
                            ->deletable(false)
                            ->schema([
                                Forms\Components\Select::make('document_type_id')
                                    ->translateLabel()
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->default(2)
                                    ->relationship('documentType', 'name')
                                    ->required(),
                                Forms\Components\TextInput::make('document_number')
                                    ->translateLabel()
                                    ->required()
                                    ->disabled(fn (callable $get) => $get('is_disabled') ?? false)
                                    ->afterStateHydrated(function (mixed $component, mixed $state, callable $set, string $context) {
                                        if ($context === 'edit') {
                                            $set('is_disabled', true);
                                        }
                                    })
                                    ->prefixAction(
                                        Forms\Components\Actions\Action::make('toggleEdit')
                                            ->icon(fn (callable $get) => $get('is_disabled') ? 'heroicon-m-lock-closed' : 'heroicon-m-lock-open')
                                            ->tooltip(fn (callable $get) => $get('is_disabled') ? 'Habilitar edición' : 'Deshabilitar edición')
                                            ->action(function (callable $set, callable $get) {
                                                $set('is_disabled', ! $get('is_disabled'));
                                            })
                                            ->visible(fn (string $context): bool => $context === 'edit')
                                    )
                                    ->live()
                                    ->unique(ignoreRecord: true)
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('generate')
                                            ->icon('heroicon-m-magnifying-glass')
                                            ->action(function (?string $state, callable $set, callable $get) {
                                                app(IdentificationService::class)->setFullName($state, $set);
                                            })
                                            ->visible(
                                                fn (callable $get, string $context): bool => in_array($get('document_type_id'), DocumentType::dniRuc()) &&
                                                    $context !== 'view' &&
                                                    ($context !== 'edit' || ! ($get('is_disabled') ?? true))
                                            ),
                                    )->extraAttributes(fn (Forms\Components\TextInput $component) => [
                                        'wire:keydown.enter.prevent' => "mountFormComponentAction('{$component->getStatePath()}', 'generate')",
                                    ])
                                    ->maxLength(11),
                                Forms\Components\TextInput::make('full_name')
                                    ->translateLabel()
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->minItems(1)
                            ->columns(3)
                            ->addable()
                            ->maxItems(1),
                        Forms\Components\Repeater::make('phones')
                            ->translateLabel()
                            ->relationship('phones')
                            ->collapsible()
                            ->schema([
                                Forms\Components\TextInput::make('phone_type')
                                    ->translateLabel()
                                    ->maxLength(50)
                                    ->default('Personal')
                                    ->nullable(),
                                CountryCodeSelect::make('country_code')
                                    ->default('+51')
                                    ->translateLabel(),
                                Forms\Components\TextInput::make('phone_number')
                                    ->translateLabel()
                                    ->tel()
                                    ->maxLength(15),
                            ])
                            ->addable()
                            ->maxItems(3)
                            ->columns(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->translateLabel()
                    ->searchable()
                    ->default('Sin Roles')
                    ->badge(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()->native(false),
            ])
            ->actions([
                Tables\Actions\Action::make('changePassword')
                    ->label('Nueva Clave')
                    ->icon('heroicon-o-lock-closed')
                    ->visible(fn () => app(AuthService::class)->IsSuperUser())
                    ->modalWidth('md')
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->translateLabel()
                            ->disabled()
                            ->default(fn (User $record) => $record->name),
                        Forms\Components\TextInput::make('email')
                            ->translateLabel()
                            ->disabled()
                            ->default(fn (User $record) => $record->email),
                        Forms\Components\TextInput::make('password')
                            ->translateLabel()
                            ->password()
                            ->required()
                            ->maxLength(255)
                            ->revealable()
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $set('password_confirmation', $state);
                            })
                            ->prefixAction(
                                Forms\Components\Actions\Action::make('generate')
                                    ->icon('heroicon-m-key')
                                    ->action(function ($set) {
                                        $password = app(PasswordGenerator::class)->generate();
                                        $set('password', $password);
                                        $set('password_confirmation', $password);
                                    })
                            ),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->translateLabel()
                            ->password()
                            ->required()
                            ->revealable()
                            ->live()
                            ->maxLength(255)
                            ->same('password'),
                    ])
                    ->action(function (User $record, array $data) {
                        $record->update(['password' => \Illuminate\Support\Facades\Hash::make($data['password'])]);
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
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
