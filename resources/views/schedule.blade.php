
<?php $dayc = count($schedule); ?>
@for ($i = 0; $i < $dayc; $i++)
<div class="sc-section">
    <div class="sc-day"><div>Day {{$i + 1}}</div></div>
    <div class="sc-evt">
        <?php $day = $schedule[$i]; ?>
        @foreach ($day as $block)
        <div class="sc-block">
            <div class="sc-block-time">
                @if (isset($block['ts']))
                <span>{{ $block['ts'] }}</span>
                @endif
                @if (isset($block['te']))
                <div class="dot-sep"></div>
                <span>{{ $block['te'] }}</span>
                @endif
            </div>
            <div class="sc-block-evt">
                <?php $evts = $block['list']; ?>
                @foreach ($evts as $evt)
                <span>{{ $evt }}</span>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
@endfor
