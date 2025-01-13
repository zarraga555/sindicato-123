<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Income\Create as IncomeCreate;
use App\Livewire\Income\Edit as IncomeEdit;
use App\Livewire\Income\View as IncomeView;
use App\Livewire\AccountLetters\Create as AccountLettersCreate;
use App\Livewire\AccountLetters\Edit as AccountLettersEdit;
use App\Livewire\AccountLetters\View as AccountLettersView;
use App\Livewire\IncomeCategories\Create as IncomeCategoriesCreate;
use App\Livewire\IncomeCategories\Edit as IncomeCategoriesEdit;
use App\Livewire\IncomeCategories\View as IncomeCategoriesView;
use App\Livewire\ExpenseCategories\Create as ExpenseCategoriesCreate;
use App\Livewire\ExpenseCategories\Edit as ExpenseCategoriesEdit;
use App\Livewire\ExpenseCategories\View as ExpenseCategoriesView;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::middleware(['auth:sanctum', 'verified'])->group(function (){
//        Incomes Routes
        Route::get('incomes',\App\Livewire\IncomeComponent::class )->name('income.index');
        Route::get('incomes/create', IncomeCreate::class)->name('income.create');
        Route::get('incomes/{id}/edit',IncomeEdit::class )->name('income.edit');
        Route::get('incomes/{id}/view',IncomeView::class )->name('income.view');
        //        Account Letters Routes
        Route::get('account-letters',\App\Livewire\AccountLettersComponent::class )->name('accountLetters.index');
        Route::get('account-letters/create', AccountLettersCreate::class)->name('accountLetters.create');
        Route::get('account-letters/{id}/edit',AccountLettersEdit::class )->name('accountLetters.edit');
        Route::get('account-letters/{id}/view',AccountLettersView::class )->name('accountLetters.view');
        //        Income Categories Routes
        Route::get('imcome-categories',\App\Livewire\IncomeCategoriesComponent::class )->name('incomeCategories.index');
        Route::get('imcome-categories/create', IncomeCategoriesCreate::class)->name('incomeCategories.create');
        Route::get('imcome-categories/{id}/edit',IncomeCategoriesEdit::class )->name('incomeCategories.edit');
        Route::get('imcome-categories/{id}/view',IncomeCategoriesView::class )->name('incomeCategories.view');
        //        Expense Categories Routes
        Route::get('expense-categories',\App\Livewire\ExpenseCategoriesComponent::class )->name('expenseCategories.index');
        Route::get('expense-categories/create', ExpenseCategoriesCreate::class)->name('expenseCategories.create');
        Route::get('expense-categories/{id}/edit',ExpenseCategoriesEdit::class )->name('expenseCategories.edit');
        Route::get('expense-categories/{id}/view',ExpenseCategoriesView::class )->name('expenseCategories.view');
    });
});
