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
 * Class AdminltePermission
 * 
 * @property int $id
 * @property string $name
 * @property string|null $label
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|AdminlteActionPermission[] $adminlte_action_permissions
 * @property Collection|Role[] $adminlte_permission_roles
 *
 * @package App\Models
 */
class AdminltePermission extends Model
{
	protected $table = 'adminlte_permissions';

	protected $fillable = [
		'name',
		'label'
	];

  
	public function actions(): BelongsToMany
    {
        return $this->belongsToMany(
            AdminlteAction::class,
            'adminlte_action_permission',
            'permission_id',
            'action_id'
        );
    }

	public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'adminlte_permission_role',
            'permission_id',
            'role_id'
        );
    }

    
}
