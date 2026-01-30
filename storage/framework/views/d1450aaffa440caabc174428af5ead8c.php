

<?php $__env->startSection('title', 'Visitors List'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Visitors Management</h1>
    <p class="text-gray-600 mt-1">View and manage all visitor registrations</p>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6">
    <form method="GET" action="<?php echo e(route('admin.visitors.index')); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                   placeholder="Name, Mobile, Email">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Visitor Type</label>
            <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                <option value="">All Types</option>
                <option value="guest" <?php echo e(request('type') === 'guest' ? 'selected' : ''); ?>>Guest</option>
                <option value="broker" <?php echo e(request('type') === 'broker' ? 'selected' : ''); ?>>Broker</option>
                <option value="customer" <?php echo e(request('type') === 'customer' ? 'selected' : ''); ?>>Customer</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                <option value="">All Status</option>
                <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                <option value="verified" <?php echo e(request('status') === 'verified' ? 'selected' : ''); ?>>Verified</option>
                <option value="checked_in" <?php echo e(request('status') === 'checked_in' ? 'selected' : ''); ?>>Checked In</option>
                <option value="checked_out" <?php echo e(request('status') === 'checked_out' ? 'selected' : ''); ?>>Checked Out</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
            <input type="date" name="date" value="<?php echo e(request('date')); ?>" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg">
        </div>
        <div class="md:col-span-4 flex gap-2">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Apply Filters
            </button>
            <a href="<?php echo e(route('admin.visitors.index')); ?>" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                Clear
            </a>
        </div>
    </form>
</div>

<!-- Visitors Table -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mobile</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Synced</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $visitors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visitor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #<?php echo e(str_pad($visitor->id, 5, '0', STR_PAD_LEFT)); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($visitor->name); ?></div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitor->email): ?>
                                <div class="text-sm text-gray-500"><?php echo e($visitor->email); ?></div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                <?php echo e($visitor->visitor_type === 'guest' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                <?php echo e($visitor->visitor_type === 'broker' ? 'bg-green-100 text-green-800' : ''); ?>

                                <?php echo e($visitor->visitor_type === 'customer' ? 'bg-purple-100 text-purple-800' : ''); ?>">
                                <?php echo e(ucfirst($visitor->visitor_type)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo e($visitor->mobile); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                <?php echo e($visitor->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                <?php echo e($visitor->status === 'verified' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                <?php echo e($visitor->status === 'checked_in' ? 'bg-green-100 text-green-800' : ''); ?>

                                <?php echo e($visitor->status === 'checked_out' ? 'bg-gray-100 text-gray-800' : ''); ?>">
                                <?php echo e(ucfirst(str_replace('_', ' ', $visitor->status))); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($visitor->created_at->format('M d, Y H:i')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitor->synced_to_sheets): ?>
                                <span class="text-green-600">✓ Synced</span>
                            <?php else: ?>
                                <span class="text-orange-600">⏳ Pending</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="<?php echo e(route('admin.visitors.show', $visitor)); ?>" 
                               class="text-indigo-600 hover:text-indigo-900">View</a>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitor->status === 'checked_in'): ?>
                                <form action="<?php echo e(route('admin.visitors.checkout', $visitor)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-900">Checkout</button>
                                </form>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            No visitors found.
                        </td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitors->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-200">
            <?php echo e($visitors->links()); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\GitProjects\staging\mayfair_VMS\resources\views/admin/visitors/index.blade.php ENDPATH**/ ?>