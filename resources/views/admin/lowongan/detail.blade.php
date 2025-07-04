<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content shadow-sm rounded">

        <div class="modal-header bg-primary text-white rounded-top">
            <h5 class="text-light">Detail Lowongan</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="container mt-4">

                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Perusahaan</label>
                    <div class="border rounded p-2">
                        <p class="form-control-plaintext mb-0">{{ $lowongan->perusahaan->nama }}</p>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Bidang</label>
                    <div class="border rounded p-2">
                        <p class="form-control-plaintext mb-0">{{ $lowongan->bidang->nama }}</p>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Lowongan</label>
                    <div class="border rounded p-2">
                        <p class="form-control-plaintext mb-0">{{ $lowongan->nama }}</p>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Persyaratan</label>
                    <div class="border rounded p-2">
                        <div class="form-control-plaintext mb-0">
                            {!! htmlspecialchars_decode($lowongan->persyaratan) !!}
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi</label>
                    <div class="border rounded p-2">
                        <p class="form-control-plaintext mb-0">{{ $lowongan->deskripsi }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="btn-hapus">
                <i class="bi bi-trash"></i> Hapus
            </button>
            <button type="button" class="btn btn-primary"
                onclick="modalAction('{{ url('/admin/lowongan/edit/' . $lowongan->id_lowongan) }}')">
                <i class="bi bi-pencil-square"></i> Edit
            </button>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#btn-hapus').click(function () {
                Swal.fire({
                    title: 'Yakin ingin menghapus data ini?',
                    text: "Akan menghapus Periode yang dimiliki Lowongan ini, Juga Magang yang telah diikuti Mahasiswa!!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('admin/lowongan/edit/' . $lowongan->id_lowongan) }}",
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Data berhasil dihapus.'
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function () {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Gagal menghapus data. Silakan coba lagi.'
                                });
                            }
                        });
                    }
                });
            });
        })
    </script>