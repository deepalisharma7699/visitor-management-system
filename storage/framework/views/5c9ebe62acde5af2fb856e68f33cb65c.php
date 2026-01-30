<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => 'Registration Successful']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Registration Successful']); ?>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden text-center p-8 sm:p-12">
            <!-- Success Icon -->
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-3">Registration Successful!</h1>
            <p class="text-lg text-gray-600 mb-8">
                Welcome to Mayfair, <span class="font-semibold text-indigo-600"><?php echo e($visitor->name); ?></span>
            </p>

            <!-- Visitor Details Card -->
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-6 mb-8 text-left">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Details</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Visitor Type:</span>
                        <span class="font-medium text-gray-900"><?php echo e(ucfirst($visitor->visitor_type)); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Mobile:</span>
                        <span class="font-medium text-gray-900"><?php echo e($visitor->mobile); ?></span>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitor->email): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium text-gray-900"><?php echo e($visitor->email); ?></span>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Registration ID:</span>
                        <span class="font-medium text-indigo-600">#<?php echo e(str_pad($visitor->id, 5, '0', STR_PAD_LEFT)); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Check-in Time:</span>
                        <span class="font-medium text-gray-900"><?php echo e($visitor->checked_in_at->format('h:i A')); ?></span>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-800">
                    <strong>Note:</strong> Your details have been recorded and synced to our system. 
                    Please proceed to the reception desk.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <!-- Checkout Button (if checked in) -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitor->status === 'checked_in' && is_null($visitor->checked_out_at)): ?>
                    <form action="<?php echo e(route('visitor.checkout', $visitor)); ?>" method="POST" class="mb-4">
                        <?php echo csrf_field(); ?>
                        <button type="submit" 
                                class="block w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors">
                            ✓ Check Out Now
                        </button>
                    </form>
                <?php elseif($visitor->status === 'checked_out'): ?>
                    <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                        <p class="text-sm text-green-800">
                            <strong>✓ Checked Out</strong> at <?php echo e($visitor->checked_out_at->format('h:i A')); ?>

                        </p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <a href="<?php echo e(route('visitor.register')); ?>" 
                   class="block w-full px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition-colors">
                    Register Another Visitor
                </a>
                <button onclick="window.print()" 
                        class="block w-full px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Print Receipt
                </button>
            </div>

            <!-- Footer Message -->
            <p class="mt-8 text-sm text-gray-500">
                Thank you for visiting Mayfair. Have a great day! 🙏
            </p>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php /**PATH E:\GitProjects\staging\mayfair_VMS\resources\views/visitor/success.blade.php ENDPATH**/ ?>