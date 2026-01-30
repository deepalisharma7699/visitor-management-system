

<?php $__env->startSection('title', 'Visitor Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <a href="<?php echo e(route('admin.visitors.index')); ?>" class="text-indigo-600 hover:text-indigo-800 mb-4 inline-block">
        ← Back to Visitors List
    </a>
    <h1 class="text-3xl font-bold text-gray-900">Visitor Details</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Information -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Info Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-500">Registration ID</label>
                    <p class="text-lg font-semibold text-indigo-600">#<?php echo e(str_pad($visitor->id, 5, '0', STR_PAD_LEFT)); ?></p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Visitor Type</label>
                    <p class="text-lg font-semibold"><?php echo e(ucfirst($visitor->visitor_type)); ?></p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Full Name</label>
                    <p class="text-lg"><?php echo e($visitor->name); ?></p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Mobile Number</label>
                    <p class="text-lg">+91 <?php echo e($visitor->mobile); ?></p>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitor->email): ?>
                    <div class="col-span-2">
                        <label class="text-sm text-gray-500">Email Address</label>
                        <p class="text-lg"><?php echo e($visitor->email); ?></p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <!-- Type Specific Details -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Additional Details</h2>
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitor->visitor_type === 'guest'): ?>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500">Guest Type</label>
                        <p class="text-lg"><?php echo e(ucfirst(str_replace('_', ' ', $visitor->guest_type ?? 'N/A'))); ?></p>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitor->company_name): ?>
                        <div>
                            <label class="text-sm text-gray-500">Company Name</label>
                            <p class="text-lg"><?php echo e($visitor->company_name); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitor->employee): ?>
                        <div>
                            <label class="text-sm text-gray-500">Meeting With</label>
                            <p class="text-lg"><?php echo e($visitor->employee->name); ?> (<?php echo e($visitor->employee->department); ?>)</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitor->accompanying_count > 0): ?>
                        <div>
                            <label class="text-sm text-gray-500">Accompanying Persons</label>
                            <p class="text-lg"><?php echo e($visitor->accompanying_count); ?> person(s)</p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitor->accompanying_persons): ?>
                                <div class="mt-2 space-y-2">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $visitor->accompanying_persons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $person): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <p class="font-medium"><?php echo e($person['name']); ?></p>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($person['mobile'])): ?>
                                                <p class="text-sm text-gray-600">📱 <?php echo e($person['mobile']); ?></p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($person['email'])): ?>
                                                <p class="text-sm text-gray-600">✉️ <?php echo e($person['email']); ?></p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php elseif($visitor->visitor_type === 'broker'): ?>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500">Company Name</label>
                        <p class="text-lg"><?php echo e($visitor->broker_company); ?></p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Department to Meet</label>
                        <p class="text-lg"><?php echo e($visitor->meet_department); ?></p>
                    </div>
                </div>
            <?php elseif($visitor->visitor_type === 'customer'): ?>
                <div class="space-y-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitor->project): ?>
                        <div>
                            <label class="text-sm text-gray-500">Interested Project</label>
                            <p class="text-lg font-medium"><?php echo e($visitor->project->name); ?></p>
                            <p class="text-sm text-gray-600"><?php echo e($visitor->project->location); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Status</h2>
            <div class="space-y-4">
                <div>
                    <label class="text-sm text-gray-500">Current Status</label>
                    <p class="text-lg">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full
                            <?php echo e($visitor->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                            <?php echo e($visitor->status === 'verified' ? 'bg-blue-100 text-blue-800' : ''); ?>

                            <?php echo e($visitor->status === 'checked_in' ? 'bg-green-100 text-green-800' : ''); ?>

                            <?php echo e($visitor->status === 'checked_out' ? 'bg-gray-100 text-gray-800' : ''); ?>">
                            <?php echo e(ucfirst(str_replace('_', ' ', $visitor->status))); ?>

                        </span>
                    </p>
                </div>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitor->verified_at): ?>
                    <div>
                        <label class="text-sm text-gray-500">Verified At</label>
                        <p class="text-base"><?php echo e($visitor->verified_at->format('M d, Y h:i A')); ?></p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitor->checked_in_at): ?>
                    <div>
                        <label class="text-sm text-gray-500">Check-in Time</label>
                        <p class="text-base"><?php echo e($visitor->checked_in_at->format('M d, Y h:i A')); ?></p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitor->checked_out_at): ?>
                    <div>
                        <label class="text-sm text-gray-500">Check-out Time</label>
                        <p class="text-base"><?php echo e($visitor->checked_out_at->format('M d, Y h:i A')); ?></p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitor->status === 'checked_in'): ?>
                    <form action="<?php echo e(route('admin.visitors.checkout', $visitor)); ?>" method="POST" class="pt-2">
                        <?php echo csrf_field(); ?>
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                            Check Out Visitor
                        </button>
                    </form>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <!-- Sync Status Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Sync Status</h2>
            <div class="space-y-3">
                <div>
                    <label class="text-sm text-gray-500">Google Sheets</label>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitor->synced_to_sheets): ?>
                        <p class="text-green-600 font-medium">✓ Synced</p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitor->synced_at): ?>
                            <p class="text-xs text-gray-500"><?php echo e($visitor->synced_at->diffForHumans()); ?></p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php else: ?>
                        <p class="text-orange-600 font-medium">⏳ Pending</p>
                        <p class="text-xs text-gray-500">Will sync automatically</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\GitProjects\staging\mayfair_VMS\resources\views/admin/visitors/show.blade.php ENDPATH**/ ?>