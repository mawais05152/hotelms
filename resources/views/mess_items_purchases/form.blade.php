 <div class="purchase-item row mb-3 border p-2">
                        <div class="col-md-4">
                            <label>Mess Purchase Name</label>
                            <input type="text" name="purchases[0][ingredient_name]" class="form-control" required>
                        </div>

                        <div class="col-md-2">
                            <label>Quantity</label>
                            <input type="number" name="purchases[0][quantity]" class="form-control" step="0.01" required>
                        </div>

                        <div class="col-md-2">
                            <label>Unit</label>
                            <input type="text" name="purchases[0][unit]" class="form-control" required>
                        </div>

                        <div class="col-md-2">
                            <label>Price Per Unit</label>
                            <input type="number" name="purchases[0][price_per_unit]" class="form-control" step="0.01" required>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-success btn-sm add-item me-1">+</button>
                            <button type="button" class="btn btn-danger btn-sm remove-item">−</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label>Purchased By</label>
                        <input type="text" name="purchased_by" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Purchased At</label>
                        <input type="date" name="purchased_at" class="form-control" required>
                    </div>
                </div>
