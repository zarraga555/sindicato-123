<?php

namespace App\Livewire\Collateral;

use App\Models\Collateral;
use App\Models\paymentPlans;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class View extends Component
{
    use WithPagination;

    public $collateral;
    public string $nameLabel;
    public $currency;

    public function mount($id)
    {
        $this->collateral = Collateral::findOrFail($id);
        $this->nameLabel = $this->collateral->vehicle->make . ' ' . $this->collateral->vehicle->model;
        //$this->currency = SystemConfigurationController::getCurrency();
    }

    public function render()
    {
        $fees = paymentPlans::where('collateral_id', $this->collateral->id)->paginate(15);
        return view('livewire.collateral.view', compact('fees'));
    }

    public function downloadFile($feeId)
    {
        // Buscar la cuota o fee por su ID
        $fee = Collateral::findOrFail($feeId);

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
        $fees = paymentPlans::where('collateral_id', $this->collateral->id)->get();
        // Generar el PDF
        $pdf = PDF::loadView('pdf.collateral-report', ['loan' => $this->loan, 'fees' => $fees, 'currency' => $this->currency]);

        // Descargar el archivo PDF
        return response()->streamDownload(
            fn() => print($pdf->output()),
            "reporte_garantia_". __($this->loan->user_type) . "_". $this->loan->driver_partner_name ."_" .now()->format('d-m-Y') . ".pdf"
        );
    }
}
