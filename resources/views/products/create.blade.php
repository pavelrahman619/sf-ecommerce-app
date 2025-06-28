@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-8">
    <h2 class="text-xl font-bold mb-4">Add Product</h2>
    <form action="{{ route('products.store') }}" method="POST">
        @include('products.partials.form', ['button' => 'Create'])
    </form>
</div>
@endsection
