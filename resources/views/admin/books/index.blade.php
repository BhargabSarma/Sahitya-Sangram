{{-- filepath: resources/views/admin/books/index.blade.php --}}
@extends('layouts.admin')

@section('content')
    <h2>Set Discounts for Books</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form id="discountForm" method="POST" action="{{ route('admin.books.discount.bulkUpdate') }}">
        @csrf

        {{-- Hard Cover Section --}}
        <div class="mb-5 p-3 border rounded bg-light">
            <h3 class="mb-3">Hard Cover Discounts</h3>
            <div class="mb-3 d-flex gap-2 align-items-end">
                <label for="hard_cover_discount_input" class="form-label">Discount (%)</label>
                <input type="number" id="hard_cover_discount_input" min="0" max="100" value="0" class="form-control"
                    style="width:120px;">
                <button type="button" class="btn btn-primary" onclick="applyDiscount('hard', true)">Apply to
                    Selected</button>
                <button type="button" class="btn btn-secondary" onclick="selectAll('hard')">Select All</button>
                <button type="button" class="btn btn-warning" onclick="resetAll('hard')">Reset All</button>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Title</th>
                        <th>Original Price</th>
                        <th>Discount (%)</th>
                        <th>Price After Discount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $book)
                        <tr>
                            <td>
                                <input type="checkbox" class="hard-checkbox" data-book="{{ $book->id }}">
                            </td>
                            <td>{{ $book->title }}</td>
                            <td>₹{{ $book->hard_copy_price }}</td>
                            <td>
                                <input type="number" name="discounts[{{ $book->id }}][hard_copy]" min="0" max="100"
                                    value="{{ old('discounts.' . $book->id . '.hard_copy', $book->hard_copy_discount) }}"
                                    data-price="{{ $book->hard_copy_price }}" data-type="hard" data-book="{{ $book->id }}">
                            </td>
                            <td>
                                @php
                                    $hardDiscount = $book->hard_copy_discount ?? 0;
                                    $hardFinal = $book->hard_copy_price * (1 - $hardDiscount / 100);
                                @endphp
                                ₹<span id="hard_final_{{ $book->id }}">{{ number_format($hardFinal, 2) }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Digital Copy Section --}}
        <div class="mb-5 p-3 border rounded bg-light">
            <h3 class="mb-3">Digital Copy Discounts</h3>
            <div class="mb-3 d-flex gap-2 align-items-end">
                <label for="digital_discount_input" class="form-label">Discount (%)</label>
                <input type="number" id="digital_discount_input" min="0" max="100" value="0" class="form-control"
                    style="width:120px;">
                <button type="button" class="btn btn-primary" onclick="applyDiscount('digital', true)">Apply to
                    Selected</button>
                <button type="button" class="btn btn-secondary" onclick="selectAll('digital')">Select All</button>
                <button type="button" class="btn btn-warning" onclick="resetAll('digital')">Reset All</button>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Title</th>
                        <th>Original Price</th>
                        <th>Discount (%)</th>
                        <th>Price After Discount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $book)
                        <tr>
                            <td>
                                <input type="checkbox" class="digital-checkbox" data-book="{{ $book->id }}">
                            </td>
                            <td>{{ $book->title }}</td>
                            <td>₹{{ $book->digital_price }}</td>
                            <td>
                                <input type="number" name="discounts[{{ $book->id }}][digital_copy]" min="0" max="100"
                                    value="{{ old('discounts.' . $book->id . '.digital_copy', $book->digital_discount) }}"
                                    data-price="{{ $book->digital_price }}" data-type="digital" data-book="{{ $book->id }}">
                            </td>
                            <td>
                                @php
                                    $digitalDiscount = $book->digital_discount ?? 0;
                                    $digitalFinal = $book->digital_price * (1 - $digitalDiscount / 100);
                                @endphp
                                ₹<span id="digital_final_{{ $book->id }}">{{ number_format($digitalFinal, 2) }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>
    <script>
        // Live update price after discount
        document.querySelectorAll('input[type="number"][name^="discounts"]').forEach(function (input) {
            input.addEventListener('input', function () {
                var price = parseFloat(input.getAttribute('data-price'));
                var discount = parseFloat(input.value) || 0;
                var bookId = input.getAttribute('data-book');
                var type = input.getAttribute('data-type');
                var final = price * (1 - discount / 100);
                document.getElementById(type + '_final_' + bookId).textContent = final.toFixed(2);
            });
        });

        // Apply discount to selected books and submit form
        function applyDiscount(type, submit) {
            var discountValue = type === 'hard'
                ? document.getElementById('hard_cover_discount_input').value
                : document.getElementById('digital_discount_input').value;
            var checkboxes = document.querySelectorAll('.' + type + '-checkbox');
            checkboxes.forEach(function (checkbox) {
                if (checkbox.checked) {
                    var bookId = checkbox.getAttribute('data-book');
                    var input = document.querySelector('input[name="discounts[' + bookId + '][' + (type === 'hard' ? 'hard_copy' : 'digital_copy') + ']"]');
                    if (input) {
                        input.value = discountValue;
                        input.dispatchEvent(new Event('input'));
                    }
                }
            });
            if (submit) {
                document.getElementById('discountForm').submit();
            }
        }

        // Select all checkboxes in section
        function selectAll(type) {
            document.querySelectorAll('.' + type + '-checkbox').forEach(function (checkbox) {
                checkbox.checked = true;
            });
        }

        // Reset all checkboxes and discount inputs in section
        function resetAll(type) {
            document.querySelectorAll('.' + type + '-checkbox').forEach(function (checkbox) {
                checkbox.checked = false;
            });
            document.querySelectorAll('input[type="number"][name^="discounts"]').forEach(function (input) {
                if (input.getAttribute('data-type') === type) {
                    input.value = 0;
                    input.dispatchEvent(new Event('input'));
                }
            });
            if (type === 'hard') {
                document.getElementById('hard_cover_discount_input').value = 0;
            } else {
                document.getElementById('digital_discount_input').value = 0;
            }
        }
    </script>
@endsection