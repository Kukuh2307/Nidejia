<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Support\Enums\FontWeight;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

class WaitingTransaction extends BaseWidget
{
    protected static ?int $sort = 3;
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()->where('status', 'pending')
            )
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
            ]);
    }
}
