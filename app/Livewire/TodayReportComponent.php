<?php

namespace App\Livewire;

use App\Models\CashFlow;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class TodayReportComponent extends Component
{
    use WithPagination;

    public $dateCheck = "unic"; // Valor predeterminado: "fecha única"
    public $reportDateFrom; // Fecha desde (usada tanto para única como rango)
    public $reportDateTo; // Fecha hasta (solo para rango)
    public $totalIncome;
    public $totalExpense;
    public $totalLoan;
    public $netTotal;

    public $selectedItem = null; // Filtro por ítem
    public $vehicleId = null; //

    public function mount()
    {
        $this->reportDateFrom = now()->format('Y-m-d'); // Establecer la fecha actual como predeterminada
        $this->filterRecords(); // Filtrar registros al montar el componente
    }

    public function render()
    {
        // Obtener los datos con paginación
        $items = $this->items(); // Se mueve la lógica de la consulta a una función separada

        return view('livewire.today-report-component', [
            'items' => $items,
        ]);
    }

    private function items()
    {
        $query = CashFlow::query();

        // Filtrar por fecha
        if ($this->dateCheck === 'unic') {
            $query->whereDate('registration_date', '=', $this->reportDateFrom);
        } elseif ($this->dateCheck === 'range') {
            $query->whereBetween('registration_date', [
                Carbon::parse($this->reportDateFrom)->startOfDay(),
                Carbon::parse($this->reportDateTo)->endOfDay()
            ]);
        }

        // Filtrar por ítem si está seleccionado
        if (!empty($this->selectedItem)) {
            $query->where('items_id', $this->selectedItem);
        }

        // Filtrar por número de vehículo si está ingresado
        if (!empty($this->vehicleId)) {
            $query->where('vehicle_id', $this->vehicleId);
        }

        return $query->paginate(25);
    }

    private function filterRecords()
    {
        $query = CashFlow::query();

        // Obtener los totales directamente desde la base de datos con condiciones
        $this->totalIncome = $query->where('transaction_type_income_expense', 'income')
//            ->whereNull('type_transaction')
            ->sum('amount');

        $this->totalExpense = $query->where('transaction_type_income_expense', 'expense')
//            ->whereNull('type_transaction')
            ->sum('amount');

//        $this->totalLoan = $query->where('type_transaction', 'loan')
//            ->sum('amount');
        $this->netTotal = $this->totalIncome - $this->totalExpense;
    }

    // Método para actualizar el filtro de fecha
    public function updated($propertyName)
    {
        // Si cambiamos alguna propiedad relacionada con la fecha, volvemos a filtrar los registros
        if (in_array($propertyName, ['reportDateFrom', 'reportDateTo', 'dateCheck'])) {
            $this->filterRecords();
        }
    }

    // Este método es necesario para la paginación cuando se usa Livewire
    public function updatedPage()
    {
        $this->filterRecords();
    }

    public function generatePDF()
    {
        $query = CashFlow::query();

        // Filtrar por fecha
        if ($this->dateCheck === 'unic') {
            $query->whereDate('registration_date', '=', $this->reportDateFrom);
        } elseif ($this->dateCheck === 'range') {
            $query->whereBetween('registration_date', [
                Carbon::parse($this->reportDateFrom)->startOfDay(),
                Carbon::parse($this->reportDateTo)->endOfDay()
            ]);
        }

        // Aplicar filtros adicionales
        if (!empty($this->selectedItem)) {
            $query->where('items_id', $this->selectedItem);
        }

        if (!empty($this->vehicleId)) {
            $query->where('vehicle_id', $this->vehicleId);
        }

        // Obtener los datos filtrados
        $items = $query->get();

        // Calcular totales
        $totalIncomes = $items->where('transaction_type_income_expense', 'income')->sum('amount');
        $totalExpenses = $items->where('transaction_type_income_expense', 'expense')->sum('amount');
        $netTotal = $totalIncomes - $totalExpenses;

        // Agrupar por ítems
        $itemsGrouped = $items->whereNotNull('items_id')
            ->groupBy('items_id')
            ->map(function ($group) {
                return [
                    'item_name' => optional($group->first()->itemsCashFlow)->name ?? __('Unknown'),
                    'total_income' => $group->where('transaction_type_income_expense', 'income')->sum('amount'),
                    'total_expense' => $group->where('transaction_type_income_expense', 'expense')->sum('amount'),
                ];
            });

        $startDate = Carbon::parse($this->reportDateFrom);
        $endDate = Carbon::parse($this->reportDateTo);

        // Obtener el rango de fechas según el filtro seleccionado
        $startDate = Carbon::parse($this->reportDateFrom)->startOfDay();
        $endDate = $this->dateCheck === 'range'
            ? Carbon::parse($this->reportDateTo)->endOfDay()
            : $startDate->copy()->endOfDay();

        // Contar cuántas hojas se vendieron (items_id 8 y 24)
        $soldSheets = CashFlow::whereIn('items_id', [8, 24])
            ->whereBetween('registration_date', [$startDate, $endDate])
            ->count();

        // Generar PDF
        $pdf = Pdf::loadView('pdf.today-report', compact('items', 'soldSheets', 'itemsGrouped', 'totalIncomes', 'totalExpenses', 'netTotal', 'startDate', 'endDate'));

        return response()->streamDownload(
            fn() => print($pdf->output()),
            "reporte_diario_" . now()->format('d-m-Y') . ".pdf"
        );


    }
}
