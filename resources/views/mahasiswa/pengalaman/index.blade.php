<!-- PENGALAMAN SECTION -->
<div class="section-wrapper mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold mb-0">Pengalaman</h5>
        <button type="button" class="btn-edit-section btn btn-success"
            onclick="modalAction('{{ url('/mahasiswa/profil/edit/pengalaman/tambah') }}')">
            <i class="bi bi-plus"></i> Tambah
        </button>
    </div>

    <p class="text-muted mb-3">Pengalaman yang pernah dijalani.</p>

    @if (count($pengalaman))
        @foreach ($pengalaman as $item)
            <div class="card mb-4 shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-end align-items-center mb-3">

                        <button type="button" class="btn-edit-section btn btn-primary me-2"
                            onclick="modalAction('{{ url('/mahasiswa/profil/edit/pengalaman/edit/' . $item->id_pengalaman) }}')">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <button type="button" class="btn-delete-pengalaman btn btn-danger"
                            data-url="{{ url('/mahasiswa/profil/edit/pengalaman/' . $item->id_pengalaman) }}">
                            <i class="bi bi-trash"></i>
                        </button>

                    </div>
                    <div>
                        <textarea disabled required style="width: 100%;" class="p-2">{{ $item->deskripsi }}</textarea>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.btn-delete-pengalaman').on('click', function () {
            const url = $(this).data('url');

            Swal.fire({
                title: 'Yakin ingin menghapus data ini?',
                text: "Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
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
    });
</script>