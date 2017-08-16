
<div class="event-detail-panel animatable-o remove-o hidden" id={{ 'edp-' . $cat->getID() }}>
    <div class="edp-header">
        <div class="edp-nav-but">
            <div id="nb-back"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
        </div>
        <?php
            $evtList = $cat->getEventList();
            $evtCnt = count($evtList);

            $classStr = NULL;
        ?>

        @for ($i = 0; $i < $evtCnt; $i++)
        <?php $idstr = 'et-' . $cat->getID() . '-' . $i; ?>
        @if ($i == 0)
        <div class="evt-title animatable-o" id={{ $idstr }}>
        @else
        <div class="evt-title animatable-o remove-o hidden" id={{ $idstr }}>
        @endif
            {{ $evtList[$i]->getTitle() }}
        </div>
        @endfor

        <div class="cat-title">{{ $cat->getTitle() }}</div>
    </div>
    <div class="edp-body">
        <div class="evt-grid">
            <?php $cols = 2; $rows = intval($evtCnt / $cols); ?>
            @for ($i = 0; $i < $rows; $i++)
            <div class="row">
                @for ($j = 0; $j < $cols; $j++)
                <?php
                    $idx = $cols * $i + $j;
                    $idstr = 'ec-' . $cat->getID() . '-' . $idx;
                ?>
                @include('evt-card', ['idstr' => $idstr, 'title' => $evtList[$idx]->getTitle()])
                @endfor
            </div>
            @endfor

            <?php $rem = intval($evtCnt % $cols); $off = $evtCnt - $rem; ?>
            @if ($rem > 0)
            <div class="row">
                @for ($i = 0; $i < $rem; $i++)
                <?php $idstr = 'ec-' . $cat->getID() . '-' . ($off+$i); ?>
                @include('evt-card', [ 'idstr' => $idstr, 'title' => $evtList[$off+$i]->getTitle() ])
                @endfor
            </div>
            @endif
        </div>
        <div class="evt-desc-panel">
            <div class="evt-story" id={{ 'edp-es-' . $cat->getID() }}>
            <?php
                $file_path = $es_path . $cat->getID() . '-0/' . 'story.cms';
                
                if (file_exists($file_path))
                    echo nl2br(file_get_contents($file_path));
                else
                    echo nl2br(file_get_contents($es_path . 'fallback.cms'));
            ?>
            </div>
            <div class="evt-controls">
                <div class="evt-dialog-button" id={{ "edb-" . $cat->getID() }}>Event Details</div>
            </div>
        </div>
    </div>
</div>
