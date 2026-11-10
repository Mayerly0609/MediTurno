<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class AdminlteAction
 * 
 * @property int $id
 * @property string $module
 * @property string $route
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|AdminlteActionPermission[] $adminlte_action_permissions
 *
 * @package App\Models
 */
class AdminlteAction extends Model
{
	protected $table = 'adminlte_actions';

	protected $fillable = [
		'module',
		'route'
	];

	public function permissions(): BelongsToMany
	{
		return $this->belongsToMany(
            Permission::class,
            'adminlte_action_permission',
            'action_id',
            'permission_id'
        );
	}
}
