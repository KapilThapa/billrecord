<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bill;
use Yajra\Datatables\Datatables;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $bills = Bill::orderby('id','asce')->get();
        // return $bills;
        return Datatables::of(Bill::orderby('id','asce')->get())->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'bill_no' => 'required|unique:bills,bill_no',
            'total'  => 'required',
            'advance'  => 'required',
        ]);
        $bill = new Bill;
        $bill->bill_no = $request->bill_no;
        $bill->customer_name = $request->customer_name;
        $bill->contact = $request->contact;
        $bill->total = $request->total;
        $bill->advance = $request->advance;
        $bill->Balance = $request->total - $request->advance;
        $bill->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function checkbill($billno)
    {
        $bill = Bill::where('bill_no',$billno)->first();
        if(isset($bill)){
            return 'true';
        }
        return 'false';
    }

    public function getBillNo(){
        $lastbill = Bill::orderBy('id', 'desc')->first();
        $newbill = 0;
        if (is_null($lastbill)){
            $newbill = 1;
        }
        return response()->json([
            'billno' => $lastbill,
            'newbill' => $newbill
        ]);
    }

    public function getbilldetail($billno)
    {
        $bill = Bill::where('bill_no',$billno)->first();
        if(isset($bill)){
            return response()->json([
                'status' => 'true',
                'bill' => $bill
            ]);
        }
        return response()->json([
            'status' => 'false'
        ]);
    }
}
