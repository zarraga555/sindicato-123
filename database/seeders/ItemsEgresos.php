<?php

namespace Database\Seeders;

use App\Models\ItemsCashFlow;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemsEgresos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemsCashFlow::create([
            'name'                  => 'Gastos Diarios',
            'type_income_expense'   => 'Expense'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Devolucion de Garantias',
            'type_income_expense'   => 'Expense'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Pago de Sueldos',
            'type_income_expense'   => 'Expense'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Gastos en Tarjeta de Credito',
            'type_income_expense'   => 'Expense'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Devolucion en PRO SEDE',
            'type_income_expense'   => 'Expense'
        ]);

        ItemsCashFlow::create([
            'name'                  => 'Servicios Basicos',
            'type_income_expense'   => 'Expense'
        ]);
        ItemsCashFlow::create([
            'name'                  => 'Gastos Reservados',
            'type_income_expense'   => 'Expense'
        ]);
    }
}
