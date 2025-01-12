<?php

namespace Database\Seeders;

use App\Models\ItemsCashFlow;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemsIngresos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemsCashFlow::create([
            'name'                  => 'Venta de Hoja',
            'type_income_expense'   => 'Income'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Garantia de Choferes',
            'type_income_expense'   => 'Income'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Multas de Talonarios',
            'type_income_expense'   => 'Income'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Multas de Recibos',
            'type_income_expense'   => 'Income'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Certificados',
            'type_income_expense'   => 'Income'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Transferencias',
            'type_income_expense'   => 'Income'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Multas de Asamblea',
            'type_income_expense'   => 'Income'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Alquiler',
            'type_income_expense'   => 'Income'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Interes de prestamo',
            'type_income_expense'   => 'Income'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Ingreso Tikeo (MES)',
            'type_income_expense'   => 'Income'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Lavadero (MES)',
            'type_income_expense'   => 'Income'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'PRO SEDE',
            'type_income_expense'   => 'Income'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Multa de bloqueo o marcha',
            'type_income_expense'   => 'Income'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Aporte sindical',
            'type_income_expense'   => 'Income'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Cambio de herramienta',
            'type_income_expense'   => 'Income'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Hoja Domingo talonario',
            'type_income_expense'   => 'Income'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Hoja de Resagados',
            'type_income_expense'   => 'Income'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Lavado',
            'type_income_expense'   => 'Income'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Multa de Lavado',
            'type_income_expense'   => 'Income'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Aporte para Seguro',
            'type_income_expense'   => 'Income'
        ]);
    }
}
