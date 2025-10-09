@php
$logoPemdaPath = public_path('kaiadmin/assets/img/kaiadmin/logoindramayu.png')
$logoSMPPath = public_path('kaiadmin/assets/img/kaiadmin/favicon.png');

// Menggunakan file_exists untuk mencegah error jika file tidak ditemukan
$logoPemda = file_exists($logoPemdaPath) ? base64_encode(file_get_contents($logoPemdaPath)) : null;
$logoSMP = file_exists($logoSMPPath) ? base64_encode(file_get_contents($logoSMPPath)) : null;
@endphp

{{-- KOP SURAT --}}
<table class="header-table">
    <tr>
        <td style="width: 15%; text-align: left;">
            @if($logoPemda)
            <img src="data:image/png;base64,{{ $logoPemda }}" class="logo" alt="Logo PEMDA">
            @endif
        </td>
        <td style="width: 70%;" class="kop-surat">
            <div class="line1">PEMERINTAH KABUPATEN INDRAMAYU</div>
            <div class="line2">SMP UNGGULAN SINDANG</div>
            <div class="line3">Jl. Pendidikan No. 123, Sindang, Kab. Indramayu | Telp: (021) 12345678</div>
        </td>
        <td style="width: 15%; text-align: right;">
            @if($logoSMP)
            <img src="data:image/png;base64,{{ $logoSMP }}" class="logo" alt="Logo SMP">
            @endif
        </td>
    </tr>
</table>
<hr class="header-line">
