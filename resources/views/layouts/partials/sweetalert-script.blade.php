<script>
    $(document).ready(function() {
        const showSweetAlert = (icon, title, text, timer = null) => {
            Swal.fire({ icon: icon, title: title, text: text, timer: timer, showConfirmButton: !timer });
        };

        @if(session('success'))
            showSweetAlert('success', 'Berhasil!', "{{ session('success') }}", 2500);
        @endif
        @if(session('error'))
            showSweetAlert('error', 'Gagal!', "{{ session('error') }}");
        @endif
        @if(session('warning'))
            showSweetAlert('warning', 'Perhatian!', "{{ session('warning') }}");
        @endif
        @if(session('info'))
            showSweetAlert('info', 'Informasi', "{{ session('info') }}");
        @endif
        @if(session('status')) // Untuk notifikasi dari reset password, dll.
            showSweetAlert('success', 'Informasi', "{{ session('status') }}", 3000);
        @endif
        @if(session('error_kecamatan'))
            showSweetAlert('warning', 'Perhatian!', "{{ session('error_kecamatan') }}");
        @endif

        @php
            $specificAuthErrors = ['login_identifier', 'email', 'nisn', 'password', 'nama_lengkap', 'jalur_pendaftaran', 'kecamatan_domisili'];
            $generalErrors = [];
            if ($errors->any()) {
                foreach ($errors->getMessages() as $field => $messages) {
                    $isSpecific = in_array($field, $specificAuthErrors);
                    $isAuthFailedOrThrottle = false;
                    foreach ($messages as $msg) {
                        if (str_contains($msg, trans('auth.failed')) || str_contains($msg, trans('auth.throttle'))) {
                            $isAuthFailedOrThrottle = true;
                            break;
                        }
                    }
                    if (!$isSpecific && !$isAuthFailedOrThrottle) {
                        foreach ($messages as $message) {
                            if (!in_array($message, $generalErrors)) { $generalErrors[] = $message; }
                        }
                    }
                }
            }
        @endphp

        @if (!empty($generalErrors))
            let errorMessagesAuth = '<ul class="text-start" style="list-style-position: inside; padding-left: 0;">';
            @foreach ($generalErrors as $error)
                errorMessagesAuth += '<li>{{ $error }}</li>';
            @endforeach
            errorMessagesAuth += '</ul>';
            Swal.fire({ icon: 'warning', title: '<strong>Validasi Gagal!</strong>', html: errorMessagesAuth, confirmButtonText: 'OK' });
        @endif
    });
</script>
