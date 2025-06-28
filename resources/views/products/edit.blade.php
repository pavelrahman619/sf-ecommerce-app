@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-8">
    <h2 class="text-xl font-bold mb-4">Edit Product</h2>
    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf @method('PUT')
        @include('products.partials.form', ['button' => 'Update'])
    </form>
</div>
@endsection
