<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminlteActionPermission
 * 
 * @property int $permission_id
 * @property int $action_id
 * 
 * @property AdminlteAction $adminlte_action
 * @property AdminltePermission $adminlte_permission
 *
 * @package App\Models
 */
class AdminlteActionPermission extends Model
{
	protected $table = 'adminlte_action_permission';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'permission_id' => 'int',
		'action_id' => 'int'
	];

	public function adminlte_action()
	{
		return $this->belongsTo(AdminlteAction::class, 'action_id');
	}

	public function adminlte_permission()
	{
		return $this->belongsTo(Permission::class, 'permission_id');
	}
}
