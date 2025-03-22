<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.loan_of', ['vehicle' => $loan->vehicle_id]) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        .container {
            width: 100%;
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

        .title {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .section {
            margin-bottom: 20px;
        }

        .label {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
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
                <p><strong>{{ env('APP_NAME') }}</strong></p>
                <p><strong>{{ __('Address') }}:</strong> {{ env('EMPRESA_REFERENCIA') }},
                    {{ env('EMPRESA_CIUDAD') }},
                    {{ env('EMPRESA_ESTADO') }}, {{ env('EMPRESA_PAIS') }}</p>
                <p>
                    @if (env('EMPRESA_CORREO'))
                        {{ env('EMPRESA_CORREO') }} |
                    @endif
                    @if (env('EMPRESA_TELEFONO'))
                        {{ env('EMPRESA_TELEFONO') }}
                    @endif
                </p>
            </div>
        </header>
        <div class="title">{{ __('messages.loan_of', ['vehicle' => $loan->vehicle_id]) }}</div>

        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 33%; padding: 5px; border: none;"><strong>{{ __('Vehicle') }}:</strong>
                    <br>{{ $loan->vehicle_id }}
                </td>
                <td style="width: 33%; padding: 5px; border: none;"><strong>{{ __('User type') }}:</strong>
                    <br>{{ __($loan->user_type) }}
                </td>
                <td style="width: 33%; padding: 5px; border: none;"><strong>{{ __('Full Name') }}:</strong>
                    <br>{{ $loan->driver_partner_name }}
                </td>
            </tr>
            <tr>
                <td style="padding: 5px; border: none;"><strong>{{ __('Description') }}:</strong>
                    <br>{{ $loan->description }}
                </td>
                <td style="padding: 5px; border: none;"><strong>{{ __('Loan start date') }}:</strong>
                    <br>{{ $loan->loan_start_date }}
                </td>
                <td style="padding: 5px; border: none;"><strong>{{ __('Payment frequency') }}:</strong>
                    <br>{{ __($loan->payment_frequency) }}
                </td>
            </tr>
            <tr>
                <td style="padding: 5px; border: none;"><strong>{{ __('Collection of interest earned') }}:</strong>
                    <br>{{ $loan->interest_payment_method === 'separate' ? __('Charge in a separate installment') : __('Charge together with the quotas') }}
                </td>
                <td style="padding: 5px; border: none;"><strong>{{ __('Number of Installments') }}:</strong>
                    <br>
                    {{ $loan->interest_payment_method === 'separate' ? $loan->numberInstalments + 1 : $loan->numberInstalments }}
                    {{ __('Fees') }}
                </td>
                <td style="padding: 5px; border: none;"><strong>{{ __('Loan amount') }}:</strong> {{ $currency }}.
                    <br>{{ $loan->amountLoan }}
                </td>
            </tr>
            <tr>
                <td style="padding: 5px; border: none;"><strong>{{ __('Interest rate') }}:</strong>
                    <br>{{ $loan->interest_rate }}%
                </td>
                <td style="padding: 5px; border: none;"><strong>{{ __('Total debt receivable') }}:</strong>
                    <br>{{ $currency }}. {{ $loan->total_debt }}
                </td>
                <td style="padding: 5px; border: none;"><strong>{{ __('Loan status') }}:</strong>
                    <br>{{ __($loan->debtStatus) }}
                </td>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th>{{ __('Instalment Number') }}</th>
                    <th>{{ __('Date Payment') }}</th>
                    <th>{{ __('Payment Status') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Remarks') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fees as $fee)
                    <tr>
                        <td>{{ $fee->instalmentNumber }}</td>
                        <td>{{ $fee->datePayment ? \Carbon\Carbon::parse($fee->datePayment)->format('d/m/Y') : __('Date not available') }}</td>
                        <td>{{ __($fee->paymentStatus) }}</td>
                        <td>{{ $currency }}. {{ $fee->amount }}</td>
                        <td>{{ $fee->description }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">{{ __('No fees were found for this loan.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <footer style="position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 12px;">
            <p>&copy; <?php echo date('Y'); ?> {{ env('ORIGEN') }}. {{ __('All rights reserved') }}.</p>
        </footer>
    </div>
</body>

</html>
