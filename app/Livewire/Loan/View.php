<?php

namespace App\Livewire\Loan;

use App\Models\AccountLetters;
use App\Models\Loans;
use App\Models\paymentPlans;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

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
}
