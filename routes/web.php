<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Income\Create as IncomeCreate;
use App\Livewire\Income\Edit as IncomeEdit;
use App\Livewire\Income\View as IncomeView;
use App\Livewire\Expense\Create as ExpenseCreate;
use App\Livewire\Expense\Edit as ExpenseEdit;
use App\Livewire\Expense\View as ExpenseView;
use App\Livewire\AccountLetters\Create as AccountLettersCreate;
use App\Livewire\AccountLetters\Edit as AccountLettersEdit;
use App\Livewire\AccountLetters\View as AccountLettersView;
use App\Livewire\AccountLetters\History as AccountLettersHistory;
use App\Livewire\AccountLetters\Transfer as AccountLettersTransfer;
use App\Livewire\IncomeCategories\Create as IncomeCategoriesCreate;
use App\Livewire\IncomeCategories\Edit as IncomeCategoriesEdit;
use App\Livewire\IncomeCategories\View as IncomeCategoriesView;
use App\Livewire\ExpenseCategories\Create as ExpenseCategoriesCreate;
use App\Livewire\ExpenseCategories\Edit as ExpenseCategoriesEdit;
use App\Livewire\ExpenseCategories\View as ExpenseCategoriesView;
use App\Livewire\OtherIncome\Create as OtherIncomeCreate;
use App\Livewire\OtherIncome\Edit as OtherIncomeEdit;
use App\Livewire\Loan\Create as LoansCreate;
use App\Livewire\Loan\Edit as LoansEdit;
use App\Livewire\Loan\View as LoansView;
use App\Livewire\User\Create as UserCreate;
use App\Livewire\User\Edit as UserEdit;
use App\Livewire\Role\Create as RoleCreate;
use App\Livewire\Role\Edit as RoleEdit;


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

    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
//        Incomes Routes
        Route::get('incomes', \App\Livewire\IncomeComponent::class)->name('income.index');
        Route::get('incomes/create', IncomeCreate::class)->name('income.create');
        Route::get('incomes/{id}/edit', IncomeEdit::class)->name('income.edit');
        Route::get('incomes/{id}/view', IncomeView::class)->name('income.view');
        //        Account Letters Routes
        Route::get('account-letters', \App\Livewire\AccountLettersComponent::class)->name('accountLetters.index');
        Route::get('account-letters/create', AccountLettersCreate::class)->name('accountLetters.create');
        Route::get('account-letters/{id}/edit', AccountLettersEdit::class)->name('accountLetters.edit');
        Route::get('account-letters/{id}/view', AccountLettersView::class)->name('accountLetters.view');
        Route::get('account-letters/{id}/transactions', AccountLettersHistory::class)->name('accountLetters.transactions');
        Route::get('account-letters/{id}/transfer', AccountLettersTransfer::class)->name('accountLetters.transfer');
        //        Income Categories Routes
        Route::get('imcome-categories', \App\Livewire\IncomeCategoriesComponent::class)->name('incomeCategories.index');
        Route::get('imcome-categories/create', IncomeCategoriesCreate::class)->name('incomeCategories.create');
        Route::get('imcome-categories/{id}/edit', IncomeCategoriesEdit::class)->name('incomeCategories.edit');
        Route::get('imcome-categories/{id}/view', IncomeCategoriesView::class)->name('incomeCategories.view');
        //        Expense Categories Routes
        Route::get('expense-categories', \App\Livewire\ExpenseCategoriesComponent::class)->name('expenseCategories.index');
        Route::get('expense-categories/create', ExpenseCategoriesCreate::class)->name('expenseCategories.create');
        Route::get('expense-categories/{id}/edit', ExpenseCategoriesEdit::class)->name('expenseCategories.edit');
        Route::get('expense-categories/{id}/view', ExpenseCategoriesView::class)->name('expenseCategories.view');
        //        Expense  Routes
        Route::get('expense', \App\Livewire\ExpenseComponent::class)->name('expense.index');
        Route::get('expense/create', ExpenseCreate::class)->name('expense.create');
        Route::get('expense/{id}/edit', ExpenseEdit::class)->name('expense.edit');
        Route::get('expense/{id}/view', ExpenseView::class)->name('expense.view');
        //        Others Incomes Routes
        Route::get('other-income', \App\Livewire\OtherIncomeComponent::class)->name('otherIncome.index');
        Route::get('other-income/create', OtherIncomeCreate::class)->name('otherIncome.create');
        Route::get('other-income/{id}/edit', OtherIncomeEdit::class)->name('otherIncome.edit');
        //        Loans  Routes
        Route::get('loans', \App\Livewire\LoanComponent::class)->name('loans.index');
        Route::get('loans/create', LoansCreate::class)->name('loans.create');
         Route::get('loans/{id}/edit',LoansEdit::class )->name('loans.edit');
        // Route::get('loans/{id}/view',LoansView::class )->name('loans.view');
        //        Reports
        Route::get('today-report', \App\Livewire\TodayReportComponent::class)->name('today.report');
        // Route::get('vehicle-report', \App\Livewire\VehicleReportComponent::class)->name('vehicle.report');
        Route::get('income-report', \App\Livewire\IncomeReportComponent::class)->name('income.report');
        Route::get('expense-report', \App\Livewire\ExpenseReportComponent::class)->name('expense.report');
        // Route::get('loan-report',\App\Livewire\LoanReportComponent::class )->name('loan.report');
        //        Others Users Routes
        Route::get('users', \App\Livewire\UserComponent::class)->name('user.index');
        Route::get('users/create', UserCreate::class)->name('user.create');
        Route::get('users/{id}/edit', UserEdit::class)->name('user.edit');
        //        Others Roles Routes
        Route::get('roles', \App\Livewire\RoleComponent::class)->name('role.index');
        Route::get('roles/create', RoleCreate::class)->name('role.create');
        Route::get('roles/{id}/edit', RoleEdit::class)->name('role.edit');
    });
});
