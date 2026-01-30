<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-mayfair-gray rounded-2xl shadow-2xl overflow-hidden border border-mayfair-border">
        <!-- Progress Bar -->
        <div class="bg-mayfair-dark px-6 py-4 border-b border-mayfair-border">
            <div class="flex items-center justify-between mb-2">
                <h2 class="text-white font-semibold text-lg">Visitor Registration</h2>
                <span class="text-gray-400 text-sm">Step <?php echo e($currentStep); ?> of <?php echo e($totalSteps); ?></span>
            </div>
            <div class="w-full bg-mayfair-border rounded-full h-2">
                <div class="bg-mayfair-gold rounded-full h-2 transition-all duration-300" 
                     style="width: <?php echo e(($currentStep / $totalSteps) * 100); ?>%"></div>
            </div>
        </div>

        <div class="p-6 sm:p-8">
            <!-- Flash Messages -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('success')): ?>
                <div class="mb-6 bg-green-900/30 border border-green-700 text-green-300 px-4 py-3 rounded-lg">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(session()->has('error')): ?>
                <div class="mb-6 bg-red-900/30 border border-red-700 text-red-300 px-4 py-3 rounded-lg">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Step 1: Select Visitor Type -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep === 1): ?>
                <div class="space-y-6">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-white mb-2">Welcome to Mayfair</h3>
                        <p class="text-gray-400">Please select your visitor type to begin registration</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Guest -->
                        <button wire:click="selectVisitorType('guest')" 
                                class="group p-6 border-2 border-mayfair-border rounded-xl hover:border-mayfair-gold hover:bg-mayfair-dark transition-all duration-200">
                            <div class="flex flex-col items-center space-y-3">
                                <div class="w-16 h-16 bg-mayfair-dark group-hover:bg-mayfair-gold rounded-full flex items-center justify-center transition-colors border border-mayfair-border">
                                    <svg class="w-8 h-8 text-mayfair-gold group-hover:text-mayfair-dark transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-white">Guest</h4>
                                <p class="text-sm text-gray-400 text-center">Vendor, Contractor, Family, Interview, etc.</p>
                            </div>
                        </button>

                        <!-- Broker -->
                        <button wire:click="selectVisitorType('broker')" 
                                class="group p-6 border-2 border-mayfair-border rounded-xl hover:border-mayfair-gold hover:bg-mayfair-dark transition-all duration-200">
                            <div class="flex flex-col items-center space-y-3">
                                <div class="w-16 h-16 bg-mayfair-dark group-hover:bg-mayfair-gold rounded-full flex items-center justify-center transition-colors border border-mayfair-border">
                                    <svg class="w-8 h-8 text-mayfair-gold group-hover:text-mayfair-dark transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-white">Broker</h4>
                                <p class="text-sm text-gray-400 text-center">Real estate broker or agent</p>
                            </div>
                        </button>

                        <!-- Customer -->
                        <button wire:click="selectVisitorType('customer')" 
                                class="group p-6 border-2 border-mayfair-border rounded-xl hover:border-mayfair-gold hover:bg-mayfair-dark transition-all duration-200">
                            <div class="flex flex-col items-center space-y-3">
                                <div class="w-16 h-16 bg-mayfair-dark group-hover:bg-mayfair-gold rounded-full flex items-center justify-center transition-colors border border-mayfair-border">
                                    <svg class="w-8 h-8 text-mayfair-gold group-hover:text-mayfair-dark transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-white">Customer</h4>
                                <p class="text-sm text-gray-400 text-center">Interested in our projects</p>
                            </div>
                        </button>
                    </div>

                    <!-- Returning Visitor Option -->
                    <div class="mt-8 text-center border-t border-mayfair-border pt-6">
                        <p class="text-gray-400 mb-3">Already registered with us?</p>
                        <button wire:click="selectReturningVisitor" 
                                class="inline-flex items-center px-6 py-3 border-2 border-mayfair-gold text-mayfair-gold rounded-lg hover:bg-mayfair-gold hover:text-mayfair-dark font-semibold transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            I'm a Returning Visitor
                        </button>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Step 2: Basic Information -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep === 2 && !$returningVisitorMode): ?>
                <div class="space-y-6">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-white mb-2">Basic Information</h3>
                        <p class="text-gray-400">Please provide your name and mobile number</p>
                    </div>

                    <form wire:submit="sendOTP" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Full Name *</label>
                            <input type="text" wire:model="name" 
                                   class="w-full px-4 py-3 bg-mayfair-dark border border-mayfair-border rounded-lg focus:ring-2 focus:ring-mayfair-gold focus:border-mayfair-gold text-white placeholder-gray-500"
                                   placeholder="Enter your full name">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Email Address *</label>
                            <input type="email" wire:model="email" 
                                   class="w-full px-4 py-3 bg-mayfair-dark border border-mayfair-border rounded-lg focus:ring-2 focus:ring-mayfair-gold focus:border-mayfair-gold text-white placeholder-gray-500"
                                   placeholder="your.email@example.com"
                                   required>
                            <p class="mt-1 text-xs text-gray-500">OTP will be sent to this email address</p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Mobile Number *</label>
                            <div class="flex gap-2">
                                <select wire:model.live="countryCode" 
                                        class="w-36 px-3 py-3 bg-mayfair-dark border border-mayfair-border rounded-lg focus:ring-2 focus:ring-mayfair-gold focus:border-mayfair-gold text-white text-sm">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($country['dial_code']); ?>">
                                            <?php echo e($country['flag']); ?> <?php echo e($country['dial_code']); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                                <input type="tel" wire:model="mobile" 
                                       class="flex-1 px-4 py-3 bg-mayfair-dark border border-mayfair-border rounded-lg focus:ring-2 focus:ring-mayfair-gold focus:border-mayfair-gold text-white placeholder-gray-500"
                                       placeholder="Enter mobile number" 
                                       maxlength="15">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Selected: <?php echo e($countryCode); ?> (<?php echo e(collect($countries)->firstWhere('dial_code', $countryCode)['name'] ?? 'Unknown'); ?>)
                            </p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['countryCode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="button" wire:click="previousStep" 
                                    class="flex-1 px-6 py-3 border border-mayfair-border text-gray-300 rounded-lg hover:bg-mayfair-dark hover:text-white font-medium transition-colors">
                                Back
                            </button>
                            <button type="submit" 
                                    class="flex-1 px-6 py-3 bg-mayfair-gold text-mayfair-dark rounded-lg hover:bg-yellow-500 font-semibold transition-colors">
                                Send OTP
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Step 2: Returning Visitor Login -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep === 2 && $returningVisitorMode): ?>
                <div class="space-y-6">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-mayfair-dark border border-mayfair-gold rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-mayfair-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">Welcome Back!</h3>
                        <p class="text-gray-400">Enter your registered email or mobile number</p>
                    </div>

                    <form wire:submit="sendReturningVisitorOTP" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Email or Mobile Number *</label>
                            <input type="text" wire:model="identifier" 
                                   class="w-full px-4 py-3 bg-mayfair-dark border border-mayfair-border rounded-lg focus:ring-2 focus:ring-mayfair-gold focus:border-mayfair-gold text-white placeholder-gray-500"
                                   placeholder="your@email.com or 9876543210">
                            <p class="mt-1 text-xs text-gray-500">Enter the email or mobile number you used during registration</p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['identifier'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="button" wire:click="previousStep" 
                                    class="flex-1 px-6 py-3 border border-mayfair-border text-gray-300 rounded-lg hover:bg-mayfair-dark hover:text-white font-medium transition-colors">
                                Back
                            </button>
                            <button type="submit" 
                                    class="flex-1 px-6 py-3 bg-mayfair-gold text-mayfair-dark rounded-lg hover:bg-yellow-500 font-semibold transition-colors">
                                Send OTP
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Step 3: OTP Verification -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep === 3): ?>
                <div class="space-y-6">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-mayfair-dark border border-mayfair-gold rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-mayfair-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">Verify OTP</h3>
                        <p class="text-gray-400">
                            We've sent a 4-digit code to your WhatsApp
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$returningVisitorMode): ?>
                                <br><span class="font-semibold text-mayfair-gold"><?php echo e($countryCode); ?> <?php echo e($mobile); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>

                    <form wire:submit="verifyOTP" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2 text-center">Enter OTP *</label>
                            <input type="text" wire:model="otp" 
                                   class="w-full text-center text-2xl tracking-widest px-4 py-4 bg-mayfair-dark border border-mayfair-border rounded-lg focus:ring-2 focus:ring-mayfair-gold focus:border-mayfair-gold text-white placeholder-gray-500"
                                   placeholder="0000" maxlength="4" inputmode="numeric">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-400 text-center"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="text-center">
                            <button type="button" wire:click="resendOTP" 
                                    class="text-sm text-mayfair-gold hover:text-yellow-400 font-medium">
                                Didn't receive code? Resend OTP
                            </button>
                        </div>

                        <button type="submit" 
                                class="w-full px-6 py-3 bg-mayfair-gold text-mayfair-dark rounded-lg hover:bg-yellow-500 font-semibold transition-colors">
                            Verify & Continue
                        </button>
                    </form>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Step 4: Additional Details -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep === 4): ?>
                <div class="space-y-6">
                    
                    <div class="text-center mb-6">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasActiveCheckIn): ?>
                            <!-- Blocked Check-in Message -->
                            <div class="mb-6 bg-red-900/30 border-2 border-red-700 rounded-xl p-6 text-center">
                                <div class="w-16 h-16 bg-red-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-red-300 mb-2">Active Visit Detected</h3>
                                <p class="text-red-200 mb-4">
                                    You are currently checked in. Please check out before starting a new visit.
                                </p>
                                <div class="bg-mayfair-dark rounded-lg p-4 mb-4 text-left max-w-md mx-auto">
                                    <p class="text-sm text-gray-400">
                                        <strong class="text-mayfair-gold">Note:</strong> To prevent duplicate registrations, 
                                        you must complete your current visit by checking out before registering for a new visit.
                                    </p>
                                </div>
                                <a href="<?php echo e(route('visitor.register')); ?>" 
                                   class="inline-block px-6 py-3 bg-mayfair-gold text-mayfair-dark rounded-lg hover:bg-yellow-500 font-semibold transition-colors mt-4">
                                    Start Over
                                </a>
                            </div>
                        <?php elseif($isReturningVisitor): ?>
                            <div class="mb-6 bg-green-900/30 border border-green-700 text-green-300 px-4 py-3 rounded-lg">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <strong>Welcome back!</strong> Your previous details have been pre-filled. Please review and update as needed.
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <h3 class="text-2xl font-bold text-white mb-2">Additional Details</h3>
                        <p class="text-gray-400">Please complete your registration</p>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$hasActiveCheckIn): ?>
                    <form wire:submit="submitDetails" class="space-y-4">
                        <!-- Common Email Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Email Address *</label>
                            <input type="email" wire:model="email" 
                                   class="w-full px-4 py-3 bg-mayfair-dark border border-mayfair-border rounded-lg focus:ring-2 focus:ring-mayfair-gold focus:border-mayfair-gold text-white placeholder-gray-500"
                                   placeholder="your@email.com"
                                   required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- Guest Specific Fields -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitorType === 'guest'): ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Visitor Type *</label>
                                <select wire:model.live="guestType" 
                                        class="w-full px-4 py-3 bg-mayfair-dark border border-mayfair-border rounded-lg focus:ring-2 focus:ring-mayfair-gold focus:border-mayfair-gold text-white">
                                    <option value="">Select visitor type</option>
                                    <option value="vendor">Vendor</option>
                                    <option value="contractor">Contractor</option>
                                    <option value="interview">Interview</option>
                                    <option value="other">Other</option>
                                </select>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['guestType'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($guestType === 'other'): ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Purpose of Visit *</label>
                                    <textarea wire:model="purposeOfVisit" 
                                              rows="3"
                                              class="w-full px-4 py-3 bg-mayfair-dark border border-mayfair-border rounded-lg focus:ring-2 focus:ring-mayfair-gold focus:border-mayfair-gold text-white placeholder-gray-500"
                                              placeholder="Please describe the purpose of your visit"></textarea>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['purposeOfVisit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($guestType, ['vendor', 'contractor'])): ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Company Name *</label>
                                    <input type="text" wire:model="companyName" 
                                           class="w-full px-4 py-3 bg-mayfair-dark border border-mayfair-border rounded-lg focus:ring-2 focus:ring-mayfair-gold focus:border-mayfair-gold text-white placeholder-gray-500"
                                           placeholder="Enter company name">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['companyName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div x-data="employeeSearch()">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Search Employee *</label>
                                <div class="relative">
                                    <input type="text" 
                                           x-model="searchQuery"
                                           @input.debounce.300ms="searchEmployees"
                                           @focus="showResults = true"
                                           class="w-full px-4 py-3 bg-mayfair-dark border border-mayfair-border rounded-lg focus:ring-2 focus:ring-mayfair-gold focus:border-mayfair-gold text-white placeholder-gray-500"
                                           placeholder="Type employee name..."
                                           autocomplete="off">
                                    
                                    <!-- Search Results Dropdown -->
                                    <div x-show="showResults && results.length > 0" 
                                         x-transition
                                         @click.away="showResults = false"
                                         class="absolute z-10 w-full mt-1 bg-mayfair-dark border border-mayfair-border rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                        <template x-for="employee in results" :key="employee.id">
                                            <div @click="selectEmployee(employee)"
                                                 class="px-4 py-3 hover:bg-mayfair-gray cursor-pointer border-b border-mayfair-border last:border-0">
                                                <div class="text-white font-medium" x-text="employee.name"></div>
                                                <div class="text-sm text-gray-400" x-text="employee.department + ' - ' + employee.designation"></div>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <!-- Selected Employee Display -->
                                    <input type="hidden" wire:model="whomToMeet" x-ref="whomToMeet">
                                    <div x-show="selectedEmployee" class="mt-2 p-3 bg-mayfair-gray border border-mayfair-gold rounded-lg">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <div class="text-white font-medium" x-text="selectedEmployee?.name"></div>
                                                <div class="text-sm text-gray-400" x-text="selectedEmployee?.department"></div>
                                            </div>
                                            <button type="button" @click="clearSelection" class="text-red-400 hover:text-red-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['whomToMeet'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Number of Accompanying Persons</label>
                                <input type="number" wire:model="accompanyingCount" min="0" 
                                       class="w-full px-4 py-3 bg-mayfair-dark border border-mayfair-border rounded-lg focus:ring-2 focus:ring-mayfair-gold focus:border-mayfair-gold text-white placeholder-gray-500"
                                       placeholder="Enter number of accompanying persons">
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Broker Specific Fields -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitorType === 'broker'): ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Company Name *</label>
                                <input type="text" wire:model="brokerCompany" 
                                       class="w-full px-4 py-3 bg-mayfair-dark border border-mayfair-border rounded-lg focus:ring-2 focus:ring-mayfair-gold focus:border-mayfair-gold text-white placeholder-gray-500"
                                       placeholder="Enter your company name">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['brokerCompany'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Department to Meet *</label>
                                <select wire:model.live="meetDepartment" 
                                        class="w-full px-4 py-3 bg-mayfair-dark border border-mayfair-border rounded-lg focus:ring-2 focus:ring-mayfair-gold focus:border-mayfair-gold text-white">
                                    <option value="">Select department</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Management">Management</option>
                                    <option value="Accounts">Accounts</option>
                                </select>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['meetDepartment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($meetDepartment && $filteredEmployees->count() > 0): ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Select Employee (Optional)</label>
                                    <select wire:model="whomToMeet" 
                                            class="w-full px-4 py-3 bg-mayfair-dark border border-mayfair-border rounded-lg focus:ring-2 focus:ring-mayfair-gold focus:border-mayfair-gold text-white">
                                        <option value="">Any available person</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $filteredEmployees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($employee->id); ?>"><?php echo e($employee->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Customer Specific Fields -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitorType === 'customer'): ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Interested Project *</label>
                                <select wire:model="interestedProject" 
                                        class="w-full px-4 py-3 bg-mayfair-dark border border-mayfair-border rounded-lg focus:ring-2 focus:ring-mayfair-gold focus:border-mayfair-gold text-white">
                                    <option value="">Select a project</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($project->id); ?>">
                                            <?php echo e($project->name); ?> - <?php echo e($project->location); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['interestedProject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="pt-4">
                            <button type="submit" 
                                    class="w-full px-6 py-3 bg-mayfair-gold text-mayfair-dark rounded-lg hover:bg-yellow-500 font-semibold transition-all duration-200 shadow-lg">
                                Complete Registration
                            </button>
                        </div>
                    </form>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>

<script>
    function employeeSearch() {
        return {
            searchQuery: '',
            results: [],
            showResults: false,
            selectedEmployee: null,
            
            async searchEmployees() {
                if (this.searchQuery.length < 2) {
                    this.results = [];
                    return;
                }
                
                try {
                    const response = await fetch(`/api/employees/search?query=${encodeURIComponent(this.searchQuery)}`);
                    this.results = await response.json();
                    this.showResults = true;
                } catch (error) {
                    console.error('Error searching employees:', error);
                }
            },
            
            selectEmployee(employee) {
                this.selectedEmployee = employee;
                this.searchQuery = employee.name;
                this.showResults = false;
                this.$refs.whomToMeet.value = employee.id;
                this.$refs.whomToMeet.dispatchEvent(new Event('input'));
            },
            
            clearSelection() {
                this.selectedEmployee = null;
                this.searchQuery = '';
                this.$refs.whomToMeet.value = '';
                this.$refs.whomToMeet.dispatchEvent(new Event('input'));
            }
        }
    }
</script>
<?php /**PATH E:\GitProjects\staging\mayfair_VMS\resources\views/livewire/visitor-registration.blade.php ENDPATH**/ ?>