<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use  Spatie\Permission\Models\Role;

class TransactionController extends Controller
{
    public function __construct()
    {
        new Middleware(['permission:view transaction', ['only' => ['index']],'permission:create transaction', ['only' => ['create','store']]]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allUsersOprion = '';
        if(Auth::user()->hasRole('customer'))
        {
            $userid = Auth::user()->id;
            $transactions = Transaction::where('cust_id',$userid)->get();
        }
        else
        {
            $transactions = Transaction::all();
            $allUsers = User::all();
            foreach($allUsers as $user)
            {
                $allUsersOprion .= '<option value="'.$user->id.'">'.$user->name.'</option>';
            }
        }
        return view('transaction.index',compact('transactions','allUsersOprion'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('transaction.create', ['roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {   
        $request->validate([
            'amount' => 'required|int|max:10000000',
            'transaction_type' => 'required',
        ]);
        $transaction_id = rand(100000,999999).time();
        $userid = Auth::user()->id;
        $file_path = '';
        if($request->file('file')!=null)
        {
            $name = time().rand(1,50).'.'.$request->file('file')->extension();
            $request->file('file')->move(public_path('uploads'), $name); 
            $file_path = 'uploads/'.$name; 
        }
        $transaction = Transaction::create([
                      'amount'=>$request->amount,  
                      'transaction_type'=>$request->transaction_type, 
                      'transaction_id'=>$transaction_id,     
                      'cust_id'=>$userid,     
                      'file_path'=>$file_path,     
                    ]);
        return redirect('/transaction')->with('status','Transaction created successfully with roles');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
    public function getTransactionByUserId(Request $request)
    {
        $userId = $request->userid;
        $html = '';
        $amount =0;
        if($userId=='all')
        {
            $transactions = Transaction::all();  
        }
        else
        {
            $transactions = Transaction::where('cust_id',$userId)->get();
        }
        foreach($transactions as $transaction)
        {
            $credit = '--';
            $debit = '--';
            if(($transaction->transaction_type=='credit'))
            {
                $credit = $transaction->amount;
            }
            else
            {
                $debit = $transaction->amount;
            }
            if ($transaction->transaction_type=='debit')
            {
                $amount = $amount-$transaction->amount;
            }
            else
            {
                $amount = $amount+$transaction->amount;
            }
            $html .= '<tr>';
            $html .= '<td>'.$transaction->transaction_id .'</td><td>'.$credit.'</td><td>'.$debit.'</td><td>'.$amount.'</td>';
            $html .= '</tr>';
        }
        echo $html;
    }
}
