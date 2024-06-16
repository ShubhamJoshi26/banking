<x-app-layout>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">

                @if ($errors->any())
                <ul class="alert alert-warning">
                    @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4>Create Transaction
                            <a href="{{ url('transaction') }}" class="btn btn-danger float-end">Back</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('transaction') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="">Amount</label>
                                <input type="text" name="amount" class="form-control" value="{{old('amount')?old('amount'):''}}" />
                            </div>
                            <div class="mb-3">
                                <label for="">Transaction Type</label>
                                <select name="transaction_type" id="transaction_type" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="debit">Withdraw</option>
                                    <option value="credit">Deposit</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="">File</label>
                                <input type="file" name="file" class="form-control" />
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>