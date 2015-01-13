<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 * @var \yii\web\View $this
 * @var array $stat
 */
$this->title = 'SMS statistics';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \yii\widgets\DetailView::widget([
    'model' => $stat,
    'attributes' => [
        'new',
        'sent',
        'error',
    ]
]) ?>