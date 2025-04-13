@extends('layouts.pdf')
@section('title', 'Reporte de Cierre de Caja')
@section('content')
    <div class="title">{{ __('Cash closing') }}</div>
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 33%; padding: 5px; border: none;"><strong>{{ __('User') }}:</strong><br>
                {{ $cashDrawer->user->name }}
            </td>
            <td style="width: 33%; padding: 5px; border: none;"><strong>{{ __('Date of open cash') }}:</strong><br>
                {{ $cashDrawer->start_time }}
            </td>
            <td style="width: 33%; padding: 5px; border: none;"><strong>{{ __('Cash closing date') }}:</strong><br>
                {{ $cashDrawer->end_time ?? __('Unclosed cash register') }}
            </td>
        </tr>

        <tr>
            <td style="padding: 5px; border: none;"><strong>{{ __('Amount recorded by the system') }}:</strong><br>
                {{ $currency }}. {{ $cashDrawer->final_money }}
            </td>
            <td style="padding: 5px; border: none;"><strong>{{ __('Total amount entered into the system') }}:</strong><br>
                {{ $currency }}. {{ $cashDrawer->total_calculated }}
            </td>
            <td style="padding: 5px; border: none;"><strong>{{ __('Total amount declared by the user') }}:</strong><br>
                {{ $currency }}. {{ $cashDrawer->total_calculated }}
            </td>
        </tr>

        <tr>
            <td style="padding: 5px; border: none;"><strong>{{ __('Difference') }}:</strong><br>
                {{ $currency }}. {{ $cashDrawer->difference }}
            </td>
            <td style="padding: 5px; border: none;"><strong>{{ __('Cash status') }}:</strong><br>
                {{ __($cashDrawer->status) }}
            </td>
            <td style="padding: 5px; border: none;">
                <strong>{{ __('Banknoting of the amount declared in cash') }}:</strong><br>
                @php
                    $denominations = json_decode($cashDrawer->denominations, true);
                @endphp
                <ul style="margin: 0; padding-left: 15px;">
                    @if ($denominations && is_array($denominations))
                        @foreach ($denominations as $denomination => $quantity)
                            @if ($quantity)
                                <li><strong>{{ $currency }}{{ str_replace('_', '', $denomination) }}:</strong>
                                    {{ $quantity }}</li>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </td>
        </tr>
    </table>

    @if ($cashResgistersBoolean)
        <table style="width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 30px;" border="1"
            cellpadding="5">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Detail') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Total Registration') }}</th>
                    <th>{{ __('Cash on Hand') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cashRegisters as $cashRegister)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($cashRegister->registration_date)->format('d-m-Y (H:i)') }}</td>
                        <td>
                            @if ($cashRegister->vehicle_id != null)
                                Movil: {{ $cashRegister->vehicle_id }} -
                                {{ $cashRegister->itemsCashFlow->name }}
                            @else
                                {{ $cashRegister->itemsCashFlow->name }}
                            @endif
                        </td>
                        <td> {{ __($cashRegister->transaction_type_income_expense) }}</td>
                        <td>
                            {{ __($cashRegister->payment_status) }}
                            @if ($cashRegister->payment_type != null)
                                <br>
                                {{ '(' . __($cashRegister->payment_type) . ')' }}
                            @endif
                        </td>
                        <td>
                            Bs.
                            {{ $cashRegister->transaction_type_income_expense != 'income' ? '-' : '' }}{{ number_format($cashRegister->amount, 2) }}
                        </td>
                        <td>
                            Bs.
                            {{ $cashRegister->payment_type == 'cash' ? ($cashRegister->transaction_type_income_expense != 'income' ? '-' : '') . number_format($cashRegister->amount, 2) : '0.00' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">
                            {{ __('No records were found for today. You can create one now to start recording information.') }}
                        </td>
                    </tr>
                @endforelse

                <tr style="font-weight: bold; background-color: #f9f9f9;">
                    <td colspan="4" style="text-align: right;">{{ __('Total Revenues') }}</td>
                    <td>Bs. {{ number_format($totalRegistration, 2) }}</td>
                    <td>Bs. {{ number_format($cashOnHand, 2) }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    @foreach ($groupedCashFlow as $transactionType => $itemsGroup)
        <p style="font-weight: bold; margin-bottom: 4px;">
            • {{ ucfirst($transactionType) === 'Income' ? 'Ingresos' : 'Gastos' }}
        </p>

        <ul style="margin-left: 20px; margin-bottom: 12px;">
            @foreach ($itemsGroup as $item)
                <li style="margin-bottom: 4px;">
                    {{ $item->itemsCashFlow->name ?? 'Item #' . $item->items_id }} |
                    Bs. {{ number_format($item->total_amount, 2) }} |
                    {{ $item->total_count }} {{ Str::plural('registro', $item->total_count) }}
                </li>
            @endforeach
        </ul>
    @endforeach
@endsection
