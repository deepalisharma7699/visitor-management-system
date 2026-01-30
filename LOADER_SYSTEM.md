# Centralized Loader System Documentation

## Overview

The Mayfair VMS application now includes a comprehensive centralized loader mechanism that provides visual feedback for all asynchronous operations, prevents duplicate actions, and improves the overall user experience.

## Features

✅ **Automatic Loading Detection**
- Triggers automatically for all API requests (fetch, XMLHttpRequest, axios)
- Detects Livewire component updates and actions
- Monitors form submissions
- Handles file uploads

✅ **Visual Feedback**
- Elegant animated spinner with Mayfair branding
- Semi-transparent overlay to prevent interaction
- Smooth fade-in/fade-out transitions
- Pulsing progress indicators

✅ **Action Prevention**
- Automatically disables buttons during loading
- Prevents duplicate form submissions
- Disables interactive elements during Livewire updates
- Visual feedback for disabled states

✅ **Smart Concurrency Handling**
- Manages multiple concurrent requests
- No flickering when requests overlap
- Proper cleanup after all requests complete

## Installation

The loader system has been installed in:

1. **Main Application Layout**: `resources/views/components/layouts/app.blade.php`
2. **Admin Layout**: `resources/views/admin/layout.blade.php`
3. **Loader Component**: `resources/views/components/loader.blade.php`
4. **Component Class**: `app/View/Components/Loader.php`

## Usage

### Automatic Loading (No Code Required)

The loader activates automatically for:

```php
// Livewire actions
<button wire:click="submit">Submit</button>

// Livewire forms
<form wire:submit.prevent="save">...</form>

// Fetch requests
fetch('/api/data').then(...)

// Form submissions
<form action="/submit" method="POST">...</form>
```

### Manual Loading Control

#### Trigger Loading Manually

```javascript
// Trigger loading
window.dispatchEvent(new CustomEvent('loading'));

// Stop loading
window.dispatchEvent(new CustomEvent('loaded'));
```

#### Using Alpine.js

```html
<button @click="
    $dispatch('loading');
    fetch('/api/data')
        .then(() => $dispatch('loaded'))
        .catch(() => $dispatch('loaded'))
">
    Fetch Data
</button>
```

### Disable Loader for Specific Actions

#### Skip Loader for Forms

```html
<form data-no-loader action="/submit" method="POST">
    <!-- This form won't trigger the global loader -->
</form>
```

#### Prevent Element Disable

```html
<button data-no-disable wire:click="action">
    <!-- This button won't be disabled during loading -->
</button>
```

### Livewire Integration

The loader automatically integrates with Livewire:

```php
// In your Livewire component
public function submitForm()
{
    // Loader shows automatically
    $this->validate();
    
    // Perform async operation
    $this->someService->doWork();
    
    // Loader hides automatically when method completes
}
```

#### Add Loading States to Specific Elements

```html
<!-- Show text while loading -->
<div wire:loading wire:target="submit">
    Processing...
</div>

<!-- Disable button while loading -->
<button wire:click="submit" wire:loading.attr="disabled">
    Submit
</button>

<!-- Show spinner on button -->
<button wire:click="submit">
    <span wire:loading.remove>Submit</span>
    <span wire:loading>
        <svg class="animate-spin h-5 w-5" ...></svg>
    </span>
</button>
```

## Customization

### Modify Loader Appearance

Edit `resources/views/components/loader.blade.php`:

```html
<!-- Change colors -->
<div class="border-t-mayfair-gold"> <!-- Change to any color -->

<!-- Change text -->
<p class="text-white font-semibold text-lg">Processing</p>

<!-- Add custom animations -->
<div class="animate-bounce"></div>
```

### Adjust Loading Timeout

In the component JavaScript:

```javascript
// Change timeout from 10 seconds to custom value
setTimeout(() => {
    triggerLoaded();
    // ...
}, 5000); // 5 seconds
```

### Custom Loading Styles

```css
/* Add to your CSS */
.btn-loading {
    opacity: 0.7;
    cursor: wait;
}

.btn-loading::before {
    content: '⏳';
    margin-right: 8px;
}
```

## Examples

### Example 1: API Call with Manual Control

```javascript
async function fetchData() {
    window.dispatchEvent(new CustomEvent('loading'));
    
    try {
        const response = await fetch('/api/visitors');
        const data = await response.json();
        console.log(data);
    } catch (error) {
        console.error(error);
    } finally {
        window.dispatchEvent(new CustomEvent('loaded'));
    }
}
```

### Example 2: Livewire Component with OTP

```html
<div>
    <input wire:model="otp" type="text">
    
    <button wire:click="verifyOTP" wire:loading.attr="disabled">
        <span wire:loading.remove wire:target="verifyOTP">Verify OTP</span>
        <span wire:loading wire:target="verifyOTP">Verifying...</span>
    </button>
    
    <button wire:click="resendOTP" wire:loading.attr="disabled">
        Resend OTP
    </button>
</div>
```

### Example 3: Form with Loader

```html
<form wire:submit.prevent="register">
    <input wire:model="name" type="text">
    <input wire:model="email" type="email">
    
    <!-- Button is automatically disabled during submission -->
    <button type="submit" wire:loading.attr="disabled">
        <span wire:loading.remove>Register</span>
        <span wire:loading>Registering...</span>
    </button>
</form>
```

### Example 4: Multiple Concurrent Requests

```javascript
// All these will show a single loader
Promise.all([
    fetch('/api/visitors'),
    fetch('/api/employees'),
    fetch('/api/projects')
]).then(() => {
    // Loader automatically hides when all complete
    console.log('All data loaded');
});
```

## Troubleshooting

### Loader Not Showing

1. Check that Alpine.js is loaded:
```html
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

2. Verify the loader component is included in your layout:
```blade
<x-loader />
```

3. Check browser console for JavaScript errors

### Loader Not Hiding

1. Ensure all requests call `triggerLoaded()` in finally blocks
2. Check for JavaScript errors interrupting execution
3. Verify the loading counter is not stuck (check with devtools)

### Elements Not Disabling

1. Check that elements don't have `data-no-disable` attribute
2. Verify Livewire is properly initialized
3. Ensure the component is inside a Livewire component context

### Performance Issues

1. Reduce the number of concurrent requests
2. Optimize backend response times
3. Use lazy loading for non-critical data

## Best Practices

1. **Always handle errors**: Ensure `loaded` event is dispatched even on errors
2. **Don't nest loaders**: The global loader handles everything
3. **Use wire:target**: Specify targets for specific loading states
4. **Test concurrent requests**: Ensure the loader handles multiple requests
5. **Provide feedback**: Use wire:loading for inline feedback on specific actions
6. **Keep it simple**: Let the auto-detection handle most cases
7. **Timeout safety nets**: Always include timeout fallbacks for API calls

## Browser Compatibility

- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+
- ✅ Opera 76+

## Dependencies

- Alpine.js 3.x (for reactive state management)
- Tailwind CSS (for styling)
- Livewire 3.x (for Livewire integration)

## Support

For issues or questions:
1. Check this documentation
2. Review browser console for errors
3. Test in incognito mode to rule out extensions
4. Check network tab for failed requests

## Changelog

### Version 1.0.0 (2025-12-30)
- ✅ Initial implementation
- ✅ Automatic detection for all async operations
- ✅ Livewire integration
- ✅ Form submission handling
- ✅ Concurrent request management
- ✅ Visual feedback with animations
- ✅ Element disabling during loading
- ✅ Comprehensive error handling
