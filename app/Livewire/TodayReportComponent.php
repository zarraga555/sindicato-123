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

        // Aplica los filtros basados en las fechas
        if ($this->dateCheck === 'unic') {
            $query->whereDate('created_at', '=', $this->reportDateFrom);
        } elseif ($this->dateCheck === 'range') {
            $query->whereBetween('created_at', [
                Carbon::parse($this->reportDateFrom)->startOfDay(),
                Carbon::parse($this->reportDateTo)->endOfDay()
            ]);
        }

        // Paginación de los registros
        return $query->paginate(10);
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

        // Aplica los filtros basados en las fechas
        if ($this->dateCheck === 'unic') {
            $query->whereDate('created_at', '=', $this->reportDateFrom);
        } elseif ($this->dateCheck === 'range') {
            $query->whereBetween('created_at', [
                Carbon::parse($this->reportDateFrom)->startOfDay(),
                Carbon::parse($this->reportDateTo)->endOfDay()
            ]);
        }

        // Obtener los datos filtrados
        $items = $query->get();

        // Calcular totales
        $totalIncome = $items->where('transaction_type_income_expense', 'income')->sum('amount');
        $totalExpense = $items->where('transaction_type_income_expense', 'expense')->sum('amount');
        $netTotal = $totalIncome - $totalExpense;

        // Fechas
        $startDate = Carbon::parse($this->reportDateFrom);
        $endDate = Carbon::parse($this->reportDateTo);

        // Generar el PDF
        $pdf = Pdf::loadView('pdf.today-report', compact('items', 'totalIncome', 'totalExpense', 'netTotal', 'startDate', 'endDate'));

//         Descargar el archivo PDF
        return response()->streamDownload(
            fn() => print($pdf->output()),
            "reporte_diario_" . now()->format('Y-m-d') . ".pdf"
        );

    }
}
