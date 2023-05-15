<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Division;
use App\Models\Supplier;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use PDF;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('purchase.purchaseOrder.purchaseOrderIndex', [
            'purchase_orders' => PurchaseOrder::with(['user', 'supplier'])->latest()->filter(request(['search']))->paginate(7)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('purchase.purchaseOrder.purchaseOrderCreate', [
            'purchase_requests' => PurchaseRequest::select('id', 'prCode')->latest()->get(),
            'suppliers' => Supplier::select('id', 'spName')->get()
        ]);
    }

    public function setText($pr_id) {
        $input = PurchaseRequest::where('id', $pr_id)->first();

        $pr_item = $input->item;
     
        return response()->json($pr_item);
    }

    public function setDetail($po_id) {
        $input = PurchaseOrder::where('id', $po_id)->first();

        $po_item = $input->item;
     
        return response()->json($po_item);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $initial = Division::where('id', Auth::id())->pluck('initials');
        $count = PurchaseOrder::count();
        $kebutuhan = $request->kebutuhan;
        $number = $count + 1;
        $limit = count($request->item_id);
        
        $prefix = sprintf('%04d', $number) . '/' . $initial[0]. '-' . $kebutuhan . '/' . date('m') . '/' . date('y');
        // DD($prefix);

        $request['poCode'] = $prefix;
        $request['user_id'] = Auth::id();

        $rules = [
            'poCode' => 'required',
            'pr_id' => 'required',
            'user_id' => 'required',
            'sp_id' => 'required',
            'description' => 'required',
            'pymntTerms' => 'required'
        ];

        $validated = $request->validate($rules);
        

        if (PurchaseOrder::create($validated)) {
            $po_id = PurchaseOrder::select('id')->where('poCode', $prefix)->get();

            for ($i=0; $i < $limit; $i++) { 
                PurchaseOrder::find($po_id[0]['id'])->item()->attach($request->item_id[$i], ['qtyPo' => $request->qtyPo[$i], 'satuan' => $request->satuanSelect[$i], 'harga' => $request->harga[$i], 'total' => $request->qtyPo[$i]*$request->harga[$i]]);
            }
            
            $request->session()->flash('success', 'PR baru berhasil ditambah!');
            return redirect('purchaseOrder/create');
        } else {
            $request->session()->flash('danger', 'PR baru gagal ditambah!');
            return redirect('purchaseOrder/create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        return view('purchase.purchaseOrder.purchaseOrderEdit', [
            'purchase_order' => $purchaseOrder,
            'suppliers' => Supplier::select('id', 'spName')->get(),
            'items' => Item::select('id', 'itemName')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePurchaseOrderRequest  $request
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $rule = [
            'description' => 'required',
            'pymntTerms' => 'required'
        ];

        if (isset($request->kebutuhan)) {
            $div = $request->kebutuhan;
            $prefix = $purchaseOrder->poCode;
            $fix = substr_replace($prefix, $div, 10, -6);
            $request['poCode'] = $fix;
            $rule['poCode'] = 'required';
        }

        $limit = count($request->item_id);
        $sync = [];

        for ($i=0; $i < $limit; $i++) { 
            $sync[$request->item_id[$i]] = ['qtyPo' => $request->qtyPo[$i], 'satuan' => $request->satuanSelect[$i], 'harga' => $request->harga[$i], 'total' => $request->qtyPo[$i]*$request->harga[$i]];
        }

        $validated = $request->validate($rule);

        if (PurchaseOrder::where('id', $purchaseOrder->id)
                    ->update($validated)) {
            
                PurchaseOrder::find($purchaseOrder->id)->item()->sync($sync);
            
            return redirect('purchaseOrder')->with('success', 'Purchase Order berhasil diubah!');
        } else {
            return redirect('purchaseOrder')->with('danger', 'Purchase Order gagal diubah!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if (PurchaseOrder::destroy($purchaseOrder->id)) {
            return redirect('purchaseOrder')->with('success', 'Purchase Order berhasil dihapus');
        } else {
            return redirect('purchaseOrder')->with('danger', 'Purchase Order gagal dihapus');
        }
    }

    public function word($po_id) {
        
        $purchase_orders = PurchaseOrder::where('id', $po_id)->get();
        $purchase_order = $purchase_orders[0];
        $limit = count($purchase_order->item);
        $pymntTerms = Str::between($purchase_order->pymntTerms, '<div>', '</div>');
        $address = Str::between($purchase_order->supplier->address, '<div>', '</div>');
        // DD($purchase_order->item[0]->pivot->qtyPo);

        // SCRIPT WORD

            // Creating the new document...
            $phpWord = new \PhpOffice\PhpWord\TemplateProcessor('poDocument.docx');
            
            $num = 1;
            for ($i=0; $i < $limit; $i++) { 
                $values[] = 
                    [
                    'no' => $num,
                    'itemName' => $purchase_order->item[$i]->itemName,
                    'qtyPo' => $purchase_order->item[$i]->pivot->qtyPo,
                    'satuan' => $purchase_order->item[$i]->pivot->satuan,
                    'harga' => $purchase_order->item[$i]->pivot->harga,
                    'total' => $purchase_order->item[$i]->pivot->total
                    ];
                $num++;
            }

            // DD($values);

            // DOCUMENT EDIT
            $phpWord->setValues([
                'poCode' => $purchase_order->poCode,
                'poCreated_at' => $purchase_order->created_at,
                'pymntTerms' => $pymntTerms,
                'spName' => $purchase_order->supplier->spName,
                'address' => $address,
                'cpNumber' => $purchase_order->supplier->cpNumber,
                'cpName' => $purchase_order->supplier->cpName,
                'prCode' => $purchase_order->purchase_request->prCode,
                'prCreated_at' => $purchase_order->purchase_request->created_at
            ]);


            // DD($values);

            $phpWord->cloneRowAndSetValues('no', $values);

            // Saving the document as OOXML file...
            // $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $phpWord->saveAs('poEnd.docx');

            // Saving the document as ODF file...
            // $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
            // $objWriter->save('helloWorld.odt');

            // Saving the document as HTML file...
            // $phpWord = \PhpOffice\PhpWord\IOFactory::load('poEnd.docx');
            // $htmlWriter = new \PhpOffice\PhpWord\Writer\HTML($phpWord);
            // $htmlWriter->save('poEnd.html');

            /* Note: we skip RTF, because it's not XML-based and requires a different example. */
            /* Note: we skip PDF, because "HTML-to-PDF" approach is used to create PDF documents. */

            // RENDER TO PDF
            $domPdfPath = base_path('vendor/dompdf/dompdf');
            \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
            \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');

            $phpWord = \PhpOffice\PhpWord\IOFactory::load('poEnd.docx');

            $PDFWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord,'PDF');
            $PDFWriter->save('poResult.pdf');

    }

}
