@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto mt-8 bg-white shadow rounded p-6">
        <h2 class="text-xl font-semibold mb-4">Record a Sale</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div x-data="saleCalculator()">
            <form action="{{ route('sales.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Product</label>
                    <select name="product_id" class="w-full mt-1 border rounded px-3 py-2" required>
                        <option value="">-- Select Product --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} (Stock: {{ $product->opening_stock }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm">Quantity</label>
                    <input type="number" name="quantity" x-model.number="qty" value="{{ old('quantity') }}"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm">Price (per unit)</label>
                    <input type="number" step="0.1" name="price" x-model.number="price" value="{{ old('price') }}"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm">Discount</label>
                    <input type="number" step="0.1" name="discount" x-model.number="discount"
                        value="{{ old('discount', 0) }}" class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-sm">VAT</label>
                    <input type="number" step="0.1" name="vat" x-model.number="vat" value="{{ old('vat', 0) }}"
                        class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-sm">Paid Amount</label>
                    <input type="number" step="0.1" name="paid_amount" x-model.number="paid" value="{{ old('paid_amount') }}"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Record
                        Sale</button>
                </div>
            </form>

            <div class="mt-4 p-4 bg-gray-50 border rounded">
                <p><strong>Total:</strong> <span x-text="total.toFixed(2)"></span></p>
                <p><strong>Due:</strong> <span x-text="due.toFixed(2)"></span></p>
            </div>
        </div>

    </div>

    <script>
        function saleCalculator() {
            return {
                qty: 1,
                price: 0,
                discount: 0,
                vat: 0,
                paid: 0,

                get total() {
                    return (this.qty * this.price - this.discount + this.vat);
                },

                get due() {
                    return Math.max(this.total - this.paid, 0);
                }
            }
        }
    </script>
@endsection
