<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SystemConfigurationController extends Controller
{
    public function indexEmail()
    {
        return view('settings.email');
    }

    public function updateEmail(Request $request)
    {
        $request->validate(
            [
                'mail_driver' => 'required|string|max:255',
                'mail_host' => 'required|string|max:255',
                'mail_port' => 'required|string|max:255',
                'mail_username' => 'required|string|max:255',
                'mail_password' => 'required|string|max:255',
                'mail_encryption' => 'required|string|max:255',
                'mail_from_address' => 'required|string|max:255',
                'mail_from_name' => 'required|string|max:255',
            ]
        );

        $arrEnv = [
            'MAIL_DRIVER' => $request->mail_driver,
            'MAIL_HOST' => $request->mail_host,
            'MAIL_PORT' => $request->mail_port,
            'MAIL_USERNAME' => $request->mail_username,
            'MAIL_PASSWORD' => $request->mail_password,
            'MAIL_ENCRYPTION' => $request->mail_encryption,
            'MAIL_FROM_NAME' => $request->mail_from_name,
            'MAIL_FROM_ADDRESS' => $request->mail_from_address,
        ];
        Settings::setEnvironmentValue($arrEnv);
        // Mesaje de exito
        return redirect()->route('settings.email')->with('success', 'Configuración de correo electrónico actualizada correctamente.');
    }

    public function indexCompany()
    {
        return view('settings.company');
    }

    public function updateCompany(Request $request)
    {
        $request->validate([
            'EMPRESA_NOMBRE' => 'required|string|max:255',
            'EMPRESA_REFERENCIA' => 'nullable|string|max:255',
            'EMPRESA_CODIGO_POSTAL' => 'nullable|string|max:20',
            'EMPRESA_CIUDAD' => 'required|string|max:255',
            'EMPRESA_ESTADO' => 'required|string|max:255',
            'EMPRESA_PAIS' => 'required|string|max:255',
            'EMPRESA_TELEFONO' => 'nullable|string|max:20',
            'EMPRESA_CORREO' => 'nullable|email|max:255',
        ]);

        $arrEnv = [
            'EMPRESA_NOMBRE' => $request->EMPRESA_NOMBRE,
            'EMPRESA_REFERENCIA' => $request->EMPRESA_REFERENCIA,
            'EMPRESA_CODIGO_POSTAL' => $request->EMPRESA_CODIGO_POSTAL,
            'EMPRESA_CIUDAD' => $request->EMPRESA_CIUDAD,
            'EMPRESA_ESTADO' => $request->EMPRESA_ESTADO,
            'EMPRESA_PAIS' => $request->EMPRESA_PAIS,
            'EMPRESA_TELEFONO' => $request->EMPRESA_TELEFONO,
            'EMPRESA_CORREO' => $request->EMPRESA_CORREO,
        ];
        Settings::setEnvironmentValue($arrEnv);
        // Mesaje de exito
        return redirect()->route('settings.company')->with('success', 'Configuración de empresa actualizada correctamente.');
    }

    public function indexSubscription()
    {
        return view('settings.subscription');
    }
}
