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
            text-align: right;
            font-size: 12px;
            margin-bottom: 20px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 13px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .summary {
            text-align: right;
            margin-top: 10px;
        }

        .summary p {
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
        <img src="logo.png" alt="Company Logo">
        <div class="company-info">
            <p><strong>Company Name:</strong> [Your Company Name]</p>
            <p><strong>Address:</strong> [Company Address]</p>
            <p><strong>Email:</strong> [Company Email] | <strong>Phone:</strong> [Company Phone]</p>
        </div>
    </header>

    <div class="report-title">{{__('Expense Report')}}</div>

    <div class="report-date">
        <p>{{__('Date')}}: {{$startDate->format('d/m/Y')}}</p> @if($endDate != null) {{__('to')}}
        {{$endDate->format('d/m/Y')}}@endif
    </div>

    <table>
        <thead>
        <tr>
            <th>{{ __('Vehicle') }}</th>
            <th>{{ __('Bank Account') }}</th>
            <th>{{ __('Items') }}</th>
            <th>{{ __('Transaction type') }}</th>
            <th>{{ __('Amount') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($items as $item)
        <tr>
            <td>{{ $item->vehicle_id ? str_pad($item->vehicle_id,3,0, STR_PAD_LEFT) : 'Sin movil asociado'}}</td>
            <td>{{ $item->banks ? $item->banks->bank_name : 'Sin banco asociado' }}</td>
            <td>{{ $item->itemsCashFlow->name?? __($item->type_transaction) }}</td>
            <td>{{ __($item->transaction_type_income_expense) }}</td>
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

    <div class="summary">
        <p><strong>{{__('Total Expenses')}}:</strong> Bs. {{ number_format($totalExpense, 2) }}</p>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> [Your Company Name]. All rights reserved.</p>
    </footer>
</div>
</body>
</html>
