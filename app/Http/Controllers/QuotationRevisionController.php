<?php

namespace App\Http\Controllers;

use App\Models\QuotationRevision;
use App\Models\QuotRevisionStatus;
use App\Models\Quotation;
use App\Models\Location;
use App\Models\LocationPhoto;
use App\Models\Area;
use App\Models\City;
use App\Models\Company;
use App\Models\Led;
use App\Models\PrintingProduct;
use App\Models\InstallationPrice;
use App\Models\MediaCategory;
use App\Models\MediaSize;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Gate;
use Illuminate\Support\Facades\Crypt;

class QuotationRevisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        //
    }

    public function guestPreview(String $category, String $id): View
    { 
        $quotation_revision = QuotationRevision::findOrFail(Crypt::decrypt($id));
        $quotation = Quotation::with('quotation_revisions')->get();
        return view('quotation-revisions.preview', [
            'quotation_revision' => $quotation_revision,
            'title' => 'Detail Revisi Penawaran',
            'category'=>$category,
            'leds' => Led::all(),
            compact('quotation')
        ]);
    }

    public function preview(String $category, String $id): View
    { 
        if(Gate::allows('isQuotation') && Gate::allows('isMarketingRead')){
            $quotation = Quotation::with('quotation_revisions')->get();
            return view('quotation-revisions.preview', [
                'quotation_revision' => QuotationRevision::findOrFail($id),
                'title' => 'Detail Revisi Penawaran',
                'category'=>$category,
                'leds' => Led::all(),
                compact('quotation')
            ]);
        } else {
            abort(403);
        }
    }

    public function revision(String $category, String $id): View
    {
        if((Gate::allows('isAdmin') && Gate::allows('isQuotation') && Gate::allows('isMarketingCreate')) || (Gate::allows('isMarketing') && Gate::allows('isQuotation') && Gate::allows('isMarketingCreate'))){
            $quotation = Quotation::findOrFail($id);
            $payment_terms = json_decode($quotation->payment_terms);
            $notes = json_decode($quotation->notes);
            if(request('new_products')){
                $products = json_decode(request('new_products'));
                $description = json_decode($products[0]->description);
            }else{
                $products = json_decode($quotation->products);
                $description = json_decode($products[0]->description);
            }
            if(request('new_price')){
                $price = json_decode(request('new_price'));
            }else{
                $price = json_decode($quotation->price);
            }
            // dd($price);
            $payment_terms = json_decode($quotation->payment_terms);
            $notes = json_decode($quotation->notes);
            if($category == "Signage"){
                $dataLocations = Location::quotationNew()->categoryName($category)->where('description->type', '!=', 'videotron')->sortable()->orderBy("code", "asc")->get();
            }else{
                $dataLocations = Location::quotationNew()->categoryName($category)->sortable()->orderBy("code", "asc")->get();
            }
            $location = null;
            if($category == "Videotron" || ($category == "Signage" && $description->type == "Videotron")){
                $location = Location::findOrFail($products[0]->id);
            }
            $companies = Company::with('quotations')->get();
            $media_sizes = MediaSize::with('locations')->get();
            $media_categories = MediaCategory::with('locations')->get();
            $location_photos = LocationPhoto::with('location')->get();
            $areas = Area::with('locations')->get();
            $cities = City::with('locations')->get();
            $quotation_revisions = QuotationRevision::with('quotation')->get();

            return view('quotation-revisions.create', [
                'quotation' => $quotation,
                'locations'=>$dataLocations,
                'location' => $location,
                'products' => $products,
                'price'=> $price,
                'payment_terms'=> $payment_terms,
                'notes'=> $notes,
                'areas'=>Area::all(),
                'cities'=>City::all(),
                'printing_products'=>PrintingProduct::all(),
                'installation_prices'=>InstallationPrice::all(),
                'leds' => Led::all(),
                'category'=>$category,
                'title' => 'Revisi Penawaran',
                compact('media_sizes', 'media_categories', 'location_photos', 'areas', 'cities', 'quotation_revisions', 'companies')
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if((Gate::allows('isAdmin') && Gate::allows('isQuotation') && Gate::allows('isMarketingCreate')) || (Gate::allows('isMarketing') && Gate::allows('isQuotation') && Gate::allows('isMarketingCreate'))){
            // dd($request->price);
            $validateData = $request->validate([
                'number' => 'required|unique:quotation_revisions',
                'quotation_id' => 'required',
                'notes' => 'required',
                'payment_terms' => 'required',
                'price' => 'required',
                'products' => 'required',
                'modified_by' => 'required'
            ]);

            QuotationRevision::create($validateData);

            $dataQuotation = QuotationRevision::where('number', $validateData['number'])->firstOrFail();

            $validateData['quotation_id'] = $validateData['quotation_id'];
            $validateData['quotation_revision_id'] = $dataQuotation->id;
            $validateData['status'] = "Created";
            $validateData['updated_by'] = $request->modified_by;
            if($dataQuotation->quotation->media_category->name == "Service"){
                $validateData['description'] = "Revisi surat penawaran cetak / pasang dengan nomor ".$validateData['number']." telah dibuat dan tersimpan";
            }else{
                $validateData['description'] = "Revisi surat penawaran ". $dataQuotation->quotation->media_category->name ." dengan nomor ".$validateData['number']." telah dibuat dan tersimpan";
            }
            
            QuotRevisionStatus::create($validateData);
                
            return redirect('/marketing/quotation-revisions/preview/'.$dataQuotation->quotation->media_category->name.'/'.$dataQuotation->id)->with('success', 'Revisi penawaran dengan nomor '. $validateData['number'] . ' berhasil dibuat');
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(QuotationRevision $quotationRevision): Response
    {
        if(Gate::allows('isQuotation') && Gate::allows('isMarketingRead')){
            $quot_revision_statuses = QuotRevisionStatus::where('quotation_revision_id', $quotationRevision->id)->orderBy("created_at", "desc")->get();
            $quotation = Quotation::with('quotation_revisions')->get();
            $lastRevision = QuotationRevision::where('quotation_id', $quotationRevision->quotation_id)->get()->last();
    
            return response()->view('quotation-revisions.show', [
                'quotation_revision' => $quotationRevision,
                'quot_revision_statuses' => $quot_revision_statuses,
                'last_revision' => $lastRevision,
                'title' => 'Data Revisi Penawaran',
                'leds' => Led::all(),
                'last_statuses' => QuotRevisionStatus::where('quotation_revision_id', $quotationRevision->id)->get()->last(),
                compact('quotation')
            ]);
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuotationRevision $quotationRevision): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuotationRevision $quotationRevision): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuotationRevision $quotationRevision): RedirectResponse
    {
        //
    }
}
