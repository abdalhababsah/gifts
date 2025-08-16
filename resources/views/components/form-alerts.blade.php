{{-- Success Messages --}}
@if(session('success'))
    <x-alert type="success" :message="session('success')" />
@endif

{{-- Error Messages --}}
@if(session('error'))
    <x-alert type="error" :message="session('error')" />
@endif

{{-- Info Messages --}}
@if(session('info'))
    <x-alert type="info" :message="session('info')" />
@endif

{{-- Warning Messages --}}
@if(session('warning'))
    <x-alert type="warning" :message="session('warning')" />
@endif

{{-- Validation Errors --}}
@if($errors->any())
    <x-alert type="error">
        @if($errors->count() === 1)
            {{ $errors->first() }}
        @else
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </x-alert>
@endif

{{-- Status Messages (for email verification, etc.) --}}
@if(session('status'))
    <x-alert type="info" :message="session('status')" />
@endif