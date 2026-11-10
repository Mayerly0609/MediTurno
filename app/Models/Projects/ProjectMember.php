<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Projects;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectMember
 * 
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property string $role
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Project $project
 * @property User $user
 *
 * @package App\Models
 */
class ProjectMember extends Model
{
	protected $table = 'project_members';

	protected $casts = [
		'project_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'project_id',
		'user_id',
		'role'
	];

	public function project()
	{
		return $this->belongsTo(Project::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
