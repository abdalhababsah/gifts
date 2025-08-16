@if($message || $slot->isNotEmpty())
<div {{ $attributes->merge(['class' => 'p-3 mb-3 text-base border rounded-md transition-all duration-300 ' . $getAlertClasses()]) }} 
     role="alert"
     id="alert-{{ uniqid() }}">
    
    <div class="flex items-start">
        <!-- Icon -->
        <div class="flex-shrink-0 mx-1 ">
            <i data-lucide="{{ $getAlertIcon() }}" class="size-5"></i>
        </div>
        
        <!-- Content -->
        <div class="flex-1">
            @if($message)
                {!! $message !!}
            @endif
            
            @if($slot->isNotEmpty())
                {{ $slot }}
            @endif
        </div>
        
        <!-- Dismiss Button -->
        @if($dismissible)
        <button type="button" 
                onclick="dismissAlert(this)"
                class="flex-shrink-0 ml-3 text-current opacity-70 hover:opacity-100 transition-opacity"
                aria-label="Close">
            <i data-lucide="x" class="size-4"></i>
        </button>
        @endif
    </div>
</div>

@once
@push('scripts')
<script>
function dismissAlert(button) {
    const alert = button.closest('[role="alert"]');
    if (alert) {
        alert.style.opacity = '0';
        alert.style.transform = 'translateX(100%)';
        setTimeout(function() {
            alert.remove();
        }, 300);
    }
}
</script>
@endpush
@endonce
@endif