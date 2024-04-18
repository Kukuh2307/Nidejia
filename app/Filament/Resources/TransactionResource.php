<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function canCreate(): bool
    {
        return false;
    }

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
                Tables\Columns\TextColumn::make('user.name')->searchable()->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('listing.title')->searchable(),
                Tables\Columns\TextColumn::make('start_date')->sortable(),
                Tables\Columns\TextColumn::make('end_date'),
                Tables\Columns\TextColumn::make('total_days'),
                Tables\Columns\TextColumn::make('total_price')->money('USD')->weight(FontWeight::Bold)->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'accepted' => 'info',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'pending',
                        'accepted' => 'accepted',
                        'rejected' => 'rejected',
                    ])
                    ->attribute('status')
            ])
            ->actions([
                Action::make('accept')
                    ->button()
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Transaction $transaction) {
                        $transaction->update(['status' => 'accepted']);
                        Notification::make()->success()->title('Transaction Accepted')->body('Your transaction has been accepted.')->icon('heroicon-o-check-circle')->send();
                    })
                    ->hidden(function (Transaction $transaction) {
                        return $transaction->status !== 'pending';
                    }),
                Action::make('reject')
                    ->button()
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Transaction $transaction) {
                        $transaction->update(['status' => 'rejected']);
                        Notification::make()->success()->title('Transaction Rejected')->body('Your transaction has been rejected.')->icon('heroicon-o-x-circle')->send();
                    })
                    ->hidden(function (Transaction $transaction) {
                        return $transaction->status !== 'pending';
                    })
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
