@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4">Product List</h2>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 px-4 py-2 mb-4">{{ session('success') }}</div>
    @endif

    <a href="{{ route('products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Add Product</a>

    <table class="min-w-full bg-white shadow rounded">
        <thead>
            <tr>
                <th class="py-2 px-4 border">Name</th>
                <th class="py-2 px-4 border">Opening Stock</th>
                <th class="py-2 px-4 border">Price</th>
                <th class="py-2 px-4 border">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td class="py-2 px-4 border">{{ $product->name }}</td>
                <td class="py-2 px-4 border">{{ $product->opening_stock }}</td>
                <td class="py-2 px-4 border">{{ $product->price }}</td>
                <td class="py-2 px-4 border">
                    <a href="{{ route('products.edit', $product) }}" class="text-blue-500">Edit</a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button class="text-red-500 ml-2" onclick="return confirm('Delete this product?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
