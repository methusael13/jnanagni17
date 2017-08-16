
<?php $dayc = count($schedule); ?>
<?php for($i = 0; $i < $dayc; $i++): ?>
<div class="sc-section">
    <div class="sc-day"><div>Day <?php echo e($i + 1); ?></div></div>
    <div class="sc-evt">
        <?php $day = $schedule[$i]; ?>
        <?php $__currentLoopData = $day; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $block): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
        <div class="sc-block">
            <div class="sc-block-time">
                <?php if(isset($block['ts'])): ?>
                <span><?php echo e($block['ts']); ?></span>
                <?php endif; ?>
                <?php if(isset($block['te'])): ?>
                <div class="dot-sep"></div>
                <span><?php echo e($block['te']); ?></span>
                <?php endif; ?>
            </div>
            <div class="sc-block-evt">
                <?php $evts = $block['list']; ?>
                <?php $__currentLoopData = $evts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evt): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                <span><?php echo e($evt); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
    </div>
</div>
<?php endfor; ?>
