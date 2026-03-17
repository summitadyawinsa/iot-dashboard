<?php

use App\Events\MessageCreated;
use App\Http\Controllers\Api\V1\DeliveryController;
use App\Http\Controllers\Api\V1\DeptPinController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\LogMachineController;
use App\Http\Controllers\Api\V1\PurchasingController;
use Illuminate\Support\Facades\DB;
use App\Models\LogMachine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

Route::get('/', function () {
    return view('dashboard.stamping.page', ['id' => 1]);
});


Route::view('/analytics', 'analytics');
Route::view('/finance', 'finance');
Route::view('/crypto', 'crypto');

Route::view('/apps/chat', 'apps.chat');
Route::view('/apps/mailbox', 'apps.mailbox');
Route::view('/apps/todolist', 'apps.todolist');
Route::view('/apps/notes', 'apps.notes');
Route::view('/apps/scrumboard', 'apps.scrumboard');
Route::view('/apps/contacts', 'apps.contacts');
Route::view('/apps/calendar', 'apps.calendar');

Route::view('/apps/invoice/list', 'apps.invoice.list');
Route::view('/apps/invoice/preview', 'apps.invoice.preview');
Route::view('/apps/invoice/add', 'apps.invoice.add');
Route::view('/apps/invoice/edit', 'apps.invoice.edit');

Route::view('/components/tabs', 'ui-components.tabs');
Route::view('/components/accordions', 'ui-components.accordions');
Route::view('/components/modals', 'ui-components.modals');
Route::view('/components/cards', 'ui-components.cards');
Route::view('/components/carousel', 'ui-components.carousel');
Route::view('/components/countdown', 'ui-components.countdown');
Route::view('/components/counter', 'ui-components.counter');
Route::view('/components/sweetalert', 'ui-components.sweetalert');
Route::view('/components/timeline', 'ui-components.timeline');
Route::view('/components/notifications', 'ui-components.notifications');
Route::view('/components/media-object', 'ui-components.media-object');
Route::view('/components/list-group', 'ui-components.list-group');
Route::view('/components/pricing-table', 'ui-components.pricing-table');
Route::view('/components/lightbox', 'ui-components.lightbox');

Route::view('/elements/alerts', 'elements.alerts');
Route::view('/elements/avatar', 'elements.avatar');
Route::view('/elements/badges', 'elements.badges');
Route::view('/elements/breadcrumbs', 'elements.breadcrumbs');
Route::view('/elements/buttons', 'elements.buttons');
Route::view('/elements/buttons-group', 'elements.buttons-group');
Route::view('/elements/color-library', 'elements.color-library');
Route::view('/elements/dropdown', 'elements.dropdown');
Route::view('/elements/infobox', 'elements.infobox');
Route::view('/elements/jumbotron', 'elements.jumbotron');
Route::view('/elements/loader', 'elements.loader');
Route::view('/elements/pagination', 'elements.pagination');
Route::view('/elements/popovers', 'elements.popovers');
Route::view('/elements/progress-bar', 'elements.progress-bar');
Route::view('/elements/search', 'elements.search');
Route::view('/elements/tooltips', 'elements.tooltips');
Route::view('/elements/treeview', 'elements.treeview');
Route::view('/elements/typography', 'elements.typography');

Route::view('/charts', 'charts');
Route::view('/widgets', 'widgets');
Route::view('/font-icons', 'font-icons');
Route::view('/dragndrop', 'dragndrop');

Route::view('/tables', 'tables');

Route::view('/datatables/advanced', 'datatables.advanced');
Route::view('/datatables/alt-pagination', 'datatables.alt-pagination');
Route::view('/datatables/basic', 'datatables.basic');
Route::view('/datatables/checkbox', 'datatables.checkbox');
Route::view('/datatables/clone-header', 'datatables.clone-header');
Route::view('/datatables/column-chooser', 'datatables.column-chooser');
Route::view('/datatables/export', 'datatables.export');
Route::view('/datatables/multi-column', 'datatables.multi-column');
Route::view('/datatables/multiple-tables', 'datatables.multiple-tables');
Route::view('/datatables/order-sorting', 'datatables.order-sorting');
Route::view('/datatables/range-search', 'datatables.range-search');
Route::view('/datatables/skin', 'datatables.skin');
Route::view('/datatables/sticky-header', 'datatables.sticky-header');

Route::view('/forms/basic', 'forms.basic');
Route::view('/forms/input-group', 'forms.input-group');
Route::view('/forms/layouts', 'forms.layouts');
Route::view('/forms/validation', 'forms.validation');
Route::view('/forms/input-mask', 'forms.input-mask');
Route::view('/forms/select2', 'forms.select2');
Route::view('/forms/touchspin', 'forms.touchspin');
Route::view('/forms/checkbox-radio', 'forms.checkbox-radio');
Route::view('/forms/switches', 'forms.switches');
Route::view('/forms/wizards', 'forms.wizards');
Route::view('/forms/file-upload', 'forms.file-upload');
Route::view('/forms/quill-editor', 'forms.quill-editor');
Route::view('/forms/markdown-editor', 'forms.markdown-editor');
Route::view('/forms/date-picker', 'forms.date-picker');
Route::view('/forms/clipboard', 'forms.clipboard');

Route::view('/users/profile', 'users.profile');
Route::view('/users/user-account-settings', 'users.user-account-settings');

Route::view('/pages/knowledge-base', 'pages.knowledge-base');
Route::view('/pages/contact-us-boxed', 'pages.contact-us-boxed');
Route::view('/pages/contact-us-cover', 'pages.contact-us-cover');
Route::view('/pages/faq', 'pages.faq');
Route::view('/pages/coming-soon-boxed', 'pages.coming-soon-boxed');
Route::view('/pages/coming-soon-cover', 'pages.coming-soon-cover');
Route::view('/pages/error404', 'pages.error404');
Route::view('/pages/error500', 'pages.error500');
Route::view('/pages/error503', 'pages.error503');
Route::view('/pages/maintenence', 'pages.maintenence');

Route::view('/auth/boxed-lockscreen', 'auth.boxed-lockscreen');
Route::view('/auth/boxed-signin', 'auth.boxed-signin');
Route::view('/auth/boxed-signup', 'auth.boxed-signup');
Route::view('/auth/boxed-password-reset', 'auth.boxed-password-reset');
Route::view('/auth/cover-login', 'auth.cover-login');
Route::view('/auth/cover-register', 'auth.cover-register');
Route::view('/auth/cover-lockscreen', 'auth.cover-lockscreen');
Route::view('/auth/cover-password-reset', 'auth.cover-password-reset');

Route::get('/dashboard/stamping/{id}', function ($id) {
    return view('dashboard.stamping.page', ['id' => "$id"]);
});

Route::get('/dashboard/assy/{id}', function ($id) {
    return view('dashboard.assy.page', ['id' => "$id"]);
});

Route::get('/dashboard/summary/assy/{id}', function ($id) {
    $db = LogMachine::where('line_id', "$id")
        ->whereNotIn('machine_code', ['RSW-5H45-01', 'RSW-5H45-02', 'RSW-5H45-03', 'RSW-5H45-04', 'RSW-5H45-05', 'RSW-5H45-06'])->get();
    $results = [];
    foreach ($db as $row) {
        $machine_id = $row->machine_id;
        $mc_code = $row->machine_code;
        $plan = $row->qty_plan;
        $actual = $row->qty_actual;
        $sph = $row->current_gsph;
        $dresser_count = $row->dresser_count;
        $spot_count = $row->spot_count;
        $ct = $row->average_ct;
        $bar_progress = ($row->qty_plan > 0 ? number_format($row->qty_actual / $row->qty_plan * 100, 0) : 0);

        $db_log_detail = DB::table('log_detail_machine')
            ->where('job_num', "$row->job_num")
            ->where('machine_id', "$row->machine_id")
            ->orderBy('seq_id', 'desc')
            ->limit(24)
            ->get();

        $ct_log_detail = [];
        $no = 1;
        foreach ($db_log_detail as $row) {
            $cycle_time = ($row->cycle_time == '') ? 0 : str_replace(",", "", number_format($row->cycle_time, 2));
            $ct_log_detail[] = $cycle_time;
            if ($no == 25) {
                break;
            }
            $no++;
        }
        $ct_log_detail = array_reverse($ct_log_detail);
        $ct_log_detail = implode(',', $ct_log_detail);

        $results[] = [
            'machine_id' => "$machine_id",
            'dresser_count' => $dresser_count,
            'spot_count' => $spot_count,
            'mc_code' => $mc_code,
            'plan' => $plan,
            'actual' => $actual,
            'sph' => $sph,
            'ct' => $ct,
            'bar_progress' => $bar_progress,
            'ct_log_detail' => $ct_log_detail
        ];
    }
    return view('dashboard.assy.summary', ['id' => "$id", 'db' => $results]);
});

Route::get('/dashboard/summary/dresser/assy/{id}', function ($id) {
    $db = LogMachine::where('line_id', "$id")->where('is_active', 1)->get();
    $results = [];
    foreach ($db as $row) {
        $machine_id = $row->machine_id;
        $mc_code = $row->machine_code;
        $plan = $row->qty_plan;
        $actual = $row->qty_actual;
        $sph = $row->current_gsph;
        $dresser_count = $row->dresser_count;
        $spot_count = $row->spot_count;
        $ct = $row->average_ct;
        $bar_progress = ($row->qty_plan > 0 ? number_format($row->qty_actual / $row->qty_plan * 100, 0) : 0);

        $db_log_detail = DB::table('log_detail_machine')
            ->where('job_num', "$row->job_num")
            ->where('machine_id', "$row->machine_id")
            ->orderBy('seq_id', 'desc')
            ->limit(24)
            ->get();

        $ct_log_detail = [];
        $no = 1;
        foreach ($db_log_detail as $row) {
            $cycle_time = ($row->cycle_time == '') ? 0 : str_replace(",", "", number_format($row->cycle_time, 2));
            $ct_log_detail[] = $cycle_time;
            if ($no == 25) {
                break;
            }
            $no++;
        }
        $ct_log_detail = array_reverse($ct_log_detail);
        $ct_log_detail = implode(',', $ct_log_detail);

        $results[] = [
            'machine_id' => "$machine_id",
            'dresser_count' => $dresser_count,
            'spot_count' => $spot_count,
            'mc_code' => $mc_code,
            'plan' => $plan,
            'actual' => $actual,
            'sph' => $sph,
            'ct' => $ct,
            'bar_progress' => $bar_progress,
            'ct_log_detail' => $ct_log_detail
        ];
    }
    return view('dashboard.assy.summary_dresser', ['id' => "$id", 'db' => $results]);
});

Route::get('/dashboard/summary/stamping/{id}', function ($id) {
    $db = LogMachine::where('line_id', "$id")->where('is_active', 1)->get();
    $results = [];
    foreach ($db as $row) {
        $machine_id = $row->machine_id;
        $mc_code = $row->machine_code;
        $plan = $row->qty_plan;
        $actual = $row->qty_actual;
        $sph = $row->current_gsph;
        $ct = $row->average_ct;
        $bar_progress = ($row->qty_plan > 0 ? number_format($row->qty_actual / $row->qty_plan * 100, 0) : 0);

        $db_log_detail = DB::table('log_detail_machine')
            ->where('job_num', "$row->job_num")
            ->where('machine_id', "$row->machine_id")
            ->orderBy('seq_id', 'desc')
            ->limit(24)
            ->get();

        $ct_log_detail = [];
        $no = 1;
        foreach ($db_log_detail as $row) {
            $cycle_time = ($row->cycle_time == '') ? 0 : str_replace(",", "", number_format($row->cycle_time, 2));
            $ct_log_detail[] = $cycle_time;
            if ($no == 25) {
                break;
            }
            $no++;
        }
        $ct_log_detail = array_reverse($ct_log_detail);
        $ct_log_detail = implode(',', $ct_log_detail);

        $results[] = [
            'machine_id' => "$machine_id",
            'mc_code' => $mc_code,
            'plan' => $plan,
            'actual' => $actual,
            'sph' => $sph,
            'ct' => $ct,
            'bar_progress' => $bar_progress,
            'ct_log_detail' => $ct_log_detail
        ];
    }
    return view('dashboard.stamping.summary', ['id' => "$id", 'db' => $results]);
});



// Route::get('/dashboard/config', function () {
//     return view('dashboard.config.config');
// });
Route::get('/dashboard/ppm-dashboard', function () {
    return view('dashboard.ppm.ppm_monitoring');
});

Route::post('/api/check_pin_sales', [DeptPinController::class, 'check_pin_sales'])
    ->middleware('web');

Route::get('/dashboard/sales', function () {
    return view('dashboard.sales.sales_monitoring');
})->middleware('department:sales');

Route::post('/api/check_pin_purchasing', [DeptPinController::class, 'check_pin_purchasing'])
    ->middleware('web');

Route::get('/dashboard/purchasing/report', function () {
    return view('dashboard.purchasing.purchasing_report');
})->middleware('department:purchasing');

Route::post('/api/check_pin_purchasing_pr', [DeptPinController::class, 'check_pin_purchasing_pr'])
    ->middleware('web');

Route::post('/api/check_pin_purchasing_reguler', [DeptPinController::class, 'check_pin_purchasing_reguler'])
    ->middleware('web');

Route::get('/dashboard/purchasing/monitoring_pr', function () {
    return view('dashboard.purchasing.monitoring_pr');
})->middleware('department:purchasing_pr');

Route::post('/api/check_pin_purchasing_project', [DeptPinController::class, 'check_pin_purchasing_project'])
    ->middleware('web');

Route::post('/api/check_pin_purchasing_ppic', [DeptPinController::class, 'check_pin_purchasing_ppic'])
    ->middleware('web');

Route::get('/dashboard/purchasing/po_project_monitoring', function () {
    return view('dashboard.purchasing.po_project_monitoring');
})->middleware('department:purchasing_project');

Route::get('/dashboard/purchasing/po_ppic_monitoring', function () {
    return view('dashboard.purchasing.po_ppic_monitoring');
})->middleware('department:purchasing_ppic');

Route::get('/dashboard/purchasing/po_reguler_monitoring', function () {
    return view('dashboard.purchasing.po_reguler_monitoring');
})->middleware('department:purchasing_reguler');

Route::post('/api/check_pin_ppic_job', [DeptPinController::class, 'check_pin_ppic_job'])
    ->middleware('web');

Route::get('/dashboard/ppic', function () {
    return view('dashboard.ppic.job_monitoring');
})->middleware('department:ppic_job');

Route::post('/api/check_pin_ppic_stock', [DeptPinController::class, 'check_pin_ppic_stock'])
    ->middleware('web');

Route::get('/dashboard/ppic/stock', function () {
    return view('dashboard.ppic.stock_monitoring');
})->middleware('department:ppic_stock');

Route::post('/api/check_pin_production', [DeptPinController::class, 'check_pin_production'])
    ->middleware('web');

Route::get('/dashboard/production', function () {
    return view('dashboard.production.production_achievment');
})->middleware('department:production');

Route::post('/api/check_pin_delivery', [DeptPinController::class, 'check_pin_delivery'])
    ->middleware('web');

Route::get('/dashboard/delivery', function () {
    return view('dashboard.delivery.delivery_forcast');
})->middleware('department:delivery');

Route::post('/api/check_pin_delivery_job', [DeptPinController::class, 'check_pin_delivery_job'])
    ->middleware('web');

Route::get('/dashboard/delivery/job_monitoring', function () {
    return view('dashboard.delivery.job_monitoring');
})->middleware('department:delivery_job');

Route::post('/api/check_pin_delivery_monitoring', [DeptPinController::class, 'check_pin_delivery_monitoring'])
    ->middleware('web');

Route::get('/dashboard/delivery/delivery_monitoring', function () {
    return view('dashboard.delivery.delivery_monitoring');
})->middleware('department:delivery_monitoring');

Route::post('/api/check_pin_delivery_finish_good', [DeptPinController::class, 'check_pin_delivery_finish_good'])
    ->middleware('web');

Route::get('/dashboard/delivery/finish_good', function () {
    return view('dashboard.delivery.finish_good');
})->middleware('department:delivery_finish_good');

Route::post('/api/check_pin_delivery_mit', [DeptPinController::class, 'check_pin_delivery_mit'])
    ->middleware('web');

Route::get('/dashboard/delivery/mit_dashboard', function () {
    return view('dashboard.delivery.mit_dashboard');
})->middleware('department:delivery_mit');

Route::post('/api/check_pin_delivery_cgr', [DeptPinController::class, 'check_pin_delivery_cgr'])
    ->middleware('web');

Route::get('dashboard/delivery/cgr_monitoring', function () {
    return view('dashboard.delivery.cgr_monitoring');
})->middleware('department:delivery_cgr');

Route::post('/api/check_pin_finance_profit', [DeptPinController::class, 'check_pin_finance_profit'])
    ->middleware('web');

Route::post('/api/check_pin_finance_invoice', [DeptPinController::class, 'check_pin_finance_invoice'])
    ->middleware('web');

Route::get('/dashboard/profitability', function () {
    return view('dashboard.finance.profitability');
});

Route::get('/dashboard/profitability/invoice', function () {
    return view('dashboard.finance.profitability_invoice_monitoring');
})->middleware('department:finance_invoice');

Route::post('/api/check_pin_finance_model', [DeptPinController::class, 'check_pin_finance_model'])
    ->middleware('web');

Route::get('/dashboard/profitability/Model', function () {
    return view('dashboard.finance.profitability_model');
})->middleware('department:finance_model');

Route::post('/api/check_pin_finance_rcd', [DeptPinController::class, 'check_pin_finance_rcd'])
    ->middleware('web');

Route::get('/dashboard/profitability/rcd', function () {
    return view('dashboard.finance.reasonable_costdown');
})->middleware('department:finance_rcd');

// Route::prefix('configuration')->group(function () {
//     // Route::get('setup', function () {
//     //     return view('v2.config');
//     // });
//     Route::get('special-setup', function () {
//         return view('v2.setup.special-setup');
//     });
// });
Route::post('machine/export-history-log', [LogMachineController::class, 'exportHistoryTable']);
Route::get('/', function () {
    return view('dashboard.stamping.page', ['id' => 1]);
});


Route::view('/analytics', 'analytics');
Route::view('/finance', 'finance');
Route::view('/crypto', 'crypto');

Route::view('/apps/chat', 'apps.chat');
Route::view('/apps/mailbox', 'apps.mailbox');
Route::view('/apps/todolist', 'apps.todolist');
Route::view('/apps/notes', 'apps.notes');
Route::view('/apps/scrumboard', 'apps.scrumboard');
Route::view('/apps/contacts', 'apps.contacts');
Route::view('/apps/calendar', 'apps.calendar');

Route::view('/apps/invoice/list', 'apps.invoice.list');
Route::view('/apps/invoice/preview', 'apps.invoice.preview');
Route::view('/apps/invoice/add', 'apps.invoice.add');
Route::view('/apps/invoice/edit', 'apps.invoice.edit');

Route::view('/components/tabs', 'ui-components.tabs');
Route::view('/components/accordions', 'ui-components.accordions');
Route::view('/components/modals', 'ui-components.modals');
Route::view('/components/cards', 'ui-components.cards');
Route::view('/components/carousel', 'ui-components.carousel');
Route::view('/components/countdown', 'ui-components.countdown');
Route::view('/components/counter', 'ui-components.counter');
Route::view('/components/sweetalert', 'ui-components.sweetalert');
Route::view('/components/timeline', 'ui-components.timeline');
Route::view('/components/notifications', 'ui-components.notifications');
Route::view('/components/media-object', 'ui-components.media-object');
Route::view('/components/list-group', 'ui-components.list-group');
Route::view('/components/pricing-table', 'ui-components.pricing-table');
Route::view('/components/lightbox', 'ui-components.lightbox');

Route::view('/elements/alerts', 'elements.alerts');
Route::view('/elements/avatar', 'elements.avatar');
Route::view('/elements/badges', 'elements.badges');
Route::view('/elements/breadcrumbs', 'elements.breadcrumbs');
Route::view('/elements/buttons', 'elements.buttons');
Route::view('/elements/buttons-group', 'elements.buttons-group');
Route::view('/elements/color-library', 'elements.color-library');
Route::view('/elements/dropdown', 'elements.dropdown');
Route::view('/elements/infobox', 'elements.infobox');
Route::view('/elements/jumbotron', 'elements.jumbotron');
Route::view('/elements/loader', 'elements.loader');
Route::view('/elements/pagination', 'elements.pagination');
Route::view('/elements/popovers', 'elements.popovers');
Route::view('/elements/progress-bar', 'elements.progress-bar');
Route::view('/elements/search', 'elements.search');
Route::view('/elements/tooltips', 'elements.tooltips');
Route::view('/elements/treeview', 'elements.treeview');
Route::view('/elements/typography', 'elements.typography');

Route::view('/charts', 'charts');
Route::view('/widgets', 'widgets');
Route::view('/font-icons', 'font-icons');
Route::view('/dragndrop', 'dragndrop');

Route::view('/tables', 'tables');

Route::view('/datatables/advanced', 'datatables.advanced');
Route::view('/datatables/alt-pagination', 'datatables.alt-pagination');
Route::view('/datatables/basic', 'datatables.basic');
Route::view('/datatables/checkbox', 'datatables.checkbox');
Route::view('/datatables/clone-header', 'datatables.clone-header');
Route::view('/datatables/column-chooser', 'datatables.column-chooser');
Route::view('/datatables/export', 'datatables.export');
Route::view('/datatables/multi-column', 'datatables.multi-column');
Route::view('/datatables/multiple-tables', 'datatables.multiple-tables');
Route::view('/datatables/order-sorting', 'datatables.order-sorting');
Route::view('/datatables/range-search', 'datatables.range-search');
Route::view('/datatables/skin', 'datatables.skin');
Route::view('/datatables/sticky-header', 'datatables.sticky-header');

Route::view('/forms/basic', 'forms.basic');
Route::view('/forms/input-group', 'forms.input-group');
Route::view('/forms/layouts', 'forms.layouts');
Route::view('/forms/validation', 'forms.validation');
Route::view('/forms/input-mask', 'forms.input-mask');
Route::view('/forms/select2', 'forms.select2');
Route::view('/forms/touchspin', 'forms.touchspin');
Route::view('/forms/checkbox-radio', 'forms.checkbox-radio');
Route::view('/forms/switches', 'forms.switches');
Route::view('/forms/wizards', 'forms.wizards');
Route::view('/forms/file-upload', 'forms.file-upload');
Route::view('/forms/quill-editor', 'forms.quill-editor');
Route::view('/forms/markdown-editor', 'forms.markdown-editor');
Route::view('/forms/date-picker', 'forms.date-picker');
Route::view('/forms/clipboard', 'forms.clipboard');

Route::view('/users/profile', 'users.profile');
Route::view('/users/user-account-settings', 'users.user-account-settings');

Route::view('/pages/knowledge-base', 'pages.knowledge-base');
Route::view('/pages/contact-us-boxed', 'pages.contact-us-boxed');
Route::view('/pages/contact-us-cover', 'pages.contact-us-cover');
Route::view('/pages/faq', 'pages.faq');
Route::view('/pages/coming-soon-boxed', 'pages.coming-soon-boxed');
Route::view('/pages/coming-soon-cover', 'pages.coming-soon-cover');
Route::view('/pages/error404', 'pages.error404');
Route::view('/pages/error500', 'pages.error500');
Route::view('/pages/error503', 'pages.error503');
Route::view('/pages/maintenence', 'pages.maintenence');

Route::view('/auth/boxed-lockscreen', 'auth.boxed-lockscreen');
Route::view('/auth/boxed-signin', 'auth.boxed-signin');
Route::view('/auth/boxed-signup', 'auth.boxed-signup');
Route::view('/auth/boxed-password-reset', 'auth.boxed-password-reset');
Route::view('/auth/cover-login', 'auth.cover-login');
Route::view('/auth/cover-register', 'auth.cover-register');
Route::view('/auth/cover-lockscreen', 'auth.cover-lockscreen');
Route::view('/auth/cover-password-reset', 'auth.cover-password-reset');

Route::get('/dashboard/stamping/{id}', function ($id) {
    return view('dashboard.stamping.page', ['id' => "$id"]);
});

Route::get('/dashboard/assy/{id}', function ($id) {
    return view('dashboard.assy.page', ['id' => "$id"]);
});

Route::get('/dashboard/summary/assy/{id}', function ($id) {
    $db = LogMachine::where('line_id', "$id")
        ->whereNotIn('machine_code', ['RSW-5H45-01', 'RSW-5H45-02', 'RSW-5H45-03', 'RSW-5H45-04', 'RSW-5H45-05', 'RSW-5H45-06'])->get();
    $results = [];
    foreach ($db as $row) {
        $machine_id = $row->machine_id;
        $mc_code = $row->machine_code;
        $plan = $row->qty_plan;
        $actual = $row->qty_actual;
        $sph = $row->current_gsph;
        $dresser_count = $row->dresser_count;
        $spot_count = $row->spot_count;
        $ct = $row->average_ct;
        $bar_progress = ($row->qty_plan > 0 ? number_format($row->qty_actual / $row->qty_plan * 100, 0) : 0);

        $db_log_detail = DB::table('log_detail_machine')
            ->where('job_num', "$row->job_num")
            ->where('machine_id', "$row->machine_id")
            ->orderBy('seq_id', 'desc')
            ->limit(24)
            ->get();

        $ct_log_detail = [];
        $no = 1;
        foreach ($db_log_detail as $row) {
            $cycle_time = ($row->cycle_time == '') ? 0 : str_replace(",", "", number_format($row->cycle_time, 2));
            $ct_log_detail[] = $cycle_time;
            if ($no == 25) {
                break;
            }
            $no++;
        }
        $ct_log_detail = array_reverse($ct_log_detail);
        $ct_log_detail = implode(',', $ct_log_detail);

        $results[] = [
            'machine_id' => "$machine_id",
            'dresser_count' => $dresser_count,
            'spot_count' => $spot_count,
            'mc_code' => $mc_code,
            'plan' => $plan,
            'actual' => $actual,
            'sph' => $sph,
            'ct' => $ct,
            'bar_progress' => $bar_progress,
            'ct_log_detail' => $ct_log_detail
        ];
    }
    return view('dashboard.assy.summary', ['id' => "$id", 'db' => $results]);
});

Route::get('/dashboard/summary/dresser/assy/{id}', function ($id) {
    $db = LogMachine::where('line_id', "$id")->where('is_active', 1)->get();
    $results = [];
    foreach ($db as $row) {
        $machine_id = $row->machine_id;
        $mc_code = $row->machine_code;
        $plan = $row->qty_plan;
        $actual = $row->qty_actual;
        $sph = $row->current_gsph;
        $dresser_count = $row->dresser_count;
        $spot_count = $row->spot_count;
        $ct = $row->average_ct;
        $bar_progress = ($row->qty_plan > 0 ? number_format($row->qty_actual / $row->qty_plan * 100, 0) : 0);

        $db_log_detail = DB::table('log_detail_machine')
            ->where('job_num', "$row->job_num")
            ->where('machine_id', "$row->machine_id")
            ->orderBy('seq_id', 'desc')
            ->limit(24)
            ->get();

        $ct_log_detail = [];
        $no = 1;
        foreach ($db_log_detail as $row) {
            $cycle_time = ($row->cycle_time == '') ? 0 : str_replace(",", "", number_format($row->cycle_time, 2));
            $ct_log_detail[] = $cycle_time;
            if ($no == 25) {
                break;
            }
            $no++;
        }
        $ct_log_detail = array_reverse($ct_log_detail);
        $ct_log_detail = implode(',', $ct_log_detail);

        $results[] = [
            'machine_id' => "$machine_id",
            'dresser_count' => $dresser_count,
            'spot_count' => $spot_count,
            'mc_code' => $mc_code,
            'plan' => $plan,
            'actual' => $actual,
            'sph' => $sph,
            'ct' => $ct,
            'bar_progress' => $bar_progress,
            'ct_log_detail' => $ct_log_detail
        ];
    }
    return view('dashboard.assy.summary_dresser', ['id' => "$id", 'db' => $results]);
});

Route::get('/dashboard/summary/stamping/{id}', function ($id) {
    $db = LogMachine::where('line_id', "$id")->where('is_active', 1)->get();
    $results = [];
    foreach ($db as $row) {
        $machine_id = $row->machine_id;
        $mc_code = $row->machine_code;
        $plan = $row->qty_plan;
        $actual = $row->qty_actual;
        $sph = $row->current_gsph;
        $ct = $row->average_ct;
        $bar_progress = ($row->qty_plan > 0 ? number_format($row->qty_actual / $row->qty_plan * 100, 0) : 0);

        $db_log_detail = DB::table('log_detail_machine')
            ->where('job_num', "$row->job_num")
            ->where('machine_id', "$row->machine_id")
            ->orderBy('seq_id', 'desc')
            ->limit(24)
            ->get();

        $ct_log_detail = [];
        $no = 1;
        foreach ($db_log_detail as $row) {
            $cycle_time = ($row->cycle_time == '') ? 0 : str_replace(",", "", number_format($row->cycle_time, 2));
            $ct_log_detail[] = $cycle_time;
            if ($no == 25) {
                break;
            }
            $no++;
        }
        $ct_log_detail = array_reverse($ct_log_detail);
        $ct_log_detail = implode(',', $ct_log_detail);

        $results[] = [
            'machine_id' => "$machine_id",
            'mc_code' => $mc_code,
            'plan' => $plan,
            'actual' => $actual,
            'sph' => $sph,
            'ct' => $ct,
            'bar_progress' => $bar_progress,
            'ct_log_detail' => $ct_log_detail
        ];
    }
    return view('dashboard.stamping.summary', ['id' => "$id", 'db' => $results]);
});



// Route::get('/dashboard/config', function () {
//     return view('dashboard.config.config');
// });
Route::get('/dashboard/ppm-dashboard', function () {
    return view('dashboard.ppm.ppm_monitoring');
});

Route::post('/api/check_pin_sales', [DeptPinController::class, 'check_pin_sales'])
    ->middleware('web');

Route::get('/dashboard/sales', function () {
    return view('dashboard.sales.sales_monitoring');
})->middleware(['department:sales']);

Route::post('/api/check_pin_purchasing', [DeptPinController::class, 'check_pin_purchasing'])
    ->middleware('web');

Route::get('/dashboard/purchasing/report', function () {
    return view('dashboard.purchasing.purchasing_report');
})->middleware('department:purchasing');

Route::get('/dashboard/purchasing/monitoring_pr', function () {
    return view('dashboard.purchasing.monitoring_pr');
});

Route::get('/dashboard/purchasing/po_project_monitoring', function () {
    return view('dashboard.purchasing.po_project_monitoring');
});

Route::get('/dashboard/purchasing/po_ppic_monitoring', function () {
    return view('dashboard.purchasing.po_ppic_monitoring');
});

Route::get('/dashboard/purchasing/po_reguler_monitoring', function () {
    return view('dashboard.purchasing.po_reguler_monitoring');
});

Route::post('/api/check_pin_ppic_job', [DeptPinController::class, 'check_pin_ppic_job'])
    ->middleware('web');

Route::get('/dashboard/ppic', function () {
    return view('dashboard.ppic.job_monitoring');
})->middleware('department:ppic_job');

Route::post('/api/check_pin_ppic_stock', [DeptPinController::class, 'check_pin_ppic_stock'])
    ->middleware('web');

Route::get('/dashboard/ppic/stock', function () {
    return view('dashboard.ppic.stock_monitoring');
})->middleware('department:ppic_stock');

Route::post('/api/check_pin_production', [DeptPinController::class, 'check_pin_production'])
    ->middleware('web');

Route::get('/dashboard/production', function () {
    return view('dashboard.production.production_achievment');
})->middleware('department:production');

Route::post('/api/check_pin_delivery', [DeptPinController::class, 'check_pin_delivery'])
    ->middleware('web');

Route::get('/dashboard/delivery', function () {
    return view('dashboard.delivery.delivery_forcast');
})->middleware('department:delivery');

Route::post('/api/check_pin_delivery_job', [DeptPinController::class, 'check_pin_delivery_job'])
    ->middleware('web');

Route::get('/dashboard/delivery/job_monitoring', function () {
    return view('dashboard.delivery.job_monitoring');
})->middleware('department:delivery_job');

Route::post('/api/check_pin_delivery_monitoring', [DeptPinController::class, 'check_pin_delivery_monitoring'])
    ->middleware('web');

Route::get('/dashboard/delivery/delivery_monitoring', function () {
    return view('dashboard.delivery.delivery_monitoring');
})->middleware('department:delivery_monitoring');

Route::post('/api/check_pin_delivery_finish_good', [DeptPinController::class, 'check_pin_delivery_finish_good'])
    ->middleware('web');

Route::get('/dashboard/delivery/finish_good', function () {
    return view('dashboard.delivery.finish_good');
})->middleware('department:delivery_finish_good');

Route::post('/api/check_pin_delivery_mit', [DeptPinController::class, 'check_pin_delivery_mit'])
    ->middleware('web');

Route::get('/dashboard/delivery/mit_dashboard', function () {
    return view('dashboard.delivery.mit_dashboard');
})->middleware('department:delivery_mit');

Route::get('dashboard/delivery/cgr_monitoring', function () {
    return view('dashboard.delivery.cgr_monitoring');
});

Route::post('/api/check_pin_finance_invoice', [DeptPinController::class, 'check_pin_finance_invoice'])
    ->middleware('web');
Route::get('/dashboard/profitability/Invoice', function () {
    return view('dashboard.finance.profitability_invoice_monitoring');
})->middleware('department:finance_invoice');

Route::post('/api/check_pin_finance_model', [DeptPinController::class, 'check_pin_finance_model'])
    ->middleware('web');

Route::get('/dashboard/profitability/Model', function () {
    return view('dashboard.finance.profitability_model');
})->middleware('department:finance_model');

Route::post('/api/check_pin_finance_rcd', [DeptPinController::class, 'check_pin_finance_rcd'])
    ->middleware('web');

Route::get('/dashboard/profitability/rcd', function () {
    return view('dashboard.finance.reasonable_costdown');
})->middleware('department:finance_rcd');

Route::get('/dashboard/qms/genba_monitoring', function () {
    return view('dashboard.qms.genba_monitoring');
});

//Route For Stamping v2
Route::prefix('/stamping')->group(function () {
    Route::get('/page/{id}', function () {
        return view('v2.page.page');
    });
    Route::get('/dashboard/{id}', function () {
        return view('v2.dashboard.main-dashboard');
    });
    Route::get('/dashboard/{id}/{tool}', function () {
        return view('v2.dashboard.dashboard-tool');
    });
    Route::get('dashboard-machine/{id}', function () {
        return view('v2.dashboard.machine-dashboard');
    });
    Route::get('/dashboard/{machine_id}/{job_num}/{production_date}/{shift}', function () {
        return view('v2.dashboard.history-dashboard');
    });
    Route::get('/summary-by-line/{id}', function () {
        return view('v2.summary.summary');
    });
    Route::get('/dashboard-by-machine/{id}', function () {
        return view('v2.summary.dashboard');
    });
    Route::get('/machine/{id}', function () {
        // return view('v2.machine.machine');
        return view('v2.machine.trial-time-entry-v2');
    });
    // Route::get('trial-time-entry/{id}', function () {
    //     return view('v2.machine.trial-time-entry-v2');
    // });
});
//Route For ASSY v2
Route::prefix('/assy')->group(function () {
    Route::get('/page/{id}', function () {
        return view('v2.page.page');
    });
    Route::get('/dashboard/{id}', function () {
        return view('v2.dashboard.main-dashboard');
    });
    Route::get('/dashboard/{id}/{tool}', function () {
        return view('v2.dashboard.dashboard-tool');
    });
    Route::get('dashboard-machine/{id}', function () {
        return view('v2.dashboard.machine-dashboard');
    });
    Route::get('/dashboard/{machine_id}/{job_num}/{production_date}/{shift}', function () {
        return view('v2.dashboard.history-dashboard');
    });
    Route::get('/dashboard/{machine_id}/{tool_id}/{job_num}/{production_date}/{shift}', function () {
        return view('v2.dashboard.history-dashboard-tool');
    });
    Route::get('/summary-by-line/{id}', function () {
        return view('v2.summary.summary');
    });
    Route::get('/dashboard-by-machine/{id}', function () {
        return view('v2.summary.dashboard');
    });
    Route::get('/machine/{id}', function () {
        return view('v2.machine.machine');
        // return view('v2.machine.trial-time-entry-v2');
    });
    Route::get('trial-time-entry/{id}', function () {
        // return view('v2.machine.machine');
        return view('v2.machine.trial-time-entry-v2');
    });
});
//Route for setup
Route::prefix('configuration')->group(function () {
    Route::get('standard-setup', function () {
        return view('v2.setup.standard-setup');
    });
    Route::get('special-setup', function () {
        return view('v2.setup.special-setup');
    });
});
//Export To Excel
Route::post('machine/export-history-log', [LogMachineController::class, 'exportHistoryTable']);
// Route::prefix('one-point-lesson')->group(function () {
//     Route::get('part', function () {
//         return view('dashboard.delivery.one-point-lesson.part');
//     });
//     Route::post('part/data-table', [DeliveryController::class, 'dataTablePart']);
// });
Route::prefix('one-point-lesson')->group(function () {
    Route::get('part', function () {
        return view('dashboard.delivery.one-point-lesson.part');
    });
    Route::get('dashboard', function () {
        return view('dashboard.delivery.one-point-lesson.index');
    });
    Route::get('scan-form', function () {
        return view('dashboard.delivery.one-point-lesson.scan_form');
    });
    Route::post('part/data-table', [DeliveryController::class, 'dataTablePart']);
    Route::post('part/list', [DeliveryController::class, 'PartList']);
    Route::post('part/create', [DeliveryController::class, 'PartCreate']);
    Route::post('part-list', [DeliveryController::class, 'PartListCard']);
    Route::post('part/relation', [DeliveryController::class, 'PartRelation']);
    Route::get('qr_view/{id}', [DeliveryController::class, 'QrView']);
    Route::post('part/show_data', [DeliveryController::class, 'show_data']);
});
Route::prefix('config')->group(function () {
    Route::get('/', function () {
        return view('config.index');
    });
});
Route::prefix('standard_operational_procedure')->group(function () {
    Route::get('', function () {
        return view('menu_sop.index');
    });
    Route::get('/{dtl}', function ($dtl) {
        $dtl = Crypt::decryptString($dtl);
        $dtl = explode('~', $dtl);
        $Part = $dtl[1];
        $data = DB::table('SOP')
            ->where('PartNum', $Part)
            ->get();
        return view('menu_sop.view', [
            'data' => $data,
            'Part' => $Part
        ]);
    });
});
Route::get('dashboard-profile/', function () {
    $user_id = Auth::user()->id;
    return view('dashboard.profile.index', ['user_id', $user_id]);
})->middleware('auth');
Route::get('user-management',function(){
    return view('dashboard.user_management.index');
})->middleware('auth');
Route::prefix('auth')->group(function () {
    Route::view('login', 'auth.login')->name('login');
    Route::post('login', [AuthController::class, 'login']);
});
