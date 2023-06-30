<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
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
                    <div class="form-group row">
                        <label for="name" class="col-lg-3 col-lg-offset-1 control-label">Nama<span
                                class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" name="name" id="name" class="form-control" required autofocus>
                            <div class="invalid-feedback name_error"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-lg-3 col-lg-offset-1 control-label">Email<span
                                class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="email" name="email" id="email" class="form-control" required>
                            <div class="invalid-feedback email_error"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-lg-3 col-lg-offset-1 control-label">Password<span
                                class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="password" name="password" id="password" class="form-control" required
                                minlength="6">
                            <div class="invalid-feedback password_error"></div>
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label for="password_confirmation" class="col-lg-3 col-lg-offset-1 control-label">Konfirmasi
                            Password</label>
                        <div class="col-lg-6">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" required data-match="#password">
                            <div class="invalid-feedback password_confirmation_error"></div>
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <label for="role" class="col-lg-3 col-lg-offset-1 control-label">Role<span
                                class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <select name="role" id="role" class="select2 form-control w-100">
                                <option hidden disabled selected value>Pilih User</option>
                                <option value="admin">Admin</option>
                                <option value="kasir">Kasir</option>
                            </select>
                            <div class="invalid-feedback role_error"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" id="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
