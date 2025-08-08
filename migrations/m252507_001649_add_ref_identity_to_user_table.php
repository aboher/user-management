<?php

use yii\db\Migration;

class m252507_001649_add_ref_identity_to_user_table extends Migration
{
	public function safeUp()
	{

		$identityClass = \Yii::$app->getModule('user-management')->identityClass;

		if ($identityClass == null) {
			echo "Migration 'm252507_001649_add_ref_identity_to_user_table' was not executed.\n";
			echo "because there is no identityClass specified.\n";
			return true;
		}

		$userTablename = \Yii::$app->getModule('user-management')->user_table;

		$this->addColumn(
			$userTablename,
			'ref_identity',
			$this->integer(),
		);

		$this->createIndex(
			'idx-user-ref_identity',
			$userTablename,
			'ref_identity'
		);

		$this->addForeignKey(
			'fk_users_identity',
			$userTablename,
			'ref_identity',
			$identityClass::tableName(),
			'id',
			'CASCADE'
		);
	}

	public function safeDown()
	{
		$identityClass = \Yii::$app->getModule('user-management')->identityClass;

		if ($identityClass == null) {
			echo "Migration 'm252507_001649_add_ref_identity_to_user_table' was not executed.\n";
			echo "because there is no identityClass specified.\n";
			return true;
		}

		$userTablename = \Yii::$app->getModule('user-management')->user_table;

		$cont = 0;
		if ($this->doesTableHaveColumn($userTablename, 'ref_identity')) {
			// Check if there are users using the ref_identity foreign key
			// Use count() to check for existing records
			$count = (new \yii\db\Query())
				->from($userTablename)
				->where(['IS NOT', 'ref_identity', null])
				->count('*', $this->db);
		}

		if ($count > 0) {
			echo "Migration 'm252507_001649_add_ref_identity_to_user_table' cannot be reverted.\n";
			echo "The table '{$userTablename}' contains {$count} users with an active 'ref_identity' association.\n";
			echo "Dropping 'ref_identity' column would cause data loss or integrity issues for these users.\n";
			echo "Please clear or re-assign the 'ref_identity' for these users, or handle data migration manually before reverting this migration.\n";
			return false; // Abort migration
		} else {
			echo "No active 'ref_identity' associations found in '{$userTablename}'. Proceeding with reversion.\n";
		}

		if ($this->doesForeignKeyExist($userTablename, 'fk_users_identity')) {
			echo "Dropping foreign key 'fk_users_identity' from '{$userTablename}'...\n";
			$this->dropForeignKey(
				'fk_users_identity',
				$userTablename
			);
		} else {
			echo "Foreign key 'fk_users_identity' on '{$userTablename}' does not exist, skipping drop.\n";
		}

		if ($this->doesIndexExist($userTablename, 'idx-user-ref_identity')) {
			echo "Dropping index 'idx-user-ref_identity' from '{$userTablename}'...\n";
			$this->dropIndex(
				'idx-user-ref_identity',
				$userTablename
			);
		} else {
			echo "Index 'idx-user-ref_identity' on '{$userTablename}' does not exist, skipping drop.\n";
		}

		if ($this->doesTableHaveColumn($userTablename, 'ref_identity')) {
			echo "Dropping column 'ref_identity' from '{$userTablename}'...\n";
			$this->dropColumn(
				$userTablename,
				'ref_identity'
			);
		} else {
			echo "Column 'ref_identity' on '{$userTablename}' does not exist, skipping drop.\n";
		}
	}

	private function doesForeignKeyExist($tableName, $foreignKeyName): bool
	{
		$tableSchema = $this->db->getTableSchema($tableName, true);
		return $tableSchema && isset($tableSchema?->foreignKeys[$foreignKeyName]);
	}

	private function doesIndexExist($tableName, $indexName): bool
	{
		$sql = <<<SQL
		SELECT COUNT(*) as count
		FROM INFORMATION_SCHEMA.STATISTICS
		WHERE TABLE_SCHEMA = DATABASE()
		AND TABLE_NAME = :tableName
		AND INDEX_NAME = :indexName
		SQL;


		$count = $this->db->createCommand($sql, [
			':tableName' => str_replace(['{{%', '}}'], '', $tableName),
			':indexName' => $indexName
		])->queryScalar();
		return $count > 0;
	}

	private function doesTableHaveColumn($tableName, $columnName): bool
	{
		$tableSchema = $this->db->getTableSchema($tableName, true);
		return $tableSchema && isset($tableSchema->columns[$columnName]);
	}
}
