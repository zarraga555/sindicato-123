<?php

namespace App\Livewire\Collateral;

use App\Models\Collateral;
use App\Models\paymentPlans;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    public int $vehicle_id;
    public string $user_type;
    public string $driver_partner_name;
    public string $description;
    public $start_date;
    public string $payment_frequency;
    public int $instalments;
    public float $amount;
    public $collateral;

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

    private function validateForm(): void
    {
        $this->validate([
            'vehicle_id' => 'required|integer',
            'user_type' => 'required|string',
            'driver_partner_name' => 'required|string',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'payment_frequency' => 'required|string',
            'instalments' => 'required|integer',
            'amount' => 'required',
        ]);
    }

    private function createCollateral(): void
    {
        $this->collateral = Collateral::create([
            'vehicle_id' => $this->vehicle_id,
            'user_type' => $this->user_type,
            'driver_partner_name' => $this->driver_partner_name,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'payment_frequency' => $this->payment_frequency,
            'instalments' => $this->instalments,
            'amount' => $this->amount,
            'user_id' => Auth::id(),
            'status' => 'not charged',
        ]);

        $payment_date = Carbon::parse($this->start_date); // Asegúrate de que sea un objeto Carbon
        $fees = $this->amount / $this->instalments;
        // Verificamos si la frecuencia es semanal o mensual
        if ($this->payment_frequency == 'weekly') {
            for ($i = 1; $i <= $this->instalments; $i++) {
                // Nos aseguramos de que $i sea un número entero y lo pasamos como argumento
                $date = $payment_date->copy()->addWeeks((int)$i); // Asegurándonos de pasar un valor entero
                paymentPlans::create([
                    'instalmentNumber' => $i,
                    'datePayment' => $date,
                    'paymentStatus' => 'unpaid',
                    'amount' => $fees,
                    'collateral_id' => $this->collateral->id,
                    'user_id' => Auth::id(),
                ]);
            }
        } elseif ($this->payment_frequency == 'monthly') {
            for ($i = 1; $i <= $this->instalments; $i++) {
                // Asegurándonos de pasar un valor entero
                $date = $payment_date->copy()->addMonths((int)$i); // Asegurándonos de pasar un valor entero
                paymentPlans::create([
                    'instalmentNumber' => $i,
                    'datePayment' => $date,
                    'paymentStatus' => 'unpaid',
                    'amount' => $fees,
                    'collateral_id' => $this->collateral->id,
                    'user_id' => Auth::id(),
                ]);
            }
        }
    }

    public function save()
    {
        $this->validateForm();
        // Verifica si el vehiculo existe
        if (Vehicle::find($this->vehicle_id)) {
            DB::beginTransaction();
            try {
                $this->createCollateral();
                DB::commit();
                $this->resetForm();
                session()->flash('success', 'Registro creado exitosamente.');
                return redirect()->route('collateral.view', ['id' => $this->collateral->id]);
            } catch (\Throwable $th) {
                DB::rollBack();
                session()->flash('error', 'Ocurrió un error al crear el registro. Por favor, verifica e intenta nuevamente.' . $th->getMessage());
            }
        } else {
            session()->flash('error', 'El vehículo seleccionado no existe. Por favor, verifica e intenta nuevamente.');
        }
    }


    public function render()
    {
        return view('livewire.collateral.create');
    }
}
