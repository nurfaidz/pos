<div class="modal fade" id="modal-add-expend" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">Tambah Pengeluaran</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-data" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="description">Deskripsi<span class="text-danger">*</span></label>
                        <input type="text" name="description" id="description" class="form-control" required>
                        <div class="invalid-feedback description_error"></div>
                    </div>
                    <div class="form-group">
                        <label for="nominal">Nominal<span class="text-danger">*</span></label>
                        <input type="numeric" name="nominal" id="nominal" class="form-control" required>
                        <div class="invalid-feedback nominal_error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" id="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-archive-expend" tabindex="-1" role="dialog" aria-labelledby="modal-title"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">Arsip Pengeluaran
                </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-data-archive" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="note">Keterangan<span class="text-danger">*</span></label>
                        <textarea class="form-control" id="note" name="note" rows="3" required></textarea>
                        <div class="invalid-feedback note_error"></div>
                    </div>
                </form>
                <span class="text text-danger">Data ini akan diarsip.</span>
            </div>
            <div class="modal-footer">
                <button type="submit" id="submit-archive" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
