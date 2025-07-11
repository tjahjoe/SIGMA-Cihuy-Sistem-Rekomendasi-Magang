<div class="section-wrapper mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold mb-0">Dokumen</h5>
        <button type="button" class="btn-edit-section btn btn-success"
            onclick="modalAction('{{ url('/mahasiswa/profil/edit/dokumen/tambah') }}')">
            <i class="bi bi-plus"></i> Tambah
        </button>
    </div>
    @if (count($dokumen))
        <div id="dokumen-container">
            @foreach ($dokumen as $item)
                <div class="card mb-4 shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="bg-primary bg-opacity-10 px-3 py-1 rounded d-inline-block">
                                    {{ $item->nama }}
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn-edit-section btn btn-primary me-2"
                                    onclick="modalAction('{{ url('/mahasiswa/profil/edit/dokumen/edit/' . $item->id_dokumen) }}')">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <button type="button" class="btn-delete-dokumen btn btn-danger"
                                    data-url="{{ url('/mahasiswa/profil/edit/dokumen/' . $item->id_dokumen) }}">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </div>
                        </div>
                        <div>
                            <embed src="{{ url('storage/dokumen/' . $item->file_path) }}" type="application/pdf" width="100%"
                                height="1000px" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.btn-delete-dokumen').on('click', function () {
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