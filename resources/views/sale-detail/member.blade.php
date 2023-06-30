<div class="modal fade" id="modal-member" tabindex="-1" role="dialog" aria-labelledby="modal-member" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal-member">Pilih Member</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-member">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($member as $key => $item)
                            <tr>
                                <td width="5%">{{ $key + 1 }}</td>
                                <td>{{ $item->member_name }}</td>
                                <td>{{ $item->member_name }}</td>
                                <td>{{ $item->phone }}</td>
                                <td>{{ $item->email }}</td>
                                @if ($item->status == 'Member')
                                    <td><span class="badge badge-pill badge-success">Member</span></td>
                                @else
                                    <td><span class="badge badge-pill badge-warning">Unmember</td>
                                @endif
                                {{-- @php
                                    $diskon = $item->discount_member * 100;
                                @endphp
                                <td>{{ $diskon }}%</td> --}}
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs btn-flat"
                                        onclick="selectMember('{{ $item->member_id }}', '{{ $item->member_code }}', '{{ $item->discount_member }}')">
                                        <i class="fa fa-check-circle"></i>
                                        Pilih
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
