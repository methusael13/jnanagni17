
<div class="event-detail-panel animatable-o remove-o hidden" id=<?php echo e('edp-' . $cat->getID()); ?>>
    <div class="edp-header">
        <div class="edp-nav-but">
            <div id="nb-back"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
        </div>
        <?php
            $evtList = $cat->getEventList();
            $evtCnt = count($evtList);

            $classStr = NULL;
        ?>

        <?php for($i = 0; $i < $evtCnt; $i++): ?>
        <?php $idstr = 'et-' . $cat->getID() . '-' . $i; ?>
        <?php if($i == 0): ?>
        <div class="evt-title animatable-o" id=<?php echo e($idstr); ?>>
        <?php else: ?>
        <div class="evt-title animatable-o remove-o hidden" id=<?php echo e($idstr); ?>>
        <?php endif; ?>
            <?php echo e($evtList[$i]->getTitle()); ?>

        </div>
        <?php endfor; ?>

        <div class="cat-title"><?php echo e($cat->getTitle()); ?></div>
    </div>
    <div class="edp-body">
        <div class="evt-grid">
            <?php $cols = 2; $rows = intval($evtCnt / $cols); ?>
            <?php for($i = 0; $i < $rows; $i++): ?>
            <div class="row">
                <?php for($j = 0; $j < $cols; $j++): ?>
                <?php
                    $idx = $cols * $i + $j;
                    $idstr = 'ec-' . $cat->getID() . '-' . $idx;
                ?>
                <?php echo $__env->make('evt-card', ['idstr' => $idstr, 'title' => $evtList[$idx]->getTitle()], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php endfor; ?>
            </div>
            <?php endfor; ?>

            <?php $rem = intval($evtCnt % $cols); $off = $evtCnt - $rem; ?>
            <?php if($rem > 0): ?>
            <div class="row">
                <?php for($i = 0; $i < $rem; $i++): ?>
                <?php $idstr = 'ec-' . $cat->getID() . '-' . ($off+$i); ?>
                <?php echo $__env->make('evt-card', [ 'idstr' => $idstr, 'title' => $evtList[$off+$i]->getTitle() ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="evt-desc-panel">
            <div class="evt-story" id=<?php echo e('edp-es-' . $cat->getID()); ?>>
            <?php
                $file_path = $es_path . $cat->getID() . '-0/' . 'story.cms';
                
                if (file_exists($file_path))
                    echo nl2br(file_get_contents($file_path));
                else
                    echo nl2br(file_get_contents($es_path . 'fallback.cms'));
            ?>
            </div>
            <div class="evt-controls">
                <div class="evt-dialog-button" id=<?php echo e("edb-" . $cat->getID()); ?>>Event Details</div>
            </div>
        </div>
    </div>
</div>
