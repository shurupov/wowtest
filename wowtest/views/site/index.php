<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Загрузить</h1>

        <p class="lead">Нажмите на кнопку ниже или перетащите в неё файл</p>

        <p>
            <span class="btn btn-lg btn-success fileinput-button">
                <i class="glyphicon glyphicon-plus"></i>
                <span>Выбрать файл...</span>
                            <!-- The file input field used as target for the file upload widget -->
                <input id="fileupload" type="file" name="files[]" multiple>
            </span>
        </p>

    </div>

    <div class="body-content">

        <div class="row processors">

        </div>

    </div>
</div>
