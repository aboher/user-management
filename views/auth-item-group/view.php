<?php

use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\rbacDB\AuthItemGroup $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => UserManagementModule::t('back', 'Permission groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-group-view">

	<h2 class="lte-hide-title"><?= $this->title ?></h2>

	<div class="card">
		<div class="card-body">

			<p>
				<?= Html::a(UserManagementModule::t('back', 'Edit'), ['update', 'id' => $model->code], ['class' => 'btn btn-primary']) ?>
				<?= Html::a(UserManagementModule::t('back', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
				<?= Html::a(Yii::t('yii', 'Delete'), ['delete', 'id' => $model->code], [
					'class' => 'btn btn-danger',
					'data' => [
						'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
						'method' => 'post',
					],
				]) ?>
			</p>

			<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
					'name',
					'code',
					'created_at:datetime',
					'updated_at:datetime',
				],
			]) ?>

		</div>
	</div>
</div>
