<form action="{{ url('/admin/perusahaan/edit/' . $perusahaan->id_perusahaan) }}" method="POST" id="form-edit">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content shadow-sm rounded">

            <div class="modal-header bg-primary text-white rounded-top">
                <h5 class="text-light">Edit Perusahaan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mt-4">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <div class="avatar avatar-2xl mb-3">
                            <label for="file" style="cursor: pointer;">
                                <img id="preview" src="{{ Storage::exists('public/profil/perusahaan/' . $perusahaan->foto_path)
    ? asset('storage/profil/perusahaan/' . $perusahaan->foto_path)
    : asset('template/assets/images/mhs.jpeg') }}" alt="Profile Picture" class="rounded-circle"
                                    style="width: 150px; height: 150px; border: 5px solid blue; object-fit: cover;">
                            </label>
                        </div>
                        <small class="text-muted text-center">Tekan gambar untuk mengganti logo</small>
                        <input type="file" id="file" name="file" accept="image/*" onchange="previewImage(event)"
                            style="display: none;">
                            <button type="button" id="tombolBatal" class="btn btn-sm btn-primary mt-2"
                            style="visibility: hidden;" onclick="batalkanPreview()">Batalkan</button>
                    </div>
                </div>
                <div class="container mt-4">
                    <div class="d-flex justify-content-between">
                        <div class="w-50 me-2">
                            <label for="nama" class="form-label">Nama Perusahaan</label>
                            <input type="text" class="form-control" id="nama" name="nama" required
                                value="{{ $perusahaan->nama }}">
                        </div>
                        <div class="w-50 ms-2"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="w-50 me-2">
                            <div>
                                <label for="telepon" class="form-label">Telepon</label>
                                <input type="text" class="form-control" id="telepon" name="telepon" required
                                    value="{{ $perusahaan->telepon }}">
                            </div>
                            <div class="mt-4">
                                <label for="nama_provinsi" class="form-label">Provinsi:
                                    {{ $perusahaan->provinsi }}</label>
                                <select name="nama_provinsi" class="form-select" id="nama_provinsi" data-placeholder="Perbarui Provinsi">
                                    <option value=""></option>
                                </select>
                                <input type="hidden" name="provinsi" id="provinsi" value="{{ $perusahaan->provinsi }}">
                            </div>
                        </div>
                        <div class="w-50 ms-2">
                            <div>
                                <label for="id_jenis" class="form-label">Jenis Perusahaan</label>
                                <select name="id_jenis" class="form-select" id="id_jenis" data-placeholder="Pilih Jenis Perusahaan" required>
                                    <option value=""></option>
                                    @foreach ($jenis as $item)
                                        <option value="{{ $item->id_jenis }}" {{ $perusahaan->id_jenis == $item->id_jenis ? 'selected' : '' }}>{{ $item->jenis }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-4">
                                <label for="nama_daerah" class="form-label">Daerah: {{ $perusahaan->daerah }}</label>
                                <select name="nama_daerah" class="form-select" id="nama_daerah" data-placeholder="Perbarui Daerah">
                                    <option value=""></option>
                                </select>
                                <input type="hidden" name="daerah" id="daerah" value="{{ $perusahaan->daerah }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container mt-4">
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                            required>{{ $perusahaan->deskripsi }}</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning"
                    onclick="modalAction('{{ url('/admin/perusahaan/detail/' . $perusahaan->id_perusahaan) }}')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    const defaultPreview = document.getElementById('preview').src;

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            document.getElementById('preview').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
        document.getElementById('tombolBatal').style.visibility = 'visible';
    }

    function batalkanPreview() {
        document.getElementById('preview').src = defaultPreview;
        document.getElementById('file').value = "";
        document.getElementById('tombolBatal').style.visibility = 'hidden';
    }
</script>
<script>
    $(document).ready(function () {
        let dataProvinsi = {};
        let dataDaerah = {};

        $.get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json', function (data) {
            data.forEach(function (prov) {
                dataProvinsi[prov.id] = prov.name;
                $('#nama_provinsi').append($('<option>', {
                    value: prov.id,
                    text: prov.name
                }));
            });
        });

        $('#nama_provinsi').on('change', function () {
            const provId = $(this).val();
            $('#provinsi').val(dataProvinsi[provId] || '');
            $('#nama_daerah').empty().append('<option value="">Perbarui Daerah</option>');
            $('#daerah').val('');

            if (provId) {
                $.get(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`, function (data) {
                    data.forEach(function (daerah) {
                        dataDaerah[daerah.id] = daerah.name;
                        $('#nama_daerah').append($('<option>', {
                            value: daerah.id,
                            text: daerah.name
                        }));
                    });
                });
            }
        });

        $('#nama_daerah').on('change', function () {
            const daerahId = $(this).val();
            $('#daerah').val(dataDaerah[daerahId] || '');
        });

        $("#form-edit").validate({
            rules: {
                id_jenis: { required: true },
                nama: { required: true },
                telepon: {
                    required: true,
                    digits: true,
                    minlength: 8
                },
                provinsi: { required: true },
                daerah: { required: true }
            },
            messages: {
                id_jenis: "Pilih jenis perusahaan",
                nama: "Nama perusahaan wajib diisi",
                telepon: {
                    required: "Telepon wajib diisi",
                    digits: "Hanya angka yang diperbolehkan",
                    minlength: "Minimal 8 digit"
                },
                provinsi: "Pilih provinsi",
                daerah: "Pilih daerah"
            },
            errorElement: 'div',
            errorClass: 'invalid-feedback',
            validClass: 'is-valid',
            errorClass: 'is-invalid',
            submitHandler: function (form) {
                const formData = new FormData(form);

                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Sedang memproses data',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Data berhasil disimpan.'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message || 'Terjadi kesalahan saat menyimpan.'
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan.'
                        });
                    }
                });

                // return false;
            }
        });
    });
</script>