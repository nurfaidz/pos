<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">Tambah Kategori</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_data" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="product_name">Nama<span class="text-danger">*</span></label>
                        <input type="text" name="product_name" id="product_name" class="form-control" required>
                        <div class="invalid-feedback product_name_error"></div>
                    </div>
                    <div class="form-group">
                        <label for="category_id">Kategori<span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" required class="select2 form-control w-100">
                            <option hidden disabled selected value>Pilih Kategori</option>
                        </select>
                        <div class="invalid-feedback category_error"></div>
                    </div>
                    <div class="form-group">
                        <label for="merk" id="label-merk">Merk</label>
                        <input type="text" name="merk" id="merk" class="form-control">
                        <div class="invalid-feedback merk_error"></div>
                    </div>
                    <div class="form-group">
                        <label for="price">Harga Jual<span class="text-danger">*</span></label>
                        <input type="number" name="price" id="price" class="form-control" required>
                        <div class="invalid-feedback price_error"></div>
                    </div>
                    <div class="form-group">
                        <label for="stock" id="label-stock">Stok<span class="text-danger"
                                id="label-stock">*</span></label>
                        <input type="number" name="stock" id="stock" class="form-control">
                        <div class="invalid-feedback stock_error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" id="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
