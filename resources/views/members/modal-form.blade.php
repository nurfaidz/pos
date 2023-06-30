<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">Tambah Member</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_data" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="member_name">Nama<span class="text-danger"> *</span></label>
                        <input type="text" name="member_name" id="member_name" class="form-control" required>
                        <div class="invalid-feedback member_name_error"></div>
                    </div>
                    <div class="form-group">
                        <label for="phone">Telepon<span class="text-danger"> *jika terdaftar member</span></label>
                        <input type="number" name="phone" id="phone" class="form-control">
                        <div class="invalid-feedback phone_error"></div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email<span class="text-danger"> *jika terdaftar member</span></label>
                        <input type="email" name="email" id="email" class="form-control">
                        <div class="invalid-feedback email_error"></div>
                    </div>
                    <div class="form-group">
                        <label for="status">Member<span class="text-danger"> *jika terdaftar member</span></label>
                        <select name="status" id="status" class="form-control">
                            <option selected value="1">Unmember</option>
                            <option value="2">Member</option>
                        </select>
                        <div class="invalid-feedback member_error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" id="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
