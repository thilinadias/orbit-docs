<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            <?php echo e(__('Update Password')); ?>

        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            <?php echo e(__('Ensure your account is using a long, random password to stay secure.')); ?>

        </p>
    </header>

    <form method="post" action="<?php echo e(route('password.update')); ?>" class="mt-6 space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('put'); ?>

        <div>
            <label for="update_password_current_password" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                <?php echo e(__('Current Password')); ?>

            </label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
            <?php if($errors->updatePassword->get('current_password')): ?>
                <ul class="mt-2 text-sm text-red-600 dark:text-red-400 space-y-1">
                    <?php $__currentLoopData = (array) $errors->updatePassword->get('current_password'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($message); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>
        </div>

        <div>
            <label for="update_password_password" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                <?php echo e(__('New Password')); ?>

            </label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
            <?php if($errors->updatePassword->get('password')): ?>
                 <ul class="mt-2 text-sm text-red-600 dark:text-red-400 space-y-1">
                    <?php $__currentLoopData = (array) $errors->updatePassword->get('password'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($message); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                <?php echo e(__('Confirm Password')); ?>

            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
             <?php if($errors->updatePassword->get('password_confirmation')): ?>
                 <ul class="mt-2 text-sm text-red-600 dark:text-red-400 space-y-1">
                    <?php $__currentLoopData = (array) $errors->updatePassword->get('password_confirmation'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($message); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="flex items-center gap-4">
            <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('Save')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>

            <?php if(session('status') === 'password-updated'): ?>
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                ><?php echo e(__('Saved.')); ?></p>
            <?php endif; ?>
        </div>
    </form>
</section>
<?php /**PATH C:\xampp\htdocs\orbitdocs\resources\views/profile/partials/update-password-form.blade.php ENDPATH**/ ?>