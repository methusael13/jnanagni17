<?php $__env->startSection('title'); ?>
Event Registrations
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<link rel="stylesheet" type="text/css" href="css/reg/details.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="js/reg/details.js"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="content">
    <div class="title title-small">Jnanagni Registrations</div>
    <div class="controls">
        <select id="cat-name">
            <?php $__currentLoopData = $evtcats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
            <option><?php echo e($cat->getTitle()); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
        </select>
        <select id="evt-name"></select>
        <div>
            <input type="checkbox" name="sort" value="Sort" id="_sort">
            <label for="sort">Sort By Name</label>
        </div>
        <div class="btn animatable-all" id="fetch-btn">Fetch</div>
    </div>
    <div class="evt-title" id="evt-title-id"></div>
    <div class="data-section" id="data-section-id"></div>
    <div class="controls" id="exp-controls">
    <div class="btn animatable-all hidden" id="export-btn">Export to EXCEL</div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('reg.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>