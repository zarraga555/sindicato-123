<?php

namespace App\Livewire;

use App\Models\Settings;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;
use Livewire\WithFileUploads;
use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class WizardSetup extends Component
{
    use WithFileUploads;

    // Permite manejar archivos subidos dentro del componente

    public $step = 1; // Paso actual del asistente

    // Variables para almacenar datos comerciales de la empresa
    public $empresa, $fecha_creacion, $logotipo, $contacto, $zona_horaria, $pais, $estado, $ciudad, $codigo_postal, $referencia;

    // Variables para almacenar los datos del propietario/usuario administrador
    public $nombre, $email, $password, $password_confirmation;

    // Variable para la cantidad de móviles (posiblemente vehículos o usuarios móviles)
    public $moviles;

    // Método para avanzar al siguiente paso del asistente
    public function nextStep()
    {
        $this->step++;
    }

    // Método para retroceder al paso anterior
    public function prevStep()
    {
        $this->step--;
    }

    // Método para guardar la configuración inicial del sistema
    public function save()
    {
        $this->validate([
            'empresa' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

//        try {
            // Guardamos los datos comerciales
            $data = [
                'APP_NAME' => $this->empresa,
                'EMPRESA_FECHA_CREACION' => $this->fecha_creacion,
                'EMPRESA_CONTACTO' => $this->contacto,
                'EMPRESA_ZONA_HORARIA' => $this->zona_horaria,
                'EMPRESA_PAIS' => $this->pais,
                'EMPRESA_ESTADO' => $this->estado,
                'EMPRESA_CIUDAD' => $this->ciudad,
                'EMPRESA_CODIGO_POSTAL' => $this->codigo_postal,
                'EMPRESA_REFERENCIA' => $this->referencia
            ];

            // Guardar en el archivo .env
            Settings::setEnvironmentValue($data);

            // Crear usuario
            $user = User::create([
                'name' => $this->nombre,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            // Asignar rol admin
            $user->assignRole('admin');

            // Autenticar al usuario
            Auth::login($user);

            for($i = 1; $i <= $this->moviles; $i++){
                Vehicle::create([
                    'created_at' => Carbon::now(),
                ]);

            }

            // Redirigir al dashboard con un mensaje de éxito
            session()->flash('success', 'Configuración guardada correctamente.');
            return redirect()->route('dashboard');
//        } catch (\Exception $e) {
//            // En caso de error, mostrar el mensaje de error
//            session()->flash('error', 'Hubo un error al guardar la configuración: ' . $e->getMessage());
//            return redirect()->route('dashboard');
//        }
    }

    public function render()
    {
        return view('livewire.wizard-setup')->layout('layouts.wizard');
    }
}
