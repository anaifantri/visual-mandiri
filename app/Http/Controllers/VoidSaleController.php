<?php

namespace App\Http\Controllers;

use App\Models\VoidSale;
use App\Models\Sale;
use App\Models\Quotation;
use App\Models\QuotationRevision;
use App\Models\MediaCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Carbon\Carbon;
use Validator;
use Gate;

class VoidSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(String $saleId): Response
    {
        if((Gate::allows('isAdmin') && Gate::allows('isSale') && Gate::allows('isMarketingCreate')) || (Gate::allows('isMarketing') && Gate::allows('isSale') && Gate::allows('isMarketingCreate'))){
            $dataSale = Sale::findOrFail($saleId);
            $product = json_decode($dataSale->product);
            $category = $dataSale->media_category->name;
            $client = json_decode($dataSale->quotation->clients);
            $revision = QuotationRevision::where('quotation_id', $dataSale->quotation->id)->get()->last();
            if($revision){
                $quotation = $revision;
                $price = json_decode($revision->price);
            } else{
                $quotation = $dataSale->quotation;
                $price = json_decode($dataSale->quotation->price);
            }
            
            return response()-> view ('void-sales.create', [
                'sale'=>$dataSale,
                'quotation'=>$quotation,
                'product'=>$product,
                'client'=>$client,
                'price'=>$price,
                'category'=>$category,
                'title' => 'Membatalkan Penjualan Nomor '.$dataSale->number
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
        if((Gate::allows('isAdmin') && Gate::allows('isSale') && Gate::allows('isMarketingCreate')) || (Gate::allows('isMarketing') && Gate::allows('isSale') && Gate::allows('isMarketingCreate'))){
            $request->validate([
                'images.*'=> 'image|file|mimes:jpeg,png,jpg|max:2048'
            ]);

            $validateData = $request->validate([
                'sale_id' => 'required',
                'company_id' => 'required',
                'note' => 'required',
                'price' => 'required',
                'dpp' => 'required',
                'ppn' => 'required',
                'created_by' => 'required'
            ]);

            if($request->file('images')){
                $images = [];
                $getImages = $request->file('images');
                foreach($getImages as $image){
                    array_push($images,$image->store('void-sale-images'));
                }
                $validateData['images'] = json_encode($images); 
            }

            VoidSale::create($validateData);
                
            return redirect('/marketing/sales/home/'.$request->category.'/'.$validateData['company_id'])->with('success', 'Pembatalan penjualan nomor '.$request->sale_number.' berhasil');
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(VoidSale $voidSale): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VoidSale $voidSale): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VoidSale $voidSale): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VoidSale $voidSale): RedirectResponse
    {
        //
    }
}
