<?php

use webvimark\modules\UserManagement\UserManagementModule;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\forms\ChangeOwnPasswordForm $model
 */

$this->title = UserManagementModule::t('back', 'Change own password');
// $this->params['breadcrumbs'][] = $this->title; // comment to hide breadcrumbs
?>
<div class="change-own-password">

	<h2 class="lte-hide-title"><?= $this->title ?></h2>

	<div class="card">
		<div class="card-body">

			<?php if (Yii::$app->session->hasFlash('success')): ?>
				<div class="alert alert-success text-center">
					<?= Yii::$app->session->getFlash('success') ?>
				</div>
			<?php endif; ?>

			<div class="user-form">

				<?php $form = ActiveForm::begin([
					'id' => 'user',
					'layout' => 'horizontal',
					'validateOnBlur' => false,
				]); ?>

				<?php if ($model->scenario != 'restoreViaEmail'): ?>
					<?= $form->field($model, 'current_password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>

				<?php endif; ?>

				<?= $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>

				<?= $form->field($model, 'repeat_password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>


				<div class="text-end mt-3">
					<?= Html::submitButton(
						'<i class="bi bi-check-lg"></i> ' . UserManagementModule::t('back', 'Save'),
						['class' => 'btn btn-primary']
					) ?>
				</div>

				<?php ActiveForm::end(); ?>

			</div>
		</div>
	</div>

</div>