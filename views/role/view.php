<?php
/**
 * @var yii\widgets\ActiveForm $form
 * @var array $childRoles
 * @var array $allRoles
 * @var array $routes
 * @var array $currentRoutes
 * @var array $permissionsByGroup
 * @var array $currentPermissions
 * @var yii\rbac\Role $role
 */

use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = UserManagementModule::t('back', 'Permissions for role:') . ' '. $role->description;
// $this->params['breadcrumbs'][] = ['label' => UserManagementModule::t('back', 'Roles'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title; // comment to hide breadcrumbs
?>

<h2 class="lte-hide-title"><?= $this->title ?></h2>

<?php if ( Yii::$app->session->hasFlash('success') ): ?>
	<div class="alert alert-success text-center">
		<?= Yii::$app->session->getFlash('success') ?>
	</div>
<?php endif; ?>

<p>
	<?= GhostHtml::a(UserManagementModule::t('back', 'Edit'), ['update', 'id' => $role->name], ['class' => 'btn btn-sm btn-primary']) ?>
	<?= GhostHtml::a(UserManagementModule::t('back', 'Create'), ['create'], ['class' => 'btn btn-sm btn-success']) ?>
</p>

<div class="row">
	<div class="col-sm-4">
		<div class="card">
			<div class="card-header">
				<strong>
					<i class="bi bi-grid-3x3-gap-fill"></i> <?= UserManagementModule::t('back', 'Child roles') ?>
				</strong>
			</div>
			<div class="card-body">
				<?= Html::beginForm(['set-child-roles', 'id'=>$role->name]) ?>

				<?php foreach ($allRoles as $aRole): ?>
					<div class="form-check">
						<?php $isChecked = in_array($aRole['name'], ArrayHelper::map($childRoles, 'name', 'name')) ? 'checked' : '' ?>
						<input class="form check-input" type="checkbox" <?= $isChecked ?> name="child_roles[]" value="<?= $aRole['name'] ?>" id="role_<?= $aRole['name'] ?>">
						<?= GhostHtml::a(
							'<i class="bi bi-pencil-square"></i>',
							['/user-management/role/view', 'id'=>$aRole['name']],
							['target'=>'_blank']
						) ?>
						<label class="form-check-label" for="role_<?= $aRole['name'] ?>">
							<?= $aRole['description'] ?>
						</label>
					</div>
				<?php endforeach ?>


				<hr/>
				<?= Html::submitButton(
					'<i class="bi bi-check-lg"></i> ' . UserManagementModule::t('back', 'Save'),
					['class'=>'btn btn-primary btn-sm']
				) ?>

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
				<?= Html::beginForm(['set-child-permissions', 'id'=>$role->name]) ?>

				<div class="row">
					<?php foreach ($permissionsByGroup as $groupName => $permissions): ?>
						<div class="col-sm-6">
							<fieldset class="border p-3 mb-3">
								<legend><?= $groupName ?></legend>

								<?php foreach ($permissions as $permission): ?>
									<div class="form-check">
										<?php $isChecked = in_array($permission->name, ArrayHelper::map($currentPermissions, 'name', 'name')) ? 'checked' : '' ?>
										<input class="form-check-input" type="checkbox" <?= $isChecked ?> name="child_permissions[]" value="<?= $permission->name ?>" id="perm_<?= $permission->name ?>">
										<?= GhostHtml::a(
											'<i class="bi bi-pencil-square"></i>',
											['/user-management/permission/view', 'id'=>$permission->name],
											['target'=>'_blank']
										) ?>
										<label class="form-check-label" for="perm_<?= $permission->name ?>">
											<?= $permission->description ?>
										</label>
									</div>
								<?php endforeach ?>

							</fieldset>
							<br/>
						</div>


					<?php endforeach ?>
				</div>

				<hr/>
				<?= Html::submitButton(
					'<i class="bi bi-check-lg"></i> ' . UserManagementModule::t('back', 'Save'),
					['class'=>'btn btn-primary btn-sm']
				) ?>

				<?= Html::endForm() ?>

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