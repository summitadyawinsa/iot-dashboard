<?php

use App\Http\Controllers\Api\V1\LogMachineController;
use App\Http\Controllers\Api\V1\FinanceController;
use App\Http\Controllers\Api\V1\SalesController;
use App\Http\Controllers\Api\V1\DeliveryController;
use App\Http\Controllers\Api\V1\PPICController;
use App\Http\Controllers\Api\V1\ProductionController;
use App\Http\Controllers\Api\V1\PurchasingController;
use App\Http\Controllers\Api\V1\QMSController;
use App\Http\Controllers\Api\V1\DeptPinController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\HealthConditionController;
use App\Http\Controllers\JobNumController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/update_pin_delivery', [DeptPinController::class, 'update_pin_delivery']);
Route::post('/update_pin_delivery_job', [DeptPinController::class, 'update_pin_delivery_job']);
Route::post('/update_pin_delivery_finish_good', [DeptPinController::class, 'update_pin_delivery_finish_good']);
Route::post('/update_pin_delivery_mit', [DeptPinController::class, 'update_pin_delivery_mit']);
Route::post('/update_pin_delivery_cgr', [DeptPinController::class, 'update_pin_delivery_cgr']);
Route::post('/update_pin_sales', [DeptPinController::class, 'update_pin_sales']);
Route::post('/update_pin_ppic_job', [DeptPinController::class, 'update_pin_ppic_job']);
Route::post('/update_pin_ppic_stock', [DeptPinController::class, 'update_pin_ppic_stock']);
Route::post('/update_pin_production', [DeptPinController::class, 'update_pin_production']);
Route::post('/update_pin_purchasing', [DeptPinController::class, 'update_pin_purchasing']);
Route::post('/update_pin_purchasing_pr', [DeptPinController::class, 'update_pin_purchasing_pr']);
Route::post('/update_pin_purchasing_project', [DeptPinController::class, 'update_pin_purchasing_project']);
Route::post('/update_pin_purchasing_ppic', [DeptPinController::class, 'update_pin_purchasing_ppic']);
Route::post('/update_pin_purchasing_reguler', [DeptPinController::class, 'update_pin_purchasing_reguler']);
Route::post('/update_pin_finance_profit', [DeptPinController::class, 'update_pin_finance_profit']);
Route::post('/update_pin_finance_invoice', [DeptPinController::class, 'update_pin_finance_invoice']);
Route::post('/update_pin_finance_model', [DeptPinController::class, 'update_pin_finance_model']);
Route::post('/update_pin_finance_rcd', [DeptPinController::class, 'update_pin_finance_rcd']);

Route::get('machine_list', [LogMachineController::class, 'list_log_machine']);
Route::post('machine', [LogMachineController::class, 'store']);
Route::get('machine/{id}', [LogMachineController::class, 'show']);
Route::put('machine/update', [LogMachineController::class, 'update']);
Route::put('machine/set_job_number', [LogMachineController::class, 'set_job_number']);
Route::post('machine/status', [LogMachineController::class, 'check_status_machine']);
Route::post('machine/set_status', [LogMachineController::class, 'set_status_machine']);
Route::post('machine/log_activity', [LogMachineController::class, 'log_activity']);
Route::post('machine/set_log_activity', [LogMachineController::class, 'set_log_activity']);
Route::post('machine/schedule', [LogMachineController::class, 'get_sch_production']);
Route::post('machine/machine_list_by_category/{id}', [LogMachineController::class, 'machine_list_by_category']);
Route::post('machine/jo_list', [LogMachineController::class, 'job_num_list']);
Route::post('machine/shift_list', [LogMachineController::class, 'shift_list']);
Route::post('machine/downtime_list', [LogMachineController::class, 'downtime_list']);
Route::put('machine/set_downtime', [LogMachineController::class, 'set_downtime']);
Route::post('machine/get_property', [LogMachineController::class, 'get_property']);
Route::post('machine/get_avail_time', [LogMachineController::class, 'get_avail_time']);
Route::put('machine/set_finish', [LogMachineController::class, 'set_finish']);
// Route::get('summary_by_line/{id}', [LogMachineController::class, 'summary_by_line']);
Route::post('get_profile_line', [LogMachineController::class, 'get_profile_line']);
Route::put('machine/mass_update', [LogMachineController::class, 'mass_update']);
Route::put('machine/mass_dresser_update', [LogMachineController::class, 'mass_dresser_update']);
Route::post('ppm/get-count-doc', [LogMachineController::class, 'getCountDoc']);
Route::post('ppm/list', [LogMachineController::class, 'getPPMList']);
Route::get('andon_notif/{id}', [LogMachineController::class, 'andon_notif']);

Route::get('finance/get_profit_yearly/{id}', [FinanceController::class, 'get_profit_yearly']);
Route::get('finance/get_profit_model_yearly/{id}', [FinanceController::class, 'get_profit_model_yearly']);
Route::get('finance/get_profit_model_monthly/{id}', [FinanceController::class, 'get_profit_model_monthly']);
Route::get('finance/get_profit_monthly/{id}', [FinanceController::class, 'get_profit_monthly']);
Route::get('finance/get_profit_cust_yearly/{id}', [FinanceController::class, 'get_profit_cust_yearly']);
Route::get('finance/get_profit_cust_monthly/{id}', [FinanceController::class, 'get_profit_cust_monthly']);
Route::get('finance/get_profit_cust_date/{id}', [FinanceController::class, 'get_profit_cust_date']);
Route::post('finance/get_sales_cost_table', [FinanceController::class, 'get_sales_cost_table']);

#region Invoice Profithttps://firewall.summitadyawinsa.co.id:8090/httpclient.html
Route::get('finance/get_profit_invoice_yearly/{id}', [FinanceController::class, 'get_profit_invoice_yearly']);
Route::get('finance/get_profit_category_month/{id}', [FinanceController::class, 'get_profit_category_month']);
Route::get('finance/get_profit_invoice_cust_monthly/{id}', [FinanceController::class, 'get_profit_invoice_cust_monthly']);
Route::get('finance/get_invoice_profit_monthly/{id}', [FinanceController::class, 'get_invoice_profit_monthly']);
Route::get('finance/get_profit_invoice_cust_yearly/{id}', [FinanceController::class, 'get_profit_invoice_cust_yearly']);
Route::get('finance/get_invoice_profit_cust_date/{id}', [FinanceController::class, 'get_invoice_profit_cust_date']);
Route::get('finance/get_profit_category_year/{id}', [FinanceController::class, 'get_profit_category_year']);
Route::post('finance/get_invoice_cost_table', [FinanceController::class, 'get_invoice_cost_table']);
Route::post('finance/get_profit_model_by_yearly_table', [FinanceController::class, 'get_profit_model_by_yearly_table']);
Route::post('finance/get_profit_category_by_yearly_table', [FinanceController::class, 'get_profit_category_by_yearly_table']);
Route::post('finance/get_profit_model_by_monthly_table', [FinanceController::class, 'get_profit_model_by_monthly_table']);
Route::post('finance/get_profit_category_by_monthly_table', [FinanceController::class, 'get_profit_category_by_monthly_table']);
Route::post('finance/get_profit_model_by_yearly_export', [FinanceController::class, 'get_profit_model_by_yearly_export']);
Route::post('finance/get_profit_model_by_month_export', [FinanceController::class, 'get_profit_model_by_month_export']);
Route::post('finance/get_profit_by_year_table_export', [FinanceController::class, 'get_profit_by_year_table_export']);
Route::post('finance/get_profit_by_month_table_export', [FinanceController::class, 'get_profit_by_month_table_export']);
Route::post('finance/get_profit_category_by_yearly_table_export', [FinanceController::class, 'get_profit_category_by_yearly_table_export']);
Route::post('finance/get_invoice_cost_table_detail', [FinanceController::class, 'get_invoice_cost_table_detail']);
Route::post('finance/get_invoice_cost_table_export', [FinanceController::class, 'get_invoice_cost_table_export']);
Route::post('finance/profit_invoice_cust_yearly_table_export', [FinanceController::class, 'profit_invoice_cust_yearly_table_export']);
Route::post('finance/get_profit_category_by_monthly_table_export', [FinanceController::class, 'get_profit_category_by_monthly_table_export']);
Route::post('finance/get_profit_model_by_monthly_export', [FinanceController::class, 'get_profit_model_by_monthly_export']);
Route::post('finance/profit_invoice_cust_month_table_export', [FinanceController::class, 'profit_invoice_cust_month_table_export']);
Route::get('finance/get_RCDPerYears', [FinanceController::class, 'get_RCDPerYears']);
Route::post('finance/get_invoice_summary_table', [FinanceController::class, 'get_invoice_summary_table']);
Route::post('finance/get_rcdYears_summary_table', [FinanceController::class, 'get_rcdYears_summary_table']);
Route::post('finance/get_transactionEffect_table', [FinanceController::class, 'get_transactionEffect_table']);
Route::post('finance/get_transactionCategory_table', [FinanceController::class, 'get_transactionCategory_table']);
Route::post('finance/get_transaction_CategoryAccum_table', [FinanceController::class, 'get_transaction_CategoryAccum_table']);
Route::post('finance/get_transaction_EffectAccum_table', [FinanceController::class, 'get_transaction_EffectAccum_table']);
Route::post('finance/get_transaction_summaryMonth_table', [FinanceController::class, 'get_transaction_summaryMonth_table']);
Route::post('finance/get_transaction_activity_table', [FinanceController::class, 'get_transaction_activity_table']);
Route::post('finance/get_transaction_dept_table', [FinanceController::class, 'get_transaction_dept_table']);
Route::post('finance/get_transaction_deptAccum_table', [FinanceController::class, 'get_transaction_deptAccum_table']);
Route::post('finance/get_transaction_accum_detail', [FinanceController::class, 'get_transaction_accum_detail']);
Route::post('finance/get_transaction_category_detail', [FinanceController::class, 'get_transaction_category_detail']);
Route::post('finance/profit_invoice_cust_month_table', [FinanceController::class, 'profit_invoice_cust_month_table']);
Route::post('finance/get_transaction_effect_accum_detail', [FinanceController::class, 'get_transaction_effect_accum_detail']);
Route::post('finance/get_transaction_effect_detail', [FinanceController::class, 'get_transaction_effect_detail']);
Route::post('finance/get_profit_by_year_table', [FinanceController::class, 'get_profit_by_year_table']);
Route::post('finance/get_profit_by_month_table', [FinanceController::class, 'get_profit_by_month_table']);
Route::post('finance/profit_invoice_cust_yearly_table', [FinanceController::class, 'profit_invoice_cust_yearly_table']);
Route::post('finance/get_profitability_by_year_table', [FinanceController::class, 'get_profitability_by_year_table']);
Route::post('finance/get_profitability_by_month_table', [FinanceController::class, 'get_profitability_by_month_table']);
Route::post('finance/profitability_invoice_cust_yearly_table', [FinanceController::class, 'profitability_invoice_cust_yearly_table']);
Route::post('finance/profitability_invoice_cust_month_table', [FinanceController::class, 'profitability_invoice_cust_month_table']);
Route::post('finance/get_profitability_model_by_yearly_table', [FinanceController::class, 'get_profitability_model_by_yearly_table']);
Route::post('finance/get_profitability_model_by_monthly_table', [FinanceController::class, 'get_profitability_model_by_monthly_table']);
Route::post('finance/get_profitability_category_by_yearly_table', [FinanceController::class, 'get_profitability_category_by_yearly_table']);
Route::post('finance/get_profitability_category_by_month_table', [FinanceController::class, 'get_profitability_category_by_month_table']);
Route::post('finance/get_invoice_expenses_table', [FinanceController::class, 'get_invoice_expenses_table']);

Route::get('finance/get_RCDPerMonthAccum/{id}', [FinanceController::class, 'get_RCDPerMonthAccum']);
Route::get('finance/get_RCDPerYearsCategory/{id}', [FinanceController::class, 'get_RCDPerYearsCategory']);
Route::get('finance/get_transaction_effect/{id}', [FinanceController::class, 'get_transaction_effect']);
Route::get('finance/get_transaction_Category/{id}', [FinanceController::class, 'get_transaction_Category']);
Route::get('finance/get_transaction_CategoryAccum/{id}', [FinanceController::class, 'get_transaction_CategoryAccum']);
Route::get('finance/get_transaction_EffectAccum/{id}', [FinanceController::class, 'get_transaction_EffectAccum']);
Route::get('finance/get_transaction_summaryMonth/{id}', [FinanceController::class, 'get_transaction_summaryMonth']);
Route::get('finance/get_rcd_permonth/{id}', [FinanceController::class, 'get_rcd_permonth']);
Route::get('finance/get_transaction_activity/{id}', [FinanceController::class, 'get_transaction_activity']);
Route::get('finance/get_transaction_dept/{id}', [FinanceController::class, 'get_transaction_dept']);
Route::get('finance/get_transaction_deptAccum/{id}', [FinanceController::class, 'get_transaction_deptAccum']);
Route::get('finance/get_profitability_yearly/{id}', [FinanceController::class, 'get_profitability_yearly']);
Route::get('finance/get_profitability_monthly/{id}', [FinanceController::class, 'get_profitability_monthly']);
Route::get('finance/get_profitability_invoice_cust_yearly/{id}', [FinanceController::class, 'get_profitability_invoice_cust_yearly']);
Route::get('finance/get_profitability_invoice_cust_monthly/{id}', [FinanceController::class, 'get_profitability_invoice_cust_monthly']);
Route::get('finance/get_profitability_model_yearly/{id}', [FinanceController::class, 'get_profitability_model_yearly']);
Route::get('finance/get_profitability_model_monthly/{id}', [FinanceController::class, 'get_profitability_model_monthly']);
Route::get('finance/get_profitability_category_year/{id}', [FinanceController::class, 'get_profitability_category_year']);
Route::get('finance/get_profitability_category_month/{id}', [FinanceController::class, 'get_profitability_category_month']);
#endregion

#region Sales
Route::get('sales/get_profit_yearly/{id}', [SalesController::class, 'get_profit_yearly']);
Route::get('sales/get_profit_monthly/{id}', [SalesController::class, 'get_profit_monthly']);
Route::get('sales/get_profit_cust_yearly/{id}', [SalesController::class, 'get_profit_cust_yearly']);
Route::get('sales/get_profit_cust_monthly/{id}', [SalesController::class, 'get_profit_cust_monthly']);
Route::get('sales/get_profit_cust_date/{id}', [SalesController::class, 'get_profit_cust_date']);
Route::post('sales/get_sales_cost_table', [SalesController::class, 'get_sales_cost_table']);
#endregion

#region Delivery
Route::get('delivery/get_forecast_order_Monhtly/{id}', [DeliveryController::class, 'get_forecast_order_Monhtly']);
Route::get('delivery/get_forecast_order_Daily/{id}', [DeliveryController::class, 'get_forecast_order_Daily']);
Route::get('delivery/get_data_job_monthly/{id}', [DeliveryController::class, 'get_data_job_monthly']);
Route::get('delivery/get_data_job_daily_select/{id}', [DeliveryController::class, 'get_data_job_daily_select']);
Route::get('delivery/get_data_job_daily', [DeliveryController::class, 'get_data_job_daily']);
Route::get('delivery/det_moitoring_control_delivery/{id}', [DeliveryController::class, 'det_moitoring_control_delivery']);
Route::get('delivery/get_stok_monitoring', [DeliveryController::class, 'get_stok_monitoring']);
Route::get('delivery/get_delivery_table_monthly/{id}', [DeliveryController::class, 'get_delivery_table_monthly']);
Route::post('delivery/get_delivery_Table', [DeliveryController::class, 'get_delivery_Table']);
Route::post('delivery/get_delivery_job_monitoring_table', [DeliveryController::class, 'get_delivery_job_monitoring_table']);
Route::post('delivery/get_control_delivery_table/{customer}', [DeliveryController::class, 'get_control_delivery_table']);
Route::post('delivery/get_control_delivery_table_summary/{customer}', [DeliveryController::class, 'get_control_delivery_table_summary']);
Route::post('delivery/get_control_delivery_min_max_summary', [DeliveryController::class, 'get_control_delivery_min_max_summary']);
Route::post('delivery/get_stock_monitoring_table', [DeliveryController::class, 'get_stock_monitoring_table']);
Route::post('delivery/get_data_mit_dashboard_table', [DeliveryController::class, 'get_data_mit_dashboard_table']);
Route::post('delivery/get_data_gcr_monitoring_table', [DeliveryController::class, 'get_data_gcr_monitoring_table']);
Route::get('delivery/get_data_finish_good', [DeliveryController::class, 'get_data_finish_good']);
Route::get('delivery/get_data_mit_dashboard/{id}', [DeliveryController::class, 'get_data_mit_dashboard']);
Route::get('delivery/get_data_monitoring_cgr/{id}', [DeliveryController::class, 'get_data_monitoring_cgr']);
#endregion

#region Production
Route::get('production/get_production_achiev_Year/{year}', [ProductionController::class, 'get_production_achiev_Year']);
Route::get('production/get_production_achiev_Month/{year}', [ProductionController::class, 'get_production_achiev_Month']);
Route::get('production/get_production_achiev_Daily/{id}', [ProductionController::class, 'get_production_achiev_Daily']);
Route::post('production/get_production_achiev_Table', [ProductionController::class, 'get_production_achiev_Table']);
#endregion

#region PPIC
Route::get('ppic/get_ppic_monitoring_Monthly/{id}', [PPICController::class, 'get_ppic_monitoring_Monthly']);

Route::get('ppic/get_ppic_monitoring_Days/{id}', [PPICController::class, 'get_ppic_monitoring_Days']);
Route::get('ppic/get_stock_monitoring/{id}', [PPICController::class, 'get_stock_monitoring']);
Route::get('ppic/get_stock_monitoring_warehouse', [PPICController::class, 'get_stock_monitoring_warehouse']);
Route::post('ppic/get_ppic_monitoring_Table_All', [PPICController::class, 'get_ppic_monitoring_Table_All']);
Route::post('ppic/get_ppic_monitoring_Table', [PPICController::class, 'get_ppic_monitoring_Table']);
Route::post('ppic/get_stock_monitoring_table', [PPICController::class, 'get_stock_monitoring_table']);
Route::post('ppic/get_ppic_stock_Table', [PPICController::class, 'get_ppic_stock_Table']);
Route::get('ppic/get_ppic_monitoring_table_close_open/{id}', [PPICController::class, 'get_ppic_monitoring_table_close_open']);
Route::get('ppic/get_data_month_departement/{id}', [PPICController::class, 'get_data_month_departement']);
Route::get('ppic/get_data_day_departement/{id}', [PPICController::class, 'get_data_day_departement']);





#region Purchasing
Route::get('purchasing/get_purchasing_actualYears/{id}', [PurchasingController::class, 'get_purchasing_actualYears']);
Route::get('purchasing/get_purchasing_actualMonth/{id}', [PurchasingController::class, 'get_purchasing_actualMonth']);
Route::get('purchasing/get_purchasing_pocategoryYear/{id}', [PurchasingController::class, 'get_purchasing_pocategoryYear']);
Route::get('purchasing/get_purchasing_pocategoryMonth/{id}', [PurchasingController::class, 'get_purchasing_pocategoryMonth']);
Route::post('purchasing/get_purchase_po_month', [PurchasingController::class, 'get_purchase_po_month']);
Route::post('purchasing/get_purchase_po_year', [PurchasingController::class, 'get_purchase_po_year']);
Route::post('purchasing/get_purchase_category_year', [PurchasingController::class, 'get_purchase_category_year']);
Route::post('purchasing/get_purchase_category_month', [PurchasingController::class, 'get_purchase_category_month']);
Route::post('purchasing/get_table_month_stack', [PurchasingController::class, 'get_table_month_stack']);
Route::post('purchasing/get_purchase_stack_bymonth', [PurchasingController::class, 'get_purchase_stack_bymonth']);
Route::post('purchasing/get_purchase_po_project', [PurchasingController::class, 'get_purchase_po_project']);
Route::post('purchasing/get_purchase_data_po_project_ammount', [PurchasingController::class, 'get_purchase_data_po_project_ammount']);
Route::post('purchasing/get_received_po_project', [PurchasingController::class, 'get_received_po_project']);
Route::post('purchasing/get_data_po_project_table_gr', [PurchasingController::class, 'get_data_po_project_table_gr']);
Route::post('purchasing/get_data_purchasing_po_ppic_table', [PurchasingController::class, 'get_data_purchasing_po_ppic_table']);
Route::post('purchasing/get_data_purchasing_po_ppic_table_export', [PurchasingController::class, 'get_data_purchasing_po_ppic_table_export']);
Route::post('purchasing/get_purchase_data_po_ppic_ammount_export', [PurchasingController::class, 'get_purchase_data_po_ppic_ammount_export']);
Route::post('purchasing/get_purchase_po_ppic_export', [PurchasingController::class, 'get_purchase_po_ppic_export']);
Route::post('purchasing/get_purchase_po_ppic', [PurchasingController::class, 'get_purchase_po_ppic']);
Route::post('purchasing/get_purchase_data_po_ppic_ammount', [PurchasingController::class, 'get_purchase_data_po_ppic_ammount']);
Route::post('purchasing/get_data_purchasing_po_reguler_table', [PurchasingController::class, 'get_data_purchasing_po_reguler_table']);
Route::post('purchasing/get_purchase_po_reguler_export', [PurchasingController::class, 'get_purchase_po_reguler_export']);
Route::post('purchasing/get_data_purchasing_po_reguler_table_export', [PurchasingController::class, 'get_data_purchasing_po_reguler_table_export']);
Route::post('purchasing/get_purchase_po_reguler', [PurchasingController::class, 'get_purchase_po_reguler']);
Route::post('purchasing/get_purchase_data_po_reguler_ammount', [PurchasingController::class, 'get_purchase_data_po_reguler_ammount']);
Route::post('purchasing/get_purchase_data_po_reguler_ammount_export', [PurchasingController::class, 'get_purchase_data_po_reguler_ammount_export']);
Route::post('purchasing/get_purchase_po_project_export', [PurchasingController::class, 'get_purchase_po_project_export']);
Route::post('purchasing/get_received_po_project_export', [PurchasingController::class, 'get_received_po_project_export']);
Route::post('purchasing/get_data_po_project_table_gr_export', [PurchasingController::class, 'get_data_po_project_table_gr_export']);
Route::post('purchasing/get_purchase_data_po_project_ammount_export', [PurchasingController::class, 'get_purchase_data_po_project_ammount_export']);
Route::post('purchasing/get_data_purchasing_req_po_status_table_export', [PurchasingController::class, 'get_data_purchasing_req_po_status_table_export']);
Route::post('purchasing/get_data_purchasing_req_under_po_table_export', [PurchasingController::class, 'get_data_purchasing_req_under_po_table_export']);
Route::post('purchasing/get_data_purchasing_req_po_status_table', [PurchasingController::class, 'get_data_purchasing_req_po_status_table']);
Route::post('purchasing/get_data_purchasing_req_under_po_table', [PurchasingController::class, 'get_data_purchasing_req_under_po_table']);
Route::post('purchasing/get_data_purchasing_tracking_req_po_table', [PurchasingController::class, 'get_data_purchasing_tracking_req_po_table']);
Route::post('purchasing/get_data_purchasing_matrics_po_table', [PurchasingController::class, 'get_data_purchasing_matrics_po_table']);
Route::post('purchasing/get_data_purchasing_suggest_po', [PurchasingController::class, 'get_data_purchasing_suggest_po']);
Route::post('purchasing/get_data_purchasing_tracking_req_po_table_export', [PurchasingController::class, 'get_data_purchasing_tracking_req_po_table_export']);
Route::post('purchasing/get_data_purchasing_req_only_po_status_table', [PurchasingController::class, 'get_data_purchasing_req_only_po_status_table']);
Route::post('purchasing/get_data_po_approval_ppic', [PurchasingController::class, 'get_data_po_approval_ppic']);
Route::post('purchasing/get_data_po_approval_reguler', [PurchasingController::class, 'get_data_po_approval_reguler']);
Route::post('purchasing/get_data_po_approval_project', [PurchasingController::class, 'get_data_po_approval_project']);
Route::post('purchasing/get_data_po_approval_ppic_export', [PurchasingController::class, 'get_data_po_approval_ppic_export']);
Route::post('purchasing/get_data_po_approval_project_export', [PurchasingController::class, 'get_data_po_approval_project_export']);
Route::post('purchasing/get_data_po_approval_reguler_export', [PurchasingController::class, 'get_data_po_approval_reguler_export']);
Route::get('purchasing/get_purchasing_pocategoryMonth_Stack/{id}', [PurchasingController::class, 'get_purchasing_pocategoryMonth_Stack']);
Route::get('purchasing/get_purchasing_pocategoryByMonth/{id}', [PurchasingController::class, 'get_purchasing_pocategoryByMonth']);
Route::get('purchasing/get_data_po_gr_project', [PurchasingController::class, 'get_data_po_gr_project']);
Route::get('purchasing/get_data_purchase_po_ppic', [PurchasingController::class, 'get_data_purchase_po_ppic']);
Route::get('purchasing/get_data_purchase_po_reguler', [PurchasingController::class, 'get_data_purchase_po_reguler']);
Route::get('purchasing/get_data_po_project_receipt/{id}', [PurchasingController::class, 'get_data_po_project_receipt']);
Route::get('purchasing/get_data_po_ppic_receipt/{id}', [PurchasingController::class, 'get_data_po_ppic_receipt']);
Route::get('purchasing/get_data_po_reguler_receipt/{id}', [PurchasingController::class, 'get_data_po_reguler_receipt']);
Route::get('purchasing/get_data_po_project_aging', [PurchasingController::class, 'get_data_po_project_aging']);
Route::get('purchasing/get_data_po_ppic_aging', [PurchasingController::class, 'get_data_po_ppic_aging']);
Route::get('purchasing/get_data_po_reguler_aging', [PurchasingController::class, 'get_data_po_reguler_aging']);
Route::get('purchasing/get_data_summary_status_req/{id}', [PurchasingController::class, 'get_data_summary_status_req']);
Route::get('purchasing/get_data_req_po_pipeline/{id}', [PurchasingController::class, 'get_data_req_po_pipeline']);
Route::get('purchasing/get_data_req_under_po/{id}', [PurchasingController::class, 'get_data_req_under_po']);
Route::get('purchasing/get_data_po_ppic_approval/{id}', [PurchasingController::class, 'get_data_po_ppic_approval']);
Route::get('purchasing/get_data_po_reguler_approval/{id}', [PurchasingController::class, 'get_data_po_reguler_approval']);
Route::get('purchasing/get_data_po_project_approval/{id}', [PurchasingController::class, 'get_data_po_project_approval']);

#endregion


#region QMS
Route::get('qms/get_all_dept_open_overdue/{year}', [QMSController::class, 'get_all_dept_open_overdue']);
Route::get('qms/get_all_dept_open_remain/{year}', [QMSController::class, 'get_all_dept_open_remain']);
Route::get('qms/get_total_genba_dept/{year}', [QMSController::class, 'get_total_genba_dept']);
Route::get('qms/get_total_genba_dept_dpc/{year}', [QMSController::class, 'get_total_genba_dept_dpc']);
Route::get('qms/get_total_genba_dept_assy/{year}', [QMSController::class, 'get_total_genba_dept_assy']);
Route::get('qms/get_total_genba_dept_stp/{year}', [QMSController::class, 'get_total_genba_dept_stp']);
Route::get('qms/get_total_genba_dept_mtc/{year}', [QMSController::class, 'get_total_genba_dept_mtc']);
Route::get('qms/get_total_genba_dept_qua/{year}', [QMSController::class, 'get_total_genba_dept_qua']);
Route::get('qms/get_total_genba_dept_pur/{year}', [QMSController::class, 'get_total_genba_dept_pur']);
Route::get('qms/get_total_genba_dept_tmc/{year}', [QMSController::class, 'get_total_genba_dept_tmc']);
Route::get('qms/get_total_genba_dept_fa/{year}', [QMSController::class, 'get_total_genba_dept_fa']);
Route::get('qms/get_total_genba_dept_hrga/{year}', [QMSController::class, 'get_total_genba_dept_hrga']);
Route::get('qms/get_total_genba_dept_npc/{year}', [QMSController::class, 'get_total_genba_dept_npc']);
Route::get('qms/get_total_genba_dept_ict/{year}', [QMSController::class, 'get_total_genba_dept_ict']);
Route::get('qms/get_total_genba_dept_tmf/{year}', [QMSController::class, 'get_total_genba_dept_tmf']);
Route::get('qms/get_total_genba_dept_sales/{year}', [QMSController::class, 'get_total_genba_dept_sales']);
Route::post('qms/findings_detail_table', [QMSController::class, 'findings_detail_table']);

#endregion

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


#Region IOTV2
//For Tabel machine
Route::post('main/{id}', [LogMachineController::class, 'table_page']);
//For tabel history
Route::post('/main/jo_pending/{id}', [LogMachineController::class, 'table_page_pending']);
Route::post('/main/history/{id}', [LogMachineController::class, 'table_page_history']);
//For dashboard version two
Route::get('dashboard/{id}', [LogMachineController::class, 'dashboard_v2']);
Route::post('dashboard-machine', [LogMachineController::class, 'dashboard_machine']);
//For dashboard history
Route::post('history-dashboard', [LogMachineController::class, 'historyDashboard']);
Route::post('history-dashboard-tool', [LogMachineController::class, 'historyDashboardTool']);
//For summary
Route::get('summary-by-line/{id}', [LogMachineController::class, 'dashboard_summary']);
//For get one machine
Route::post('/machine/get-one-machine', [LogMachineController::class, 'getOneMachine']);
//For get many employee
Route::post('/machine/get-employees', [LogMachineController::class, 'getEmployee']);
//For machine ON
Route::post('machine/get-machine-running', [LogMachineController::class, 'GetMachineRunning']);
//For get all job number
Route::post('machine/get-all-job-number', [LogMachineController::class, 'getAllJobNumber']);
//For get category assy or stp
Route::post('machine/get_category/{id}', [LogMachineController::class, 'getCategory']);
//For card machine
Route::post('machine/job-entry', [LogMachineController::class, 'JobEntry']);
//For get machine
Route::post('/machine/get-machine', [LogMachineController::class, 'getMachine']);
//For get JobNumber
Route::post('machine/get-job-number', [LogMachineController::class, 'getJobNumber']);
//For Start downtime
Route::post('machine/set-downtime', [LogMachineController::class, 'StartDowntime']);
//For finish endgame
Route::post('machine/finish-downtime', [LogMachineController::class, 'FinishDowntime']);
Route::prefix('special-setup')->group(function () {
    Route::post('category', [LogMachineController::class, 'special_setup_category']);
    Route::post('machine', [LogMachineController::class, 'special_setup_machine']);
    Route::post('work-time', [LogMachineController::class, 'special_setup_work_time']);
    Route::post('start', [LogMachineController::class, 'special_setup_start']);
});
Route::prefix('machine/v2')->group(function () {
    // Route::get('machine_list', [LogMachineController::class, 'list_log_machine']);
    //For started
    Route::post('set_job_number', [LogMachineController::class, 'setJobNumberV2']);
    //For list job number
    Route::post('jo_list', [LogMachineController::class, 'JoList']);
    //For list shift
    Route::post('shift_list', [LogMachineController::class, 'ShiftList']);
    Route::post('get_avail_time', [LogMachineController::class, 'getAvail']);
    //For finish JO
    Route::post('set_finish', [LogMachineController::class, 'SetFinish']);
    Route::post('get-one-job-number', [LogMachineController::class, 'getOneJobNumber']);
    Route::post('tool-running-machine', [LogMachineController::class, 'toolRunningMachine']);
    Route::post('set-downtime-jig', [LogMachineController::class, 'setDowntimeJig']);
    Route::post('finish-downtime-tool', [LogMachineController::class, 'finishDowntimeTool']);
    Route::post('set-finish-tool', [LogMachineController::class, 'setFinishTool']);
    Route::post('dashboard-by-machine/{id}', [LogMachineController::class, 'dashboardSummaryMachine']);
    Route::post('dashboard-by-machine-tool/{id}', [LogMachineController::class, 'dashboardSummaryMachineTool']);
    Route::post('get-emp-select', [LogMachineController::class, 'getEmpSelect']);
    Route::post('time-entry', [LogMachineController::class, 'timeEntryV2']);
    Route::post('time-entry-tool', [LogMachineController::class, 'timeEntryToolV2']);
    Route::post('create-new-header', [LogMachineController::class, 'createNewHeaderV2']);
    Route::post('change-shift', [LogMachineController::class, 'changeShiftV2']);
    Route::post('update-header', [LogMachineController::class, 'updateHeaderV2']);
    Route::post('get-op-seq', [LogMachineController::class, 'getOpSeqV2']);
    Route::post('get-newt-labor-dtl', [LogMachineController::class, 'getNewtLaborDtlV2']);
    Route::post('change-labor-time', [LogMachineController::class, 'changeLaborTimeV2']);
    Route::post('update-dtl', [LogMachineController::class, 'updateDtlV2']);
    Route::post('submit-time-entry', [LogMachineController::class, 'submitTimeEntryV2']);
});
Route::get('dashboard-tool/{id}/{tool}', [LogMachineController::class, 'dashboard_tool']);
#endregion
#MENU SOP
Route::prefix('menu_sop')->group(function () {
    Route::post('show', [LogMachineController::class, 'show_sop']);
    Route::post('part_num', [LogMachineController::class, 'showPartNum']);
    Route::post('store', [LogMachineController::class, 'store']);
    Route::post('delete_all', [LogMachineController::class, 'delete_all']);
    Route::post('edit_show', [LogMachineController::class, 'edit_show']);
    Route::post('update', [LogMachineController::class, 'updateSop']);
    Route::post('delete', [LogMachineController::class, 'deleteSop']);
});
//Health Condition
Route::prefix('health_condition')->group(function () {
    Route::post('submit', [HealthConditionController::class, 'submit']);
});
//JobNum
Route::prefix('job_num')->group(function () {
    Route::post('get_all', [JobNumController::class, 'get_all']);
    Route::post('get_customer', [JobNumController::class, 'get_customer']);
    Route::post('list_setup_machine', [JobNumController::class, 'list_setup_machine']);
    Route::post('start_machine_std', [JobNumController::class, 'start_machine_std']);
    Route::post('start_machine_tool', [JobNumController::class, 'start_machine_tool']);
});
Route::prefix('profile')->group(function () {
    Route::post('main', [ProfileController::class, 'main']);
    Route::post('progress_bar', [ProfileController::class, 'progress_bar']);
    Route::post('upload-photo', [ProfileController::class, 'uploadPhoto']);
    Route::post('view_more', [ProfileController::class, 'view_more']);
    Route::post('history_oee', [ProfileController::class, 'history_oee']);
    Route::post('main_gsph', [ProfileController::class, 'main_gsph']);
    Route::post('history_main_gsph', [ProfileController::class, 'history_main_gsph']);
    Route::post('main-dt', [ProfileController::class, 'main_dt']);
    Route::post('history_main_dt', [ProfileController::class, 'history_main_dt']);
    Route::post('jo_show', [ProfileController::class, 'jo_show']);
    Route::post('change_jo', [ProfileController::class, 'change_jo']);
    Route::post('history', [ProfileController::class, 'history']);
    Route::post('activity_history', [ProfileController::class, 'activity_history']);
    //Dashboard machine
    Route::post('dashboard_machine', [ProfileController::class, 'dashboard_machine']);

});
Route::prefix('user-management')->group(function () {
    Route::post('list', [UserManagementController::class, 'list']);
    Route::post('store-users', [UserManagementController::class, 'store_user']);
    Route::post('find-data', [UserManagementController::class, 'find_data']);
    Route::post('update-user', [UserManagementController::class, 'update_user']);
    Route::post('delete-user', [UserManagementController::class, 'delete_user']);
});
Route::prefix('config')->group(function () {
    // Route::post('setup', [ConfigController::class, 'setup']);
    Route::post('setup', [ConfigController::class, 'setup_before_confirm']);
    Route::post('spesial_start', [ConfigController::class, 'spesial_start']);
    Route::post('finish_machine', [ConfigController::class, 'finish_machine']);
    Route::post('shift_get_all', [ConfigController::class, 'shift_get_all']);
    Route::post('get_machine', [ConfigController::class, 'get_machine']);
    Route::post('work-time', [ConfigController::class, 'special_setup_work_time']);
    Route::post('job_list', [ConfigController::class, 'job_list']);
    Route::post('get_job_all', [ConfigController::class, 'get_job_all']);
    Route::post('get_downtime', [ConfigController::class, 'get_downtime']);
    Route::post('get_employee', [ConfigController::class, 'get_employee']);
    Route::post('save_downtime', [ConfigController::class, 'save_downtime']);
    Route::post('stop_downtime', [ConfigController::class, 'stop_downtime']);
    Route::post('technician_arrived', [ConfigController::class, 'technician_arrived']);
    //time entry
    Route::post('create_new', [ConfigController::class, 'create_new']);
    Route::post('change_shift', [ConfigController::class, 'change_shift']);
    Route::post('update_header', [ConfigController::class, 'update_header']);
    Route::post('labor_submit_entry', [ConfigController::class, 'labor_submit_entry']);
    Route::post('scan_qr', [ConfigController::class, 'scan_qr']);
    Route::post('list_machine_by_scan', [ConfigController::class, 'list_machine_by_scan']);
    Route::post('downtime_message', [ConfigController::class, 'downtime_message']);
});
Route::prefix('production-report')->group(function () {
    Route::get('/', [ProfileController::class, 'production_table']);
});
Route::post('confirm_table', [ConfigController::class, 'confirm_table']);
Route::post('confirm_submit', [ConfigController::class, 'confirm_submit']);
