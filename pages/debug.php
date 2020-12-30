<?php ?>
<div class="debug container">
    <div>
        <h4>Types</h4>
        <div class="data">
            <? if (!empty($types)) {
                print_r($types);
            } ?>
        </div>
    </div>
    <div>
        <h4>Uses</h4>
        <div class="data">
            <? if (!empty($uses)) {
                print_r($uses);
            } ?>
        </div>
    </div>
</div>
