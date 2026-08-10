@if($stockItem->variation)
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Unit</th>
                <th>Size</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $stockItem->variation->unit }}</td>
                <td>{{ $stockItem->variation->size }}</td>
                <td>{{ $stockItem->price }}</td>
            </tr>
        </tbody>
    </table>
@else
    <p>No variation found for this stock item.</p>
@endif
