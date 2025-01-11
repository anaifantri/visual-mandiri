<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Quotation;
use App\Models\Company;
use App\Models\Location;
use App\Models\PrintOrder;
use App\Models\InstallOrder;
use App\Models\Area;
use App\Models\City;
use App\Models\MediaSize;
use App\Models\MediaCategory;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Gate;

class OrderReportController extends Controller
{
    public function index(String $company_id): View
    {
        if(Gate::allows('isOrder') && Gate::allows('isMarketingRead')){
            $year = date('Y');
            $mm = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
            for ($i=1; $i <= 12; $i++) { 
                $printOrders = PrintOrder::where('company_id', $company_id)->whereYear('created_at', $year)->whereMonth('created_at', $i)->get();
                $monthData[] = $mm[$i];
                $printOrderQty[] = count($printOrders);
            }
            $printSales = PrintOrder::where('company_id', $company_id)->sales()->year()->get();
            $freePrintSales = PrintOrder::where('company_id', $company_id)->freeSales()->year()->get();
            $freePrintOther = PrintOrder::where('company_id', $company_id)->freeOther()->year()->get();
            $printOrderData = [count($printSales), count($freePrintSales), count($freePrintOther)];

            for ($i=1; $i <= 12; $i++) { 
                $installOrders = InstallOrder::where('company_id', $company_id)->whereYear('created_at', $year)->whereMonth('created_at', $i)->get();
                $installOrderQty[] = count($installOrders);
            }
            $installSales = InstallOrder::where('company_id', $company_id)->sales()->year()->get();
            $freeInstallSales = InstallOrder::where('company_id', $company_id)->freeSales()->year()->get();
            $freeInstallOther = InstallOrder::where('company_id', $company_id)->freeOther()->year()->get();
            $installOrderData = [count($installSales), count($freeInstallSales), count($freeInstallOther)];

            $labelData = ['Berbayar', 'Gratis Penjualan', 'Gratis Lain-Lain'];
            return view ('orders-report.index', [
                'printOrderQty' => $printOrderQty,
                'printSales' => $printSales,
                'installOrderQty' => $installOrderQty,
                'monthData' => $monthData,
                'printOrderData' => $printOrderData,
                'installOrderData' => $installOrderData,
                'labelData' => $labelData,
                'todaysPrint' => PrintOrder::where('company_id', $company_id)->whereDate('created_at', Carbon::today())->get(),
                'weekdayPrints' => PrintOrder::where('company_id', $company_id)->whereBetween('created_at', [Carbon::now()->startOfWeek(Carbon::SUNDAY), Carbon::now()->endOfWeek(Carbon::SATURDAY)])->get(),
                'monthPrints' => PrintOrder::where('company_id', $company_id)->whereMonth('created_at', Carbon::now()->month)->get(),
                'yearPrints' => PrintOrder::where('company_id', $company_id)->whereYear('created_at', Carbon::now()->year)->get(),
                'todaysInstall' => InstallOrder::where('company_id', $company_id)->whereDate('created_at', Carbon::today())->get(),
                'weekdayInstalls' => InstallOrder::where('company_id', $company_id)->whereBetween('created_at', [Carbon::now()->startOfWeek(Carbon::SUNDAY), Carbon::now()->endOfWeek(Carbon::SATURDAY)])->get(),
                'monthInstalls' => InstallOrder::where('company_id', $company_id)->whereMonth('created_at', Carbon::now()->month)->get(),
                'yearInstalls' => InstallOrder::where('company_id', $company_id)->whereYear('created_at', Carbon::now()->year)->get(),
                'title' => 'Laporan SPK Cetak dan Pasang'
            ]);
        } else {
            abort(403);
        }
    }

    public function printReports(String $company_id, Request $request): View
    {
        if(Gate::allows('isOrder') && Gate::allows('isMarketingRead')){
            return view ('orders-report.print-reports', [
                'print_orders'=>PrintOrder::where('company_id', $company_id)->filter(request('search'))->sortable()->orderBy("number", "asc")->get(),
                'amount'=>PrintOrder::where('company_id', $company_id)->filter(request('search'))->sum('price'),
                'sales' => Sale::where('company_id', $company_id)->get(),
                'title' => 'Laporan SPK Cetak'
            ]);
        } else {
            abort(403);
        }
    }

    public function installReports(String $company_id, Request $request): View
    {
        if(Gate::allows('isOrder') && Gate::allows('isMarketingRead')){
            return view ('orders-report.install-reports', [
                'install_orders'=>InstallOrder::where('company_id', $company_id)->filter(request('search'))->sortable()->orderBy("number", "asc")->get(),
                'sales' => Sale::where('company_id', $company_id)->get(),
                'title' => 'Laporan SPK Pasang'
            ]);
        } else {
            abort(403);
        }
    }
}
