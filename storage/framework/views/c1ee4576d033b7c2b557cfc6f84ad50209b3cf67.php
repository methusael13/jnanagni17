<?php $__env->startSection('title'); ?>
<title>Jnanagni | FET Techfest 2017</title>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<link href="css/welcome.css" rel="stylesheet" type="text/css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="js/welcome.js" async defer></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="logo-container">
    <div class="panel">
        <div class="right animatable-fz" id="title-panel">
            <span class="title animatable-ot" id="t-jnanagni">JNANAGNI</span>
            <span class="subtitle animatable-ot" id="t-tfow">The Fire of Wisdom</span>
            <div class="link-area animatable-all">
                <a href="https://jnanagni17.in/storage/jnanagni-app.apk" class="app-link animatable-all" id="t-app" target="_blank">Download App</a>
                <a href="https://jnanagni17.in/storage/schedule.pdf" class="app-link animatable-all" id="t-app" target="_blank">Download Schedule</a>
                <a href="https://jnanagni17.in/storage/brochure.pdf" class="app-link animatable-all" id="t-app" target="_blank">Download Brochure</a>
            </div>
            <div class="notice animatable-all" id="evt-notice"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>&nbsp&nbspEvent registrations to close on 22<sup>nd</sup> March, 2017 at 23:59:59 IST</div>
            <div class="line">
                <div class="line-btn animatable-all" id="social-btn">Social Initiatives</div>
            </div>
        </div>
        <div class="left animatable-fz" id="orb-panel">
            <div class="orb" id="orb-dtg">
                <canvas class="progress" id="arc" width="416" height="416"></canvas>
                <div class="progress" id="text">
                    <div class="top"><span id="prog"></span></div>
                    <div class="bottom">Days to go</div>
                </div>
            </div>
            <div class="wrapper animatable-ot hidden remove-ot" id="ep-cards">
            <?php $catLen = count($evtcats); $ff = min($catLen, 3); ?>
            <div class="row event-panel front-face" id="ep-0">
            <?php for($i = 0; $i < $ff; $i++): ?>
                <div class="col col-span-3 card" id=<?php echo e($evtcats[$i]->getID()); ?>>
                    <div class="card-grad"></div>
                    <div class="card-text">
                        <span class="ct-title"><?php echo e($evtcats[$i]->getTitle()); ?></span>
                        <span class="ct-subtitle"></span>
                    </div>
                </div>
            <?php endfor; ?>
            </div>
            <div class="row event-panel back-face" id="ep-1">
            <?php if($catLen > $ff): ?>
                <?php for($i = 0; $i < $catLen - $ff; $i++): ?>
                <div class="col col-span-3 card" id=<?php echo e($evtcats[$ff+$i]->getID()); ?>>
                    <div class="card-grad"></div>
                    <div class="card-text">
                        <span class="ct-title"><?php echo e($evtcats[$ff+$i]->getTitle()); ?></span>
                        <span class="ct-subtitle"></span>
                    </div>
                </div>
                <?php endfor; ?>
            <?php endif; ?>
            </div>
            </div>
            <div class="ep-control animatable-ot hidden remove-ot" id="ep-control-id">
                <span class="epc-nav animatable-c epc-nav-disable" id="epc-left"><i class="fa fa-chevron-left" aria-hidden="true"></i></span>
                <span class="epc-nav animatable-c" id="epc-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
            </div>
            <?php for($i = 0; $i < $catLen; $i++): ?>
            <?php echo $__env->make('evt-details', ['cat' => $evtcats[$i], 'es_path' => $evtStoryPath], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php endfor; ?>
            <div class="button-launch"></div>
        </div>
    </div>
</div>
<div class="alt-evt-panel modal animatable-o remove-o hidden">
    <span class="round-btn" id="alt-evt-back"><i class="fa fa-arrow-left" aria-hidden="true"></i></span>
    <div class="alt-evt-cnt animatable-o" id="social-alt">
        <?php
            $scTtls = $social['titles']; $scImgs = $social['images'];
            $scDetails = $social['details']; $scCount = count($scTtls)
        ?>
        <?php for($i = 0; $i < $scCount; $i++): ?>
        <div class="sp-slide">
            <div class="slide-pic animatable-f" style='background-image: url("<?php echo e($scImgs[$i]); ?>");'></div>
            <div class="slide-details">
                <div class="sd-title"><?php echo e($scTtls[$i]); ?></div>
                <div class="sd-line"></div>
                <div class="sd-content">
                    <?php  echo nl2br(file_get_contents($scDetails[$i]));  ?>
                </div>
            </div>
        </div>
        <?php endfor; ?>
    </div>
    <div class="alt-evt-cnt animatable-o remove-o hidden" id="flashback-alt">
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('base', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>