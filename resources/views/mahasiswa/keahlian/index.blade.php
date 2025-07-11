<!-- KEAHLIAN SECTION -->
<div class="section-wrapper mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="fw-bold mb-0">Keahlian</h5>
        @if (count($keahlian) != count($bidang))
            <button type="button" class="btn-edit-section btn btn-success"
                onclick="modalAction('{{ url('/mahasiswa/profil/edit/keahlian/tambah') }}')">
                <i class="bi bi-plus"></i> Tambah
            </button>
        @endif
    </div>

    <p class="text-muted mb-3">Keahlian yang kamu miliki beserta skala prioritasnya</p>

    @if (count($keahlian))
        @foreach ($keahlian as $item)
            <div class="card mb-4 shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="d-inline-block me-2 fw-bold">
                                <h5>{{ $item->prioritas }}</h5>
                            </div>
                            <div class="d-inline-block fw-bold">
                                <h5>{{ $item->bidang->nama }}</h5>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn-edit-section btn btn-primary me-2"
                                onclick="modalAction('{{ url('/mahasiswa/profil/edit/keahlian/edit/' . $item->id_keahlian_mahasiswa) }}')">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <button type="button" class="btn-delete-keahlian btn btn-danger"
                                data-url="{{ url('/mahasiswa/profil/edit/keahlian/' . $item->id_keahlian_mahasiswa . '/' . $item->prioritas) }}">
                                <i class="bi bi-trash"></i>
                            </button>

                        </div>
                    </div>
                    <div>
                        <div>
                        <textarea disabled required style="width: 100%;" class="p-2">{{ $item->keahlian }}</textarea>
                    </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.btn-delete-keahlian').on('click', function () {
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