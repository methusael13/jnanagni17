<?php $__env->startSection('title'); ?>
Event Registrations
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="content">
    <div class="title">Jnanagni</div>
    <div class="form-section">
        <?php echo e(Form::open(['url' => '/reg-details', 'method' => 'POST', 'class' => 'form', 'id' => 'form-org'])); ?>

            <div class="inp-row">
                <input type="password" name="org-pass" placeholder="Enter Organiser Pass">
                <input type="submit" name="org-pass-btn" class="btn animatable-all" value="Submit">
            </div>
        <?php echo e(Form::close()); ?>

    </div>
    <?php if($errors->any()): ?>
    <div class="err"><?php echo e($errors->first()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('reg.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>