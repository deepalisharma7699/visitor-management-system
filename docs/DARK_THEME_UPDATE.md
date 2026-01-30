# Dark Theme Update

## Overview
Updated the Mayfair VMS frontend with a modern dark theme featuring the "Plus Jakarta Sans" font family and gold accent colors.

## Design System

### Colors
- **Primary Gold**: `#E8B923` (mayfair-gold) - Buttons, accents, highlights
- **Dark Background**: `#0A0A0A` (mayfair-dark) - Main background, input fields
- **Gray Background**: `#1A1A1A` (mayfair-gray) - Cards, containers
- **Border**: `#333333` (mayfair-border) - Element borders

### Typography
- **Font Family**: "Plus Jakarta Sans", sans-serif
- **Text Colors**:
  - Primary: White
  - Secondary: Gray-400
  - Tertiary: Gray-500

## Updated Components

### 1. Tailwind Configuration (`tailwind.config.js`)
- Added Plus Jakarta Sans font family
- Extended color palette with custom Mayfair colors
- Maintained all existing Tailwind utilities

### 2. App Layout (`resources/views/components/layouts/app.blade.php`)
- Changed Google Fonts import to Plus Jakarta Sans
- Updated body background to `mayfair-dark`
- Header: Dark gray background with gold logo
- Footer: Dark theme with gray text
- All text colors adjusted for dark theme

### 3. Visitor Registration Component (`resources/views/livewire/visitor-registration.blade.php`)

#### Progress Bar
- Dark background with gold progress indicator
- White/gray text for better contrast

#### Step 1: Visitor Type Selection
- Dark card backgrounds with border
- Gold hover effects
- Icon backgrounds with gold accent
- White headings with gray descriptions

#### Step 2: Basic Information
- Dark input fields with borders
- Gold focus rings
- White text with gray placeholders
- Gold "Send OTP" button
- Gray "Back" button with border

#### Step 3: OTP Verification
- Dark input field with centered text
- Gold accent icon border
- Gold mobile number display
- Gold resend link
- Gold submit button

#### Step 4: Additional Details
- All form inputs with dark backgrounds
- Select dropdowns with dark theme
- Accompanying persons cards with nested dark backgrounds
- Radio buttons with gold accent
- Gold "Complete Registration" button

#### Flash Messages
- Success: Dark green with green border
- Error: Dark red with red border

## Key Features

### Visual Enhancements
1. **Consistent Theming**: All elements follow the dark theme palette
2. **High Contrast**: White text on dark backgrounds for readability
3. **Gold Accents**: Strategic use of gold for CTAs and important elements
4. **Smooth Transitions**: Hover effects on all interactive elements
5. **Border Definition**: Subtle borders to define component boundaries

### Accessibility
- High contrast ratios for text
- Clear focus states with gold rings
- Consistent button styling
- Proper color hierarchy

### User Experience
- Modern, premium look
- Clear visual feedback on interactions
- Consistent spacing and padding
- Mobile-responsive design maintained

## Testing Checklist

- [x] Build completed successfully
- [ ] Test visitor type selection
- [ ] Test form inputs with dark backgrounds
- [ ] Verify OTP flow styling
- [ ] Check select dropdown appearance
- [ ] Test accompanying persons functionality
- [ ] Verify mobile responsiveness
- [ ] Check all hover states
- [ ] Verify focus states on inputs

## Browser Compatibility
The design uses standard Tailwind CSS utilities and should work on:
- Chrome/Edge (Latest)
- Firefox (Latest)
- Safari (Latest)
- Mobile browsers

## Next Steps
1. Test the registration flow thoroughly
2. Update admin dashboard if needed
3. Consider adding dark/light theme toggle
4. Update documentation screenshots
