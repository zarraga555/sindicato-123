<?php

use App\Http\Controllers\SystemConfigurationController;
use App\Livewire\WizardSetup;
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
use App\Livewire\CashDrawers\Show;
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
    return view('dashboard'); // O la vista que desees mostrar
})->middleware(App\Http\Middleware\RedirectIfNoUsers::class);

// Ruta del wizard
Route::get('/wizard-setup', WizardSetup::class)->name('wizard');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::middleware(['auth:sanctum', 'verified', 'redirectIfNoUsers'])->group(function () {
        //        Incomes Routes
        Route::get('incomes', \App\Livewire\IncomeComponent::class)->name('income.index')->middleware('can:ver ingresos');
        Route::get('incomes/create', IncomeCreate::class)->name('income.create')->middleware('can:crear ingresos');
        Route::get('incomes/{id}/edit', IncomeEdit::class)->name('income.edit')->middleware('can:editar ingresos');
        Route::get('incomes/{id}/view', IncomeView::class)->name('income.view')->middleware('can:ver ingresos');
        //        Account Letters Routes
        Route::get('account-letters', \App\Livewire\AccountLettersComponent::class)->name('accountLetters.index')->middleware('permission:ver cuentas bancarias');
        Route::get('account-letters/create', AccountLettersCreate::class)->name('accountLetters.create')->middleware('permission:crear cuentas bancarias');
        Route::get('account-letters/{id}/edit', AccountLettersEdit::class)->name('accountLetters.edit')->middleware('permission:editar cuentas bancarias');
        Route::get('account-letters/{id}/view', AccountLettersView::class)->name('accountLetters.view')->middleware('permission:ver cuentas bancarias');
        Route::get('account-letters/{id}/transactions', AccountLettersHistory::class)->name('accountLetters.transactions')->middleware('permission:historial cuentas bancarias');
        Route::get('account-letters/{id}/transfer', AccountLettersTransfer::class)->name('accountLetters.transfer')->middleware('permission:transferencia cuentas bancarias');
        //        Income Categories Routes
        Route::get('imcome-categories', \App\Livewire\IncomeCategoriesComponent::class)->name('incomeCategories.index')->middleware('can:ver item ingreso');
        Route::get('imcome-categories/create', IncomeCategoriesCreate::class)->name('incomeCategories.create')->middleware('can:crear item ingreso');
        Route::get('imcome-categories/{id}/edit', IncomeCategoriesEdit::class)->name('incomeCategories.edit')->middleware('can:editar item ingreso');
        Route::get('imcome-categories/{id}/view', IncomeCategoriesView::class)->name('incomeCategories.view')->middleware('can:ver item ingreso');
        //        Expense Categories Routes
        Route::get('expense-categories', \App\Livewire\ExpenseCategoriesComponent::class)->name('expenseCategories.index')->middleware('can:ver item egreso');
        Route::get('expense-categories/create', ExpenseCategoriesCreate::class)->name('expenseCategories.create')->middleware('can:crear item egreso');
        Route::get('expense-categories/{id}/edit', ExpenseCategoriesEdit::class)->name('expenseCategories.edit')->middleware('can:editar item egreso');
        Route::get('expense-categories/{id}/view', ExpenseCategoriesView::class)->name('expenseCategories.view')->middleware('can:ver item egreso');
        //        Expense  Routes
        Route::get('expense', \App\Livewire\ExpenseComponent::class)->name('expense.index')->middleware('can:ver egreso');
        Route::get('expense/create', ExpenseCreate::class)->name('expense.create')->middleware('can:crear egreso');
        Route::get('expense/{id}/edit', ExpenseEdit::class)->name('expense.edit')->middleware('can:editar egreso');
        Route::get('expense/{id}/view', ExpenseView::class)->name('expense.view')->middleware('can:ver egreso');
        //        Others Incomes Routes
        Route::get('other-income', \App\Livewire\OtherIncomeComponent::class)->name('otherIncome.index')->middleware('can:ver otros ingresos');
        Route::get('other-income/create', OtherIncomeCreate::class)->name('otherIncome.create')->middleware('can:crear otros ingresos');
        Route::get('other-income/{id}/edit', OtherIncomeEdit::class)->name('otherIncome.edit')->middleware('can:editar otros ingresos');
        //        Loans  Routes
        Route::get('loans', \App\Livewire\LoanComponent::class)->name('loans.index')->middleware('can:ver prestamos');
        Route::get('loans/create', LoansCreate::class)->name('loans.create')->middleware('can:crear prestamos');
        Route::get('loans/{id}/edit', LoansEdit::class)->name('loans.edit')->middleware('can:editar prestamos');
        Route::get('loans/{id}/view', LoansView::class)->name('loans.view')->middleware('can:ver prestamos');
        //        Reports
        Route::get('today-report', \App\Livewire\TodayReportComponent::class)->name('today.report');
        // Route::get('vehicle-report', \App\Livewire\VehicleReportComponent::class)->name('vehicle.report');
        Route::get('income-report', \App\Livewire\IncomeReportComponent::class)->name('income.report');
        Route::get('expense-report', \App\Livewire\ExpenseReportComponent::class)->name('expense.report');
        // Route::get('loan-report',\App\Livewire\LoanReportComponent::class )->name('loan.report');
        //        Others Users Routes
        Route::get('users', \App\Livewire\UserComponent::class)->name('user.index')->middleware('can:ver usuarios');
        Route::get('users/create', UserCreate::class)->name('user.create')->middleware('can:crear usuarios');
        Route::get('users/{id}/edit', UserEdit::class)->name('user.edit')->middleware('can:editar usuarios');
        //        Others Roles Routes
        Route::get('roles', \App\Livewire\RoleComponent::class)->name('role.index')->middleware('can:ver roles');
        Route::get('roles/create', RoleCreate::class)->name('role.create')->middleware('can:crear roles');
        Route::get('roles/{id}/edit', RoleEdit::class)->name('role.edit')->middleware('permission:editar roles');
        // Uncollectible accounts
        Route::get('uncollectible-accounts', \App\Livewire\UncollectibleAccountsComponent::class)->name('uncollectibleAccounts.index');
        //Collection of Dues
        Route::get('collection-dues', \App\Livewire\CollectionDuesComponent::class)->name('collectionDues.index');
        //Cash register
        Route::get('cash-register', \App\Livewire\CashRegisterComponent::class)->name('cashRegister.index');
        //Cash drawer
        Route::get('cash-drawer', \App\Livewire\CashDrawersComponent::class)->name('cashDrawer.index');
        Route::get('cash-drawer/{id}/view', Show::class)->name('cashDrawer.show');
        // Settings Routes Email, Company, Subscription
        Route::get('email-settings', [SystemConfigurationController::class, 'indexEmail'])->name('settings.email');
        Route::post('email-settings', [SystemConfigurationController::class, 'updateEmail'])->name('settings.email.update');
        Route::get('company-settings', [SystemConfigurationController::class, 'indexCompany'])->name('settings.company');
        Route::post('company-settings', [SystemConfigurationController::class, 'updateCompany'])->name('settings.company.update');
        Route::get('subscription-settings', [SystemConfigurationController::class, 'indexSubscription'])->name('settings.subscription');
        Route::post('subscription-settings', [SystemConfigurationController::class, 'updateSubscription'])->name('settings.subscription.update');
        // Accounts Receivable
        Route::get('accounts-receivable', \App\Livewire\AccountsReceivable::class)->name('accountsReceivable.index');
        // Collateral
        Route::get('collateral', \App\Livewire\CollateralComponent::class)->name('collateral.index');
        Route::get('collateral/create', \App\Livewire\Collateral\Create::class)->name('collateral.create');
    });
});
