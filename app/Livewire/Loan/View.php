<?php

namespace App\Livewire\Loan;

use App\Models\Loans;
use App\Models\paymentPlans;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class View extends Component
{
    use WithPagination;

    public $loan;
    public $nameLabel;
    public $currency;

    public function mount($id)
    {
        $this->loan = Loans::findOrFail($id);
        $this->nameLabel = $this->loan->driver_partner_name;
        $this->currency = $this->loan->cashFlows->banks->currency_type;
    }

    public function render()
    {
        $fees = paymentPlans::where('loan_id', $this->loan->id)->paginate(15);
        return view('livewire.loan.view', compact('fees'));
    }

    public function downloadFile($feeId)
    {
        // Buscar la cuota o fee por su ID
        $fee = Loans::findOrFail($feeId);

        // Verificar si existe la ruta del archivo en la columna 'attachment'
        if (!$fee->attachment || !Storage::exists($fee->attachment)) {
            // Si no existe el archivo, mostrar un mensaje de error
            session()->flash('error', 'No hay recurso descargable');
            return;
        }

        // Si el archivo existe, proceder a la descarga
        return response()->download(storage_path('app/' . $fee->attachment));
    }

    public function generatePDF()
    {
        $fees = paymentPlans::where('loan_id', $this->loan->id)->get();
        // Generar el PDF
        $pdf = PDF::loadView('pdf.loan-report', ['loan' => $this->loan, 'fees' => $fees, 'currency' => $this->currency]);

        // Descargar el archivo PDF
        return response()->streamDownload(
            fn() => print($pdf->output()),
            "reporte_prestamo_". __($this->loan->user_type) . "_". $this->loan->driver_partner_name ."_" .now()->format('d-m-Y') . ".pdf"
        );
    }
}
