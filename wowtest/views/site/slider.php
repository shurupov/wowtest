<?php

$this->title = $document->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= $document->name ?></h1>

    <a href="/download/<?= $document->id ?>">Скачать</a>

    <nav aria-label="Page navigation">
        <ul class="pagination">

            <li<?php if ($page < 1) { ?> class="disabled"<?php } ?>>
                <a <?php if ($page > 0) { ?>href="/result/<?= $document->id ?>/<?= $page - 1 ?>"<?php } ?> aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <?php for ($p = 0; $p < $document->pages_count; $p++) { ?>
                <li<?php if ($p == $page) { ?> class="active"<?php } ?>><a href="/result/<?= $document->id ?>/<?= $p ?>"><?= ($p + 1) ?></a></li>
            <?php } ?>

            <li<?php if ($page == $document->pages_count - 1) { ?> class="disabled"<?php } ?>>
                <a <?php if ($page < $document->pages_count - 1) { ?>href="/result/<?= $document->id ?>/<?= $page + 1 ?>"<?php } ?> aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="panel panel-default">
        <div class="panel-body">
            <img src="/pdf-images/<?= $document->id ?>_<?= $page ?>.png" class="img-responsive img-thumbnail center-block">
        </div>
    </div>

    <nav aria-label="Page navigation">
        <ul class="pagination">

            <li<?php if ($page < 1) { ?> class="disabled"<?php } ?>>
                <a <?php if ($page > 0) { ?>href="/result/<?= $document->id ?>/<?= $page - 1 ?>"<?php } ?> aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <?php for ($p = 0; $p < $document->pages_count; $p++) { ?>
                <li<?php if ($p == $page) { ?> class="active"<?php } ?>><a href="/result/<?= $document->id ?>/<?= $p ?>"><?= ($p + 1) ?></a></li>
            <?php } ?>

            <li<?php if ($page == $document->pages_count - 1) { ?> class="disabled"<?php } ?>>
                <a <?php if ($page < $document->pages_count - 1) { ?>href="/result/<?= $document->id ?>/<?= $page + 1 ?>"<?php } ?> aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>

</div>
