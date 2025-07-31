<table class="table table-bordered">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Size</th>
            <th>Color</th>
        </tr>
    </thead>
    <tbody>
        @foreach($product->variations as $variation)
            <tr>
                <td>{{ $variation->product->name ?? '-' }}</td>
                <td>{{ $variation->size }}</td>
                <td>{{ $variation->unit }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
