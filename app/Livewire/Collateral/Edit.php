<?php

namespace App\Livewire\Collateral;

use App\Models\CashFlow;
use App\Models\Collateral;
use App\Models\paymentPlans;
use App\Models\Vehicle;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    // Personal Information
    public int $vehicle_id;
    public string $user_type;
    public string $driver_partner_name;
    public string $description;
    // Collateral information
    public $start_date;
    public string $payment_frequency;
    public int $instalments;
    public float $amount;
    //
    public $collateral;
    public $confirmingUserDeletion = false;
    public bool $showButtonDelete = false;

    public function mount($id)
    {
        $this->collateral = Collateral::findOrFail($id);
        $this->vehicle_id = $this->collateral->vehicle_id;
        $this->user_type = $this->collateral->user_type;
        $this->driver_partner_name = $this->collateral->driver_partner_name;
        $this->description = $this->collateral->description;
        $this->start_date = $this->collateral->start_date;
        $this->payment_frequency = $this->collateral->payment_frequency;
        $this->instalments = $this->collateral->instalments;
        $this->amount = $this->collateral->amount;

        //$this->showInputs = $loan->installments()->where('paymentStatus', 'paid')->count() > 0;
        $this->showButtonDelete = $this->collateral->installments()->where('paymentStatus', 'paid')->count() > 0;
    }

    /**
     * Muestra el modal de confirmación de eliminación.
     */
    public function openDelete()
    {
        $this->confirmingUserDeletion = true;
    }

    /**
     * Cierra el modal de confirmación de eliminación.
     */
    public function closeDelete()
    {
        $this->confirmingUserDeletion = false;
    }

    /**
     * Elimina el registro de la base de datos.
     */
    public function delete()
    {
        DB::beginTransaction();
        try {
            if (!$this->collateral) {
                throw new \Exception('Collateral not found.');
            }
            // Eliminar registros en el orden correcto

            paymentPlans::where('collateral_id', $this->collateral->id)->delete();
            Collateral::findOrFail($this->collateral->id)->delete();

            $this->closeDelete();
            DB::commit();
            session()->flash('message', 'Registro eliminado correctamente.');
            return redirect()->route('collateral.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function update()
    {
        // Verifica si el vehiculo existe
        if (Vehicle::find($this->vehicle_id)) {
            DB::beginTransaction();
            try {
                $this->processUpdateLoan();
                DB::commit();
                $this->resetForm();
                session()->flash('success', 'Datos guardados correctamente.');
                return redirect()->route('collateral.view', ['id' => $this->collateral->id]);
            } catch (Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Error al guardar los datos: ' . $e->getMessage());
            }
        } else {
            session()->flash('error', 'El vehículo seleccionado no existe. Por favor, verifica e intenta nuevamente.');
        }
    }

    private function processUpdateLoan()
    {
        $collateral = Collateral::find($this->collateral->id);
        $collateral->update([
            'vehicle_id' => $this->vehicle_id,
            'user_type' => $this->user_type,
            'driver_partner_name' => $this->driver_partner_name,
            'description' => $this->description,
        ]);
    }

    private function resetForm(): void
    {
        $this->reset([
            'vehicle_id',
            'user_type',
            'driver_partner_name',
            'description',
            'start_date',
            'payment_frequency',
            'instalments',
            'amount',
        ]);
    }

    public function render()
    {
        return view('livewire.collateral.edit');
    }
}
