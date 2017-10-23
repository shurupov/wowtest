<?php

$this->title = $document->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= $document->name ?></h1>

    <div class="btn-group" role="group" style="margin-bottom: 20px;">
        <?php for ($p = 0; $p < $document->pages_count; $p++) { ?>
            <a href="/result/<?= $document->id ?>/<?= $p ?>" class="btn <?php if ($p == $page) echo "btn-primary"; else echo "btn-default";?>"><?= ($p + 1) ?></a>
        <?php } ?>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <img src="/pdf-images/<?= $document->id ?>_<?= $page ?>.png" class="img-responsive img-thumbnail center-block">
        </div>
    </div>

    <div class="btn-group" role="group">
        <?php for ($p = 0; $p < $document->pages_count; $p++) { ?>
        <a href="/result/<?= $document->id ?>/<?= $p ?>" class="btn <?php if ($p == $page) echo "btn-primary"; else echo "btn-default";?>"><?= ($p + 1) ?></a>
        <?php } ?>
    </div>

</div>
