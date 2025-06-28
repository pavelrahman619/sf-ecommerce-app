@csrf
<div class="mb-4">
    <label class="block text-sm mb-1">Name</label>
    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="w-full border rounded px-3 py-2">
</div>

<div class="mb-4">
    <label class="block text-sm mb-1">Opening Stock</label>
    <input type="number" name="opening_stock" value="{{ old('opening_stock', $product->opening_stock ?? 0) }}" class="w-full border rounded px-3 py-2">
</div>

<div class="mb-4">
    <label class="block text-sm mb-1">Price</label>
    <input type="number" name="price" step="0.1" value="{{ old('price', $product->price ?? 0) }}" class="w-full border rounded px-3 py-2">
</div>

<button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">{{ $button ?? 'Save' }}</button>
