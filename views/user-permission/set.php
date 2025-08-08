<?php
/**
 * @var yii\web\View $this
 * @var array $permissionsByGroup
 * @var webvimark\modules\UserManagement\models\User $user
 */

use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\bootstrap5\BootstrapPluginAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

BootstrapPluginAsset::register($this);
$this->title = UserManagementModule::t('back', 'Roles and permissions for user:') . ' ' . $user->username;

// $this->params['breadcrumbs'][] = ['label' => UserManagementModule::t('back', 'Users'), 'url' => ['/user-management/user/index']];
// $this->params['breadcrumbs'][] = $this->title; // comment to hide breadcrumbs
?>

<h2 class="lte-hide-title"><?= $this->title ?></h2>

<?php if ( Yii::$app->session->hasFlash('success') ): ?>
	<div class="alert alert-success text-center">
		<?= Yii::$app->session->getFlash('success') ?>
	</div>
<?php endif; ?>

<div class="row">
	<div class="col-sm-4">
		<div class="card">
			<div class="card-header">
				<strong>
					<i class="bi bi-grid-3x3-gap-fill"></i> <?= UserManagementModule::t('back', 'Roles') ?>
				</strong>
			</div>
			<div class="card-body">

				<?= Html::beginForm(['set-roles', 'id'=>$user->id]) ?>

				<?php foreach (Role::getAvailableRoles() as $aRole): ?>
					<div class="form-check">
						<?php $isChecked = in_array($aRole['name'], ArrayHelper::map(Role::getUserRoles($user->id), 'name', 'name')) ? 'checked' : '' ?>

						<?= GhostHtml::a(
							'<i class="bi bi-pencil-square"></i>',
							['/user-management/role/view', 'id'=>$aRole['name']],
							['target'=>'_blank']
						) ?>

						<?php if ( Yii::$app->getModule('user-management')->userCanHaveMultipleRoles ): ?>
							<input class="form-check-input" type="checkbox" <?= $isChecked ?> name="roles[]" value="<?= $aRole['name'] ?>" id="role_<?= $aRole['name'] ?>">
						<?php else: ?>
							<input class="form-check-input" type="radio" <?= $isChecked ?> name="roles" value="<?= $aRole['name'] ?>" id="role_<?= $aRole['name'] ?>">
						<?php endif; ?>

						<label class="form-check-label" for="role_<?= $aRole['name'] ?>">
							<?= $aRole['description'] ?>
						</label>
					</div>
				<?php endforeach ?>

				<br/>

				<div class="mt-3">
					<?php if ( Yii::$app->user->isSuperadmin OR Yii::$app->user->id != $user->id ): ?>

						<?= Html::submitButton(
							'<i class="bi bi-check-lg"></i> ' . UserManagementModule::t('back', 'Save'),
							['class'=>'btn btn-primary btn-sm']
						) ?>
					<?php else: ?>
						<div class="alert alert-warning text-center">
							<?= UserManagementModule::t('back', 'You can not change own permissions') ?>
						</div>
					<?php endif; ?>
				</div>

				<?= Html::endForm() ?>
			</div>
		</div>
	</div>

	<div class="col-sm-8">
		<div class="card">
			<div class="card-header">
				<strong>
					<i class="bi bi-grid-3x3-gap-fill"></i> <?= UserManagementModule::t('back', 'Permissions') ?>
				</strong>
			</div>
			<div class="card-body">

				<div class="row">
					<?php foreach ($permissionsByGroup as $groupName => $permissions): ?>

						<div class="col-sm-6">
							<fieldset class="mb-3">
								<legend class="fs-5"><?= $groupName ?></legend>

								<ul class="list-unstyled">
									<?php foreach ($permissions as $permission): ?>
										<li class="mb-2">
											<?= $permission->description ?>

											<?= GhostHtml::a(
												'<i class="bi bi-pencil-square"></i>',
												['/user-management/permission/view', 'id'=>$permission->name],
												['target'=>'_blank', 'class'=>'ms-2']
											) ?>
										</li>
									<?php endforeach ?>
								</ul>
							</fieldset>

							<br/>
						</div>

					<?php endforeach ?>

				</div>

			</div>
		</div>
	</div>
</div>

<?php
$this->registerJs(<<<JS

$('.role-help-btn').off('mouseover mouseleave')
	.on('mouseover', function(){
		var _t = $(this);
		_t.popover('show');
	}).on('mouseleave', function(){
		var _t = $(this);
		_t.popover('hide');
	});
JS
);
?>