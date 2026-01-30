<!-- Global Loading Overlay -->
<div 
    x-data="loaderManager()" 
    x-show="isLoading"
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="loader-overlay"
    role="status"
    aria-live="polite"
    aria-label="Loading"
>
    <div class="loader-content">
        <!-- Spinner Container -->
        <div class="bg-mayfair-gray rounded-2xl shadow-2xl p-8 flex flex-col items-center space-y-4 border border-mayfair-border">
            <!-- Modern SVG Loader Animation -->
            <div class="relative w-20 h-20">
                <!-- Option 1: Circular Progress Loader -->
                <svg class="animate-spin w-20 h-20" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <!-- Background Circle -->
                    <circle cx="50" cy="50" r="40" fill="none" stroke="#2D3748" stroke-width="6" opacity="0.2"/>
                    <!-- Animated Circle -->
                    <circle cx="50" cy="50" r="40" fill="none" stroke="#D4AF37" stroke-width="6" 
                            stroke-linecap="round" stroke-dasharray="251.2" stroke-dashoffset="188.4">
                        <animateTransform attributeName="transform" type="rotate" 
                                        from="0 50 50" to="360 50 50" dur="1.5s" repeatCount="indefinite"/>
                    </circle>
                </svg>
                
                <!-- Pulsing Center Dot -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-4 h-4 bg-mayfair-gold rounded-full animate-pulse"></div>
                </div>
            </div>
            
            <!-- Loading Text -->
            <div class="text-center">
                <p class="text-white font-semibold text-lg">Processing</p>
                <p class="text-gray-400 text-sm mt-1">Please wait...</p>
            </div>
            
            <!-- Animated Progress Dots -->
            <div class="flex space-x-2">
                <div class="w-2.5 h-2.5 bg-mayfair-gold rounded-full animate-bounce" style="animation-delay: 0s"></div>
                <div class="w-2.5 h-2.5 bg-mayfair-gold rounded-full animate-bounce" style="animation-delay: 0.15s"></div>
                <div class="w-2.5 h-2.5 bg-mayfair-gold rounded-full animate-bounce" style="animation-delay: 0.3s"></div>
            </div>
        </div>
    </div>
</div>

<!-- Livewire Loading Indicator (Wire:loading alternative) -->
<style>
    /* Alpine.js cloak */
    [x-cloak] {
        display: none !important;
    }
    
    /* Loader Overlay Styles */
    .loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100vw;
        height: 100vh;
        z-index: 999999;
        background-color: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: all;
        cursor: wait;
    }
    
    .loader-content {
        pointer-events: none;
    }
    
    /* Disable buttons during loading */
    button[wire\:loading],
    button[wire\:loading\.attr="disabled"],
    .loading-disabled {
        opacity: 0.6;
        cursor: not-allowed;
        pointer-events: none;
    }
    
    /* Custom loading states */
    .btn-loading {
        position: relative;
        pointer-events: none;
    }
    
    .btn-loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        margin-left: -10px;
        margin-top: -10px;
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

<script>
    // Alpine.js loader manager component
    function loaderManager() {
        return {
            isLoading: false,
            count: 0,
            
            init() {
                console.log('Loader Alpine component initialized');
                
                // Listen to custom loading events
                window.addEventListener('loading', () => {
                    this.count++;
                    this.isLoading = true;
                    console.log('✓ Loading triggered, count:', this.count);
                });
                
                window.addEventListener('loaded', () => {
                    this.count--;
                    if (this.count <= 0) {
                        this.count = 0;
                        this.isLoading = false;
                    }
                    console.log('✓ Loaded triggered, count:', this.count);
                });
            }
        }
    }
    
    // Enhanced loader management for all async operations
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Loader system initialized');
        
        // Track actual network requests only
        let activeRequests = new Set();
        
        // Livewire-specific hooks - only for actual network requests
        document.addEventListener('livewire:init', () => {
            console.log('Livewire hooks registered');
            
            // Use message.sent hook instead of commit - this only fires for actual network requests
            Livewire.hook('message.sent', (message, component) => {
                const requestId = Date.now() + Math.random();
                activeRequests.add(requestId);
                console.log('✓ Livewire network request started');
                window.dispatchEvent(new CustomEvent('loading'));
                
                // Store request ID for cleanup
                message._loaderId = requestId;
            });
            
            Livewire.hook('message.processed', (message, component) => {
                if (message._loaderId && activeRequests.has(message._loaderId)) {
                    activeRequests.delete(message._loaderId);
                    console.log('✓ Livewire request completed');
                    window.dispatchEvent(new CustomEvent('loaded'));
                }
            });
            
            Livewire.hook('message.failed', (message, component) => {
                if (message._loaderId && activeRequests.has(message._loaderId)) {
                    activeRequests.delete(message._loaderId);
                    console.log('✗ Livewire request failed');
                    window.dispatchEvent(new CustomEvent('loaded'));
                }
            });
        });
        
        // Intercept all fetch/axios requests
        const originalFetch = window.fetch;
        window.fetch = function(...args) {
            window.dispatchEvent(new CustomEvent('loading'));
            return originalFetch.apply(this, args)
                .then(response => {
                    window.dispatchEvent(new CustomEvent('loaded'));
                    return response;
                })
                .catch(error => {
                    window.dispatchEvent(new CustomEvent('loaded'));
                    throw error;
                });
        };
        
        // Intercept XMLHttpRequest
        const originalOpen = XMLHttpRequest.prototype.open;
        const originalSend = XMLHttpRequest.prototype.send;
        
        XMLHttpRequest.prototype.open = function(...args) {
            this._url = args[1];
            return originalOpen.apply(this, args);
        };
        
        XMLHttpRequest.prototype.send = function(...args) {
            // Skip if it's a Livewire update (handled by Livewire hooks)
            if (!this._url || !this._url.includes('livewire/update')) {
                window.dispatchEvent(new CustomEvent('loading'));
                
                this.addEventListener('loadend', function() {
                    window.dispatchEvent(new CustomEvent('loaded'));
                });
            }
            
            return originalSend.apply(this, args);
        };
        
        // Form submission handler
        document.addEventListener('submit', function(e) {
            if (!e.target.hasAttribute('data-no-loader')) {
                window.dispatchEvent(new CustomEvent('loading'));
                
                // Disable form elements
                const formElements = e.target.querySelectorAll('input, button, select, textarea');
                formElements.forEach(el => {
                    el.disabled = true;
                    el.classList.add('loading-disabled');
                });
                
                // Re-enable after timeout (safety net)
                setTimeout(() => {
                    window.dispatchEvent(new CustomEvent('loaded'));
                    formElements.forEach(el => {
                        el.disabled = false;
                        el.classList.remove('loading-disabled');
                    });
                }, 10000);
            }
        });
        
        // Helper functions
        function disableInteractiveElements(container) {
            const elements = container.querySelectorAll('button, input, select, textarea, a[href]');
            elements.forEach(el => {
                if (!el.hasAttribute('data-no-disable')) {
                    el.setAttribute('data-was-disabled', el.disabled);
                    el.disabled = true;
                    el.classList.add('loading-disabled');
                }
            });
        }
        
        function enableInteractiveElements(container) {
            const elements = container.querySelectorAll('button, input, select, textarea, a[href]');
            elements.forEach(el => {
                if (el.getAttribute('data-was-disabled') !== 'true') {
                    el.disabled = false;
                }
                el.removeAttribute('data-was-disabled');
                el.classList.remove('loading-disabled');
            });
        }
    });
</script>
<?php /**PATH E:\GitProjects\staging\mayfair_VMS\resources\views/components/loader.blade.php ENDPATH**/ ?>