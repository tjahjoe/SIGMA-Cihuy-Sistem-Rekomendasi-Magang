@extends('layouts.tamplate')

@section('content')
    <div class="row">
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card shadow">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon blue mb-2">
                                <i class="bi-arrow-repeat"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Proses</h6>
                            <h6 class="font-extrabold mb-0">{{ $proses }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card shadow">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon green mb-2">
                                <i class="bi-check-circle-fill"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Diterima</h6>
                            <h6 class="font-extrabold mb-0">{{ $diterima }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card shadow">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon blue mb-2">
                                <i class="bi-award-fill"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Lulus</h6>
                            <h6 class="font-extrabold mb-0">{{ $lulus }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card shadow">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon green mb-2">
                                <i class="bi-x-circle-fill"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Ditolak</h6>
                            <h6 class="font-extrabold mb-0">{{ $ditolak }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Kegiatan</h5>
        </div>
        <div class="card-body">
            @if (count($magang))
                <div class="table-responsive">
                    <table class="table" id="table1">
                        <colgroup>
                            <col style="width: 100px;">
                            <col style="width: 100px;">
                            <col style="width: 100px;">
                            <col style="width: 100px;">
                            <col style="width: 100px;">
                            <col style="width: 100px;">
                        </colgroup>
                        <thead>
                            <tr>
                            <th class="d-none">No</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Dosen</th>
                                <th>Perusahaan</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($magang as $i => $item)
                                <tr>
                                <td class="d-none">{{ $i+1 ?? '-' }}</td>
                                    <td>{{ $item->mahasiswa->akun->id_user ?? '-' }}</td>
                                    <td>{{ $item->mahasiswa->nama ?? '-' }}</td>
                                    <td>{{ $item->dosen->nama ?? '-' }}</td>
                                    <td>{{ $item->periode_magang->lowongan_magang->perusahaan->nama ?? '-' }}</td>
                                    <td>                                    
                                        <span class="badge 
                                            @if($item->status == 'diterima') bg-warning
                                            @elseif($item->status == 'lulus') bg-success
                                            @elseif($item->status == 'ditolak') bg-danger
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($item->status ?? '-') }}
                                        </span>
                                    </td>
                                    <!-- <td class="text-center"> <button class="btn btn-sm btn-info btn-detail"
                                            onclick="modalAction('{{ url('/admin/kegiatan/detail/' . $item->id_magang) }}')">
                                            Detail
                                        </button></td> -->
                                    <td class="text-center"> <a href="{{  url('/admin/kegiatan/detail/' . $item->id_magang)  }}" class="btn btn-sm btn-info btn-detail">Detail</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center">
                    <p class="mt-4">Tidak ada kegiatan tersedia</p>
                </div>
            @endif
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    </div>
@endsection
@push('css')
@endpush
@push('js')
    <script src="{{ asset('template/assets/static/js/components/dark.js') }}"></script>
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }
    </script>
@endpush