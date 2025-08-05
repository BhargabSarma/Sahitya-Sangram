@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Select Default Delivery Partner</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.set_default_courier') }}">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Courier Name</th>
                    <th>Charges</th>
                </tr>
            </thead>
            <tbody>
                @foreach($partners as $partner)
                <tr>
                    <td>
                        <input type="radio" name="courier_company_id" value="{{ $partner['courier_company_id'] }}"
                               data-courier-name="{{ $partner['courier_name'] }}"
                               data-shipping-price="{{ $partner['rate'] }}"
                               {{ (isset($default) && $default->courier_company_id == $partner['courier_company_id']) ? 'checked' : '' }}>
                    </td>
                    <td>{{ $partner['courier_name'] }}</td>
                    <td>₹{{ $partner['rate'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <input type="hidden" name="courier_name" id="courier_name" value="">
        <input type="hidden" name="shipping_price" id="shipping_price" value="">
        <button type="submit" class="btn btn-success">Set Default</button>
    </form>
</div>
<script>
    function setHiddenInputs() {
        let selected = document.querySelector('input[name="courier_company_id"]:checked');
        if (selected) {
            document.getElementById('courier_name').value = selected.getAttribute('data-courier-name');
            document.getElementById('shipping_price').value = selected.getAttribute('data-shipping-price');
        }
    }

    // Set initial value if a courier is already selected
    setHiddenInputs();

    // Change event
    document.querySelectorAll('input[name="courier_company_id"]').forEach(function(radio) {
        radio.addEventListener('change', setHiddenInputs);
    });
</script>
@endsection