<x-app-layout>

    <div class="container mt-5">
        @if(Auth::user()->hasRole('admin'))
        <div class="col-md-3">
            <select name="users" id="users" class="form-control">
                <option value="all">All</option>
                {!!$allUsersOprion!!}
            </select>
        </div>
        @endif
    </div>

    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-3">
                    <label for="">Total Balance: &nbsp;&nbsp;&nbsp;</label>
                    <label for="">
                        @php $totalamount = 0;  @endphp
                        @foreach ($transactions as $transaction)
                        @if ($transaction->transaction_type=='debit')
                        @php $totalamount = $totalamount - $transaction->amount  @endphp
                        @else
                        @php $totalamount = $totalamount + $transaction->amount  @endphp
                        @endif
                        @endforeach
                        {{$totalamount}}
                    </label>
                </div>
                @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <div class="card mt-3">
                    <div class="card-header">
                        <h4>
                            Transactions
                            @can('create transaction')
                            <a href="{{ url('transaction/create') }}" class="btn btn-primary float-end">Add Transaction</a>
                            @endcan
                        </h4>
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Transaction Id</th>
                                    <th>Diposit</th>
                                    <th>Withdraw</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody id="alltransactions">
                            @php $totalamount = 0;  @endphp
                                @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->transaction_id }}</td>
                                    <td>@if($transaction->transaction_type=='credit')
                                        {{$transaction->amount}}
                                        @else
                                        {{__('--')}}
                                        @endif
                                    </td>
                                    <td>@if($transaction->transaction_type=='debit')
                                        {{$transaction->amount}}
                                        @else
                                        {{__('--')}}
                                        @endif
                                    </td>
                                    <td>
                                    @if ($transaction->transaction_type=='debit')
                                    @php $totalamount = $totalamount - $transaction->amount  @endphp
                                        {{$totalamount}}
                                    @else
                                    @php $totalamount = $totalamount + $transaction->amount  @endphp
                                    {{$totalamount}}
                                    @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>