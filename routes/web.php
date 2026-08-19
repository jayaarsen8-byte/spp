<?php

/*
|--------------------------------------------------------------------------
| GUEST / LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', 'Auth\LoginController@showLoginForm')
        ->name('login');

    Route::post('/login', 'Auth\LoginController@login')
        ->name('login.post');
});


/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'active'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | GENERAL
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', 'Auth\LoginController@logout')
        ->name('logout');

    Route::get('/dashboard', 'DashboardController@index')
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | OWNER + ADMIN
    |--------------------------------------------------------------------------
    |
    | Fitur operasional yang dapat digunakan Owner dan Admin.
    |
    */

    Route::middleware('role:owner,admin')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | MASTER DATA
        |--------------------------------------------------------------------------
        */

        // Kategori
        Route::resource(
            'categories',
            'CategoryController'
        );

        // Satuan
        Route::resource(
            'units',
            'UnitController'
        );

        // Produk
        Route::resource(
            'products',
            'ProductController'
        );

        // Cari produk berdasarkan barcode
        Route::get(
            '/products/barcode/{barcode}',
            'ProductController@getByBarcode'
        );


        /*
        |--------------------------------------------------------------------------
        | POS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pos',
            'PosController@index'
        )->name('pos.index');

        Route::get(
            '/pos/products',
            'PosController@getProducts'
        )->name('pos.products');

        Route::post(
            '/pos/calculate',
            'PosController@calculateItem'
        )->name('pos.calculate');

        Route::post(
            '/pos/checkout',
            'PosController@store'
        )->name('pos.checkout');

        Route::get(
            '/pos/receipt/{sale}',
            'PosController@receipt'
        )->name('pos.receipt');


        /*
        |--------------------------------------------------------------------------
        | CUSTOMERS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'customers',
            'CustomerController'
        );


        /*
        |--------------------------------------------------------------------------
        | SALES / TRANSACTIONS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'sales',
            'SaleController'
        )->only([
            'index',
            'show'
        ]);

        Route::post(
            '/sales/{sale}/cancel',
            'SaleController@cancel'
        )->name('sales.cancel');

        Route::get(
            '/sales/{sale}/print',
            'SaleController@print'
        )->name('sales.print');


        /*
        |--------------------------------------------------------------------------
        | RECEIVABLES / PIUTANG
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'receivables',
            'ReceivableController'
        )->only([
            'index',
            'show'
        ]);

        Route::post(
            '/receivables/{receivable}/payment',
            'ReceivablePaymentController@store'
        )->name('receivable-payments.store');

        Route::get(
            '/receivables/{receivable}/payment-form',
            'ReceivablePaymentController@form'
        )->name('receivable-payments.form');


        /*
        |--------------------------------------------------------------------------
        | EXPENSES / PENGELUARAN
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'expenses',
            'ExpenseController'
        );

        Route::resource(
            'expense-categories',
            'ExpenseCategoryController'
        );
    });


    /*
    |--------------------------------------------------------------------------
    | OWNER ONLY
    |--------------------------------------------------------------------------
    |
    | Fitur yang hanya boleh diakses Owner.
    |
    */

    Route::middleware('role:owner')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | SETTINGS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'settings',
            'SettingsController'
        );


        /*
        |--------------------------------------------------------------------------
        | USER MANAGEMENT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/admin-users',
            'UserController@index'
        )->name('users.index');

        Route::post(
            '/admin-users',
            'UserController@store'
        )->name('users.store');

        Route::get(
            '/admin-users/{user}/edit',
            'UserController@edit'
        )->name('users.edit');

        Route::put(
            '/admin-users/{user}',
            'UserController@update'
        )->name('users.update');

        Route::delete(
            '/admin-users/{user}',
            'UserController@destroy'
        )->name('users.destroy');


        /*
        |--------------------------------------------------------------------------
        | PASSWORD RESET MANAGEMENT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/password-resets',
            'PasswordResetController@index'
        )->name('password-resets.index');

        Route::post(
            '/password-resets/{request}/approve',
            'PasswordResetController@approve'
        )->name('password-resets.approve');

        Route::post(
            '/password-resets/{request}/reject',
            'PasswordResetController@reject'
        )->name('password-resets.reject');


        /*
        |--------------------------------------------------------------------------
        | REPORTS - OWNER ONLY
        |--------------------------------------------------------------------------
        */

        // Laporan Penjualan
        Route::get(
            '/reports/sales',
            'ReportController@sales'
        )->name('reports.sales');

        Route::get(
            '/reports/sales/export',
            'ReportExportController@sales'
        )->name('reports.sales.export');


        // Laporan Inventory
        Route::get(
            '/reports/inventory',
            'ReportController@inventory'
        )->name('reports.inventory');

        Route::get(
            '/reports/inventory/export',
            'ReportExportController@inventory'
        )->name('reports.inventory.export');


        // Laporan Laba
        Route::get(
            '/reports/profit',
            'ReportController@profit'
        )->name('reports.profit');

        Route::get(
            '/reports/profit/export',
            'ReportExportController@profit'
        )->name('reports.profit.export');


        // Laporan Piutang
        Route::get(
            '/reports/receivable',
            'ReportController@receivable'
        )->name('reports.receivable');

        Route::get(
            '/reports/receivable/export',
            'ReportExportController@receivable'
        )->name('reports.receivable.export');


        // Laporan Pengeluaran
        Route::get(
            '/reports/expense',
            'ReportController@expense'
        )->name('reports.expense');

        Route::get(
            '/reports/expense/export',
            'ReportExportController@expense'
        )->name('reports.expense.export');


        /*
        |--------------------------------------------------------------------------
        | AUDIT LOG
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/audit-logs',
            'AuditLogController@index'
        )->name('audit-logs.index');
    });


    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    |
    | Owner dan Admin dapat mengelola profil masing-masing.
    |
    */

    Route::get(
        '/my-profile',
        'ProfileController@edit'
    )->name('profile.edit');

    Route::put(
        '/my-profile',
        'ProfileController@update'
    )->name('profile.update');

    Route::post(
        '/my-password',
        'ProfileController@updatePassword'
    )->name('profile.password');

    Route::post(
        '/password-reset-request',
        'ProfileController@requestPasswordReset'
    )->name('password.reset-request');


    /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/notifications',
        'NotificationController@index'
    )->name('notifications.index');

    Route::post(
        '/notifications/{notification}/read',
        'NotificationController@markAsRead'
    )->name('notifications.read');

    Route::get(
        '/api/notifications/unread-count',
        'NotificationController@unreadCount'
    )->name('notifications.unread-count');
});


/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/dashboard');
