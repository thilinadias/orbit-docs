<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center justify-between">
            <span>Assets</span>
            <div class="flex space-x-2">
                <a href="<?php echo e(route('assets.export', array_merge(['organization' => $organization->slug], request()->query()))); ?>" class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg dark:text-gray-200 dark:bg-gray-800 dark:border-gray-600 active:bg-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray">
                    Export CSV
                </a>
                <a href="<?php echo e(route('assets.import', $organization->slug)); ?>" class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg dark:text-gray-200 dark:bg-gray-800 dark:border-gray-600 active:bg-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray">
                    Import
                </a>
                <a href="<?php echo e(route('assets.create', $organization->slug)); ?>" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Create Asset
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Manufacturer</th>
                        <th class="px-4 py-3">Model</th>
                        <th class="px-4 py-3">IP Address</th>
                        <th class="px-4 py-3">Site</th>
                        <th class="px-4 py-3">Serial</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <?php $__empty_1 = true; $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                                <a href="<?php echo e(route('assets.show', [$currentOrganization->slug, $asset->id])); ?>" class="font-semibold hover:text-purple-600 dark:hover:text-purple-400">
                                    <?php echo e($asset->name); ?>

                                </a>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <?php echo e($asset->type->name); ?>

                        </td>
                        <td class="px-4 py-3 text-sm">
                            <?php echo e($asset->manufacturer ?? '-'); ?>

                        </td>
                        <td class="px-4 py-3 text-sm">
                            <?php echo e($asset->model ?? '-'); ?>

                        </td>
                        <td class="px-4 py-3 text-sm font-mono">
                            <?php echo e($asset->ip_address ?? '-'); ?>

                        </td>
                        <td class="px-4 py-3 text-sm">
                            <?php echo e($asset->site->name ?? '-'); ?>

                        </td>
                        <td class="px-4 py-3 text-sm">
                            <?php echo e($asset->serial_number ?? '-'); ?>

                        </td>
                        <td class="px-4 py-3 text-xs">
                            <span class="px-2 py-1 font-semibold leading-tight rounded-full 
                                <?php echo e($asset->status === 'active' ? 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100' : ''); ?>

                                <?php echo e($asset->status === 'broken' ? 'text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100' : ''); ?>

                                <?php echo e($asset->status === 'archived' ? 'text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-100' : ''); ?>

                                <?php echo e($asset->status === 'suspended' ? 'text-orange-700 bg-orange-100 dark:bg-orange-700 dark:text-orange-100' : ''); ?>

                            ">
                                <?php echo e(ucfirst($asset->status)); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center space-x-4 text-sm">
                                <button class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                </button>
                                <button class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-center text-gray-500">No assets found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
            <?php echo e($assets->links()); ?>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\orbitdocs\resources\views/assets/index.blade.php ENDPATH**/ ?>