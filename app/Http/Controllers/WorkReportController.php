<?php

namespace App\Http\Controllers;

use App\Models\WorkReport;
use App\Models\Company;
use App\Models\Sale;
use App\Models\Quotation;
use App\Models\QuotationRevision;
use App\Models\InstallationPhoto;
use App\Models\InstallOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Validator;
use Gate;

class WorkReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(String $company_id): Response
    {
        if(Gate::allows('isCollect') && Gate::allows('isAccountingRead')){
            return response()-> view ('work-reports.index', [
                'work_reports'=>WorkReport::where('company_id', $company_id)->sortable()->orderBy("number", "desc")->paginate(30)->withQueryString(),
                'title' => 'Daftar BAST'
            ]);
        } else {
            abort(403);
        }
    }
        
    
    public function preview(String $id): View
    { 
        if(Gate::allows('isCollect') && Gate::allows('isAccountingRead')){
            $data_work_report = WorkReport::findOrFail($id);
            $first_photo = json_decode($data_work_report->first_photo);
            $second_photo = json_decode($data_work_report->second_photo);
            $content = json_decode($data_work_report->content);
            $install_order_id = $content->install_order_id;
            $sale = Sale::with('work_reports')->get();
            return view('work-reports.preview', [
                'work_report' => $data_work_report,
                'install_order' => InstallOrder::findOrFail($install_order_id),
                'first_photo' => InstallationPhoto::findOrFail($first_photo->id),
                'first_photo_title' => $first_photo->title,
                'second_photo' => InstallationPhoto::findOrFail($second_photo->id),
                'second_photo_title' => $second_photo->title,
                'content' => $content,
                'title' => 'Preview BAST',
                compact('sale')
            ]);
        } else {
            abort(403);
        }
    }

    public function selectDocumentation(String $saleId): View
    {
        if((Gate::allows('isAdmin') && Gate::allows('isCollect') && Gate::allows('isAccountingCreate')) || (Gate::allows('isAccounting') && Gate::allows('isCollect') && Gate::allows('isAccountingCreate'))){
            $sale = Sale::findOrFail($saleId);
            $data_orders = InstallOrder::photo($saleId)->orderBy("id", "desc")->get();
            if(request('rb_install_order')){
                $data_photos = InstallationPhoto::where('install_order_id', request('rb_install_order'))->get();
                $data_order = InstallOrder::where('id', request('rb_install_order'))->get();
                $first_photos = InstallationPhoto::where('install_order_id', request('rb_install_order'))->where('type', 'day')->get();
                $second_photos = InstallationPhoto::where('install_order_id', request('rb_install_order'))->where('type', 'night')->get();
            }else{
                if(count($data_orders) != 0){
                    $data_photos = InstallationPhoto::where('install_order_id', $data_orders[0]->id)->get();
                    $data_order = InstallOrder::where('id', $data_orders[0]->id)->get();
                    $first_photos = InstallationPhoto::where('install_order_id',  $data_orders[0]->id)->where('type', 'day')->get();
                    $second_photos = InstallationPhoto::where('install_order_id',  $data_orders[0]->id)->where('type', 'night')->get();
                }else{
                    $data_photos = collect([]);
                    $data_order = collect([]);
                    $first_photos = collect([]);
                    $second_photos = collect([]);
                }
            }
            $quotations = Quotation::with('sales')->get();
            $quotation_revisions = QuotationRevision::with('quotation')->get();
            return view ('work-reports.create', [
                'title' => 'Memilih Foto Dokumentasi',
                'install_orders' => $data_orders,
                'install_order' => $data_order,
                'sale' => $sale,
                'work_category' => $sale->media_category->name,
                'installation_photos' => $data_photos,
                'first_photos' => $first_photos,
                'second_photos' => $second_photos,
                compact('quotations','quotation_revisions')
            ]);
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        if((Gate::allows('isAdmin') && Gate::allows('isCollect') && Gate::allows('isAccountingCreate')) || (Gate::allows('isAccounting') && Gate::allows('isCollect') && Gate::allows('isAccountingCreate'))){
            $quotations = Quotation::with('sales')->get();
            $install_orders = InstallOrder::with('sale')->get();
            $installation_photo = InstallationPhoto::with('install_order')->get();
            $quotation_revisions = QuotationRevision::with('quotation')->get();
            return response()-> view ('work-reports.select-sale', [
                'title' => 'Membuat BAST',
                'data_sales' => Sale::orderBy("id", "desc")->get(),
                'installation_photos' => InstallationPhoto::all(),
                compact('quotations', 'quotation_revisions','install_orders','installation_photo')
            ]);
        } else {
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if((Gate::allows('isAdmin') && Gate::allows('isCollect') && Gate::allows('isAccountingCreate')) || (Gate::allows('isAccounting') && Gate::allows('isCollect') && Gate::allows('isAccountingCreate'))){
            $romawi = [1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VII', 'IX', 'X', 'XI', 'XII'];
            $dataCompany = Company::where('id', $request->company_id)->firstOrFail();
            // Set number --> start
            $lastReport = WorkReport::where('company_id', $request->company_id)->whereYear('created_at', Carbon::now()->year)->get()->last();
            if($lastReport){
                $lastNumber = (int)substr($lastOrder->number,0,4);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
            
            if($newNumber > 0 && $newNumber < 10){
                $number = '000'.$newNumber.'/BAST/'.$dataCompany->code.'/'.$romawi[(int) date('m')].'-'. date('Y');
            }else if($newNumber >= 10 && $newNumber < 100 ){
                $number = '00'.$newNumber.'/BAST/'.$dataCompany->code.'/'.$romawi[(int) date('m')].'-'. date('Y');
            }else if($newNumber >= 100 && $newNumber < 1000 ){
                $number = '0'.$newNumber.'/BAST/'.$dataCompany->code.'/'.$romawi[(int) date('m')].'-'. date('Y');
            } else {
                $number = $newNumber.'/BAST/'.$dataCompany->code.'/'.$romawi[(int) date('m')].'-'. date('Y');
            }
            // Set number --> end

            $request->request->add(['number' => $number]);

            // dd($request);

            $validateData = $request->validate([
                'number' => 'required|unique:work_reports',
                'company_id' => 'required',
                'sale_id' => 'required',
                'content' => 'required',
                'first_photo' => 'required',
                'second_photo' => 'required',
                'created_by' => 'required',
                'updated_by' => 'required'
            ]);
            WorkReport::create($validateData);

            $dataReport = WorkReport::where('number', $validateData['number'])->firstOrFail();
    
            return redirect('/work-reports/preview/'.$dataReport->id)->with('success','BAST dengan nomor '. $number . ' berhasil ditambahkan');
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkReport $workReport): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkReport $workReport): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkReport $workReport): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkReport $workReport): RedirectResponse
    {
        //
    }
}
