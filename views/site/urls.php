<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Urls';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$js = <<<JS
    $('form').on('beforeSubmit', function(){
        var data = $(this).serialize();
        $('#conteiner').html('');
        $('#conteiner').attr('href','');
        $.ajax({
            url: '/site/prepared',
            type: 'POST',
            data: data,
            success: function(res){
                console.log(res);
                $('#conteiner').html(res);
                $('#conteiner').attr('href',res);
            },
            error: function(){
                alert('Error!');
                $("#conteiner").html("ERROR");
            }
        });
        return false;
    });
JS;
 
$this->registerJs($js);
?>

<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>
        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin(['id' => 'link-form']); ?>
                    <?= $form->field($model, 'link')->textInput(['autofocus' => true])->label('Ссылка') ?>
                    <div class="form-group">
                        <?= Html::submitButton('Получить короткую ссылку', ['class' => 'btn btn-primary', 'name' => 'link-form']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
                <a href="#" id="conteiner">Введите в поле ссылка url адрес</a>
            </div>
        </div>
</div>


