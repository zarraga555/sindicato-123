<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    public static function setEnvironmentValue(array $values)
    {
        // Obtiene la ruta del archivo .env de Laravel
        $envFile = app()->environmentFilePath();

        // Lee el contenido del archivo .env y lo guarda en una variable
        $str = file_get_contents($envFile);

        // Verifica si el array $values tiene valores para modificar/agregar
        if (count($values) > 0)
        {
            // Recorre cada clave y valor en el array $values
            foreach ($values as $envKey => $envValue)
            {
                // Busca la posición donde aparece la clave en el archivo .env
                $keyPosition = strpos($str, "{$envKey}=");

                // Busca la posición del salto de línea (\n) después de la clave
                $endOfLinePosition = strpos($str, "\n", $keyPosition);

                // Extrae la línea completa que contiene la variable del archivo .env
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // Si la clave no existe en el archivo .env
                if (!$keyPosition || !$endOfLinePosition || !$oldLine)
                {
                    // Agrega la nueva variable al final del archivo con su valor
                    $str .= "{$envKey}='{$envValue}'\n";
                }
                else
                {
                    // Reemplaza la línea antigua con la nueva clave y valor
                    $str = str_replace($oldLine, "{$envKey}='{$envValue}'", $str);
                }
            }
        }

        // Asegura que el archivo termine con una línea nueva
        $str = substr($str, 0, -1);
        $str .= "\n";

        // Guarda el contenido modificado en el archivo .env
        if (!file_put_contents($envFile, $str))
        {
            return false; // Retorna falso si hay un error al guardar
        }

        return true; // Retorna verdadero si la operación fue exitosa
    }
}
