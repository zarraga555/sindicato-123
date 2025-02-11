<?php

namespace App\Livewire;

use App\Models\Loans;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class UncollectibleAccountsComponent extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public $searchMobile;
    public ?Loans $loanInfo = null;
    public bool $showRevert = false;
    public int $id_loan;

    /**
     * Abre el modal
     */
    public function openModal()
    {
        $this->showModal = true;
    }

    /**
     * Cierra el modal y limpia los datos
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->loanInfo = null;
        $this->searchMobile = '';
    }

    // Método para abrir el modal
    public function showRevertModal($loanId)
    {
        $this->id_loan = $loanId;
        $this->showRevert = true;
    }

    // Método para cerrar el modal
    public function closeRevertModal()
    {
        $this->showRevert = false;
    }

    /**
     * Busca el préstamo según el número de móvil ingresado
     */
    public function searchLoan()
    {
        $this->loanInfo = null;
        // Borra cualquier error anterior
        session()->forget('error');

        if (empty($this->searchMobile)) {
            $this->loanInfo = null;
            return;
        }

        try {
            // Realizar la búsqueda del préstamo
            $this->loanInfo = Loans::where('vehicle_id', trim($this->searchMobile))->first();

            // Si no se encuentra el préstamo
            if (!$this->loanInfo) {
                session()->flash('error', 'No se encontró ningún préstamo con este número de móvil.');
            } else {
                // Verificar si el préstamo ya está marcado como incobrable
                if ($this->loanInfo->debtStatus === 'uncollectible') {
                    session()->flash('error', 'Este préstamo ya es una cuenta incobrable.');
                    $this->loanInfo->incobrable = true; // Marcamos que el préstamo está incobrable
                } else {
                    $this->loanInfo->incobrable = false; // El préstamo no está incobrable
                }
            }
        } catch (\Exception $e) {
            Log::error('Error al buscar préstamo: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al buscar el préstamo.');
        }
    }

    /**
     * Marcar el préstamo como incobrable
     */
    public function markAsUncollectible()
    {
        if (!$this->loanInfo) {
            session()->flash('error', 'No se encontró ningún préstamo para marcar como incobrable.');
            return;
        }

        try {
            if ($this->loanInfo->status === 'incobrable') {
                session()->flash('warning', 'Este préstamo ya está marcado como incobrable.');
                return;
            }

            $this->loanInfo->update(['debtStatus' => 'uncollectible']);

            session()->flash('success', 'Préstamo marcado como incobrable con éxito.');

            $this->closeModal();

            // Actualiza la vista de la tabla (sin recargar toda la página)
            $this->render();

        } catch (\Exception $e) {
            Log::error('Error al marcar préstamo como incobrable: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al intentar marcar el préstamo como incobrable.');
        }
    }

    public function revertToCollectible()
    {
        try {
            // Buscar el préstamo por su ID
            $loan = Loans::findOrFail($this->id_loan);

            // Verificar si la deuda está marcada como incobrable
            if ($loan->debtStatus === 'uncollectible') {
                // Cambiar el estado de la deuda a cobrable
                $loan->debtStatus = 'not charged';
                $loan->save();

                $this->closeRevertModal();
                // Mostrar mensaje de éxito
                session()->flash('success', 'La cuenta ha sido revertida a cobrable y ahora estará disponible para los cobros de cuotas.');
            } else {
                // Si la cuenta ya está cobrable, no hace nada
                session()->flash('error', 'La cuenta ya está cobrable.');
            }
        } catch (\Exception $e) {
            // Manejo de excepciones
            Log::error('Error al revertir la cuenta: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al intentar revertir la cuenta.');
        }
    }

    /**
     * Renderiza la vista con la lista de préstamos incobrables
     */
    public function render()
    {
        try {
            $loans = Loans::where('vehicle_id', 'like', '%' . $this->search . '%')
                ->where('debtStatus', 'uncollectible')
                ->orderByDesc('created_at')
                ->paginate(10);

            return view('livewire.uncollectible-accounts-component', compact('loans'));
        } catch (\Exception $e) {
            Log::error('Error al obtener la lista de préstamos incobrables: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al cargar los datos.');
            return view('livewire.uncollectible-accounts-component', ['loans' => collect()]);
        }
    }
}
