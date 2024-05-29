@extends('theme-views.layouts.app')

@section('title', \App\CPU\translate('Add new notification'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">

@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
                <button onclick="startFCM()"
                    class="btn btn-danger btn-flat">Allow notification
                </button>
            <div class="card mt-3">
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <form action="{{ route('send.web-notification') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Message Title</label>
                            <input type="text" class="form-control" name="title">
                        </div>
                        <div class="form-group">
                            <label>Message Body</label>
                            <textarea class="form-control" name="body"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Send Notification</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script src="https://www.gstatic.com/firebasejs/9.14.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.14.0/firebase-messaging-compat.js"></script>
<script>
    var firebaseConfig = {
        apiKey: "AIzaSyD8eBYoyxMH8k_f7Njw8w3HegmLiMFeah4",
        authDomain: "nichen-397921.firebaseapp.com",
        projectId: "nichen-397921",
        storageBucket: "nichen-397921.appspot.com",
        messagingSenderId: "135987346899",
        appId: "1:135987346899:web:f8a63d35a564feafb08326",
        measurementId: "G-PY9WV67R97"
    };
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();
       function startFCM() {
        Notification.requestPermission().then(function(permission) {
            if (permission === 'granted') {
                messaging.getToken().then(function(response) {
                    // Include CSRF token in the headers
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
    
                    // Make the AJAX request
                    $.ajax({
                        url: '{{ route("store.token") }}',
                        type: 'POST',
                        data: {
                            token: response
                        },
                        dataType: 'JSON',
                        success: function (response) {
                            alert('Token stored.');
                        },
                        error: function (error) {
                            alert(error.responseText);
                        },
                    });
                }).catch(function (error) {
                    alert(error);
                });
            } else {
                console.log('Unable to get permission to notify.');
            }
        });
    }
    
    messaging.onMessage((payload) =>{
        console.log('Message received ', payload);
    })

</script>
<script>
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('firebase-messaging-sw.js')
    .then((registration) => {
        console.log('Service Worker registered with scope:', registration.scope);
    }).catch((err) => {
        console.log('Service worker registration failed: ', err);
    });
}
</script>


@endsection