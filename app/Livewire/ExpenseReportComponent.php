<?php

namespace App\Livewire;

use App\Models\CashFlow;
use App\Models\ItemsCashFlow;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenseReportComponent extends Component
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
    public $incomeItems = [];

    public function mount()
    {
        $this->reportDateFrom = now()->format('Y-m-d'); // Establecer la fecha actual como predeterminada
        $this->filterRecords(); // Filtrar registros al montar el componente
        $this->incomeItems = ItemsCashFlow::where('type_income_expense', 'Expense')->get();
    }

    private function items()
    {
        $query = CashFlow::query();

        // Aplica los filtros basados en las fechas
        if ($this->dateCheck === 'unic') {
            $query->where('transaction_type_income_expense', 'expense')->whereDate('registration_date', '=', $this->reportDateFrom);
        } elseif ($this->dateCheck === 'range') {
            $query->where('transaction_type_income_expense', 'expense')->whereBetween('registration_date', [
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

        // Paginación de los registros
        return $query->paginate(25);
    }

    private function filterRecords()
    {
        $query = CashFlow::query();

        // Obtener los totales directamente desde la base de datos con condiciones
        $this->totalIncome = $query->where('transaction_type_income_expense', 'expense')
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

        // Aplica los filtros basados en las fechas
        if ($this->dateCheck === 'unic') {
            $query->where('transaction_type_income_expense', 'expense')->whereDate('registration_date', '=', $this->reportDateFrom);
        } elseif ($this->dateCheck === 'range') {
            $query->where('transaction_type_income_expense', 'expense')->whereBetween('registration_date', [
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
        $totalExpenses = $items->where('transaction_type_income_expense', 'expense')->sum('amount');

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

        // Fechas
        $startDate = Carbon::parse($this->reportDateFrom);
        $endDate = Carbon::parse($this->reportDateTo);

        // Generar el PDF
        $pdf = Pdf::loadView('pdf.expense-report', compact('items', 'itemsGrouped','totalExpenses', 'startDate', 'endDate'));

//         Descargar el archivo PDF
        return response()->streamDownload(
            fn() => print($pdf->output()),
            "reporte_gastos_" . now()->format('d-m-Y') . ".pdf"
        );

    }

    public function render()
    {
        // Obtener los datos con paginación
        $items = $this->items(); // Se mueve la lógica de la consulta a una función separada
        return view('livewire.expense-report-component', ['items' => $items,]);
    }
}
