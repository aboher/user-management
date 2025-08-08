<?php

use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\User $model
 */

$this->title = UserManagementModule::t('back', 'User creation');
// $this->params['breadcrumbs'][] = ['label' => UserManagementModule::t('back', 'Users'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title; // comment to hide breadcrumbs
?>
<div class="user-create">

	<h2 class="lte-hide-title"><?= $this->title ?></h2>

	<div class="card">
		<div class="card-body">

			<?= $this->render('_form', compact('model')) ?>
		</div>
	</div>

</div>
