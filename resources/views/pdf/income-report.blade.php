<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today's Report</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin-bottom: 20px;
        }

        header .company-info {
            margin-left: 20px;
        }

        header img {
            max-width: 100px;
        }

        header h1 {
            font-size: 24px;
            margin: 0;
        }

        header p {
            font-size: 14px;
            color: #777;
            margin: 5px 0;
        }

        .report-title {
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .report-date {
            text-align: center;
            font-size: 12px;
            margin-bottom: 20px;
            color: #555;
        }

        .report-date p {
            margin: 0; /* Elimina los márgenes */
            display: block; /* Asegura que el <p> es un elemento bloque */
            text-align: center; /* Aunque ya está en .report-date, asegúrate de que el <p> también lo tenga */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 8px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 6px 2px;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
            font-weight: bold;
            text-align: center;
        }

        .summary {
            text-align: right;
            margin-top: 10px;
        }

        .summary p {
            font-size: 10px;
            margin: 5px 0;
        }

        .summarytop {
            margin-top: 10px;
        }

        .summarytop p {
            font-size: 10px;
            margin: 5px 0;
        }

        .summarytop h4 {
            font-size: 14px;
            margin: 5px 0;
        }

        .summarytop h3 {
            font-size: 14px;
            margin: 5px 0;
        }

        footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }

        /* Ensure consistent display for print */
        @media print {
            body {
                margin: 0;
            }

            .container {
                padding: 0;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <!--<img src="logo.png" alt="Company Logo">-->
        <div class="company-info">
            <p><strong>{{env('APP_NAME')}}</strong></p>
            <p><strong>{{__('Address')}}:</strong> {{env('EMPRESA_REFERENCIA')}}, {{env('EMPRESA_CODIGO_POSTAL')}},
                {{env('EMPRESA_CIUDAD')}},
                {{env('EMPRESA_ESTADO')}}, {{env('EMPRESA_PAIS')}}</p>
            <p>{{env('EMPRESA_CORREO')}} | {{env('EMPRESA_TELEFONO')}}</p>
        </div>
    </header>

    <div class="report-title">{{__('Reporting of all revenues')}}</div>

    <div class="report-date">
        <p><strong>{{__('Movements')}}: </strong>{{$startDate->format('d/m/Y')}} @if($endDate != null) -
            {{$endDate->format('d/m/Y')}}@endif</p>
    </div>

    <table>
        <thead>
        <tr>
            <th>{{ __('Date') }}</th>
            <th>{{ __('Movil') }}</th>
            <th>{{ __('Bank Account') }}</th>
            <th>{{ __('Items') }}</th>
            <th>{{ __('Detail') }}</th>
            <th>{{ __('Amount') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($items as $item)
        <tr>
            <td>{{ $item->created_at->format('d-m-Y H:i:s') }}</td>
            <td>{{ $item->vehicle_id ? str_pad($item->vehicle_id,3,0, STR_PAD_LEFT) : 'Sin movil asociado'}}</td>
            <td>{{ $item->banks ? $item->banks->bank_name : 'Sin banco asociado' }}</td>
            <td>{{ __($item->transaction_type_income_expense) }}</td>
            <td>{{ __($item->detail) }}</td>
            <td>{{ $item->banks ? $item->banks->currency_type.'.' : '' }} {{number_format($item->amount, 2) }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                {{ __('No records found. Select other dates.') }}
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>

    {{-- Totales generales --}}
    <div class="summary">
        <hr>
        <p><strong>{{ __('Total Income') }}:</strong> Bs. {{ number_format($totalIncomes, 2) }}</p>
    </div>

    <div class="summarytop">
        <h3>{{ __('Summary by Items') }}</h3>

        {{-- Totales de Income --}}
        @if ($items->where('transaction_type_income_expense', 'income')->isNotEmpty())

        {{-- 1️⃣ Agrupar por Item --}}
        @foreach($items->where('transaction_type_income_expense',
        'income')->whereNotNull('items_id')->groupBy('items_id') as $items_id => $group)
        @php
        $totalIncome = $group->sum('amount');
        @endphp
        <p class="indented"><strong>&nbsp;&nbsp;&nbsp;&nbsp; * {{ $group->first()->itemsCashFlow->name ?? __('Unknown')
                }}:</strong> Bs. {{ number_format($totalIncome, 2) }}</p>
        @endforeach

        {{-- 2️⃣ Incluir "initial account amount" --}}
        @php
        $initialAccountAmount = $items->where('transaction_type_income_expense', 'income')
        ->where('type_transaction', 'initial account amount')
        ->sum('amount');
        @endphp
        @if ($initialAccountAmount > 0)
        <p class="indented"><strong>&nbsp;&nbsp;&nbsp;&nbsp; * {{ __('Initial Account Amount') }}:</strong> Bs. {{
            number_format($initialAccountAmount, 2) }}</p>
        @endif

        {{-- 3️⃣ Incluir "payment of quota" --}}
        @php
        $paymentOfQuota = $items->where('transaction_type_income_expense', 'income')
        ->where('type_transaction', 'payment of quota')
        ->sum('amount');
        @endphp
        @if ($paymentOfQuota > 0)
        <p class="indented"><strong>&nbsp;&nbsp;&nbsp;&nbsp; * {{ __('Payment of Quota') }}:</strong> Bs. {{
            number_format($paymentOfQuota, 2) }}</p>
        @endif

        @endif

        {{-- Totales de Expense (Solo si hay egresos registrados) --}}
        @if($items->where('transaction_type_income_expense', 'expense')->isNotEmpty())
        <h4>{{ __('Expense Items') }}</h4>
        {{-- 1️⃣ Agrupar por Item --}}
        @foreach($items->where('transaction_type_income_expense',
        'expense')->whereNotNull('items_id')->groupBy('items_id') as $items_id => $group)
        @php
        $totalIncome = $group->sum('amount');
        @endphp
        <p class="indented"><strong>&nbsp;&nbsp;&nbsp;&nbsp; * {{ $group->first()->itemsCashFlow->name ?? __('Unknown')
                }}:</strong> Bs. {{ number_format($totalIncome, 2) }}</p>
        @endforeach

        {{-- 2️⃣ Incluir "loan" --}}
        @php
        $initialAccountAmount = $items->where('transaction_type_income_expense', 'expense')
        ->where('type_transaction', 'loan')
        ->sum('amount');
        @endphp
        @if ($initialAccountAmount > 0)
        <p class="indented"><strong>&nbsp;&nbsp;&nbsp;&nbsp; * {{ __('Loan') }}:</strong> Bs. {{
            number_format($initialAccountAmount, 2) }}</p>
        @endif
        @endif
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> {{env('ORIGEN')}}. {{__('All rights reserved')}}.</p>
    </footer>
</div>
</body>
</html>
