<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Projects;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectPhase
 * 
 * @property int $id
 * @property int $project_id
 * @property int $order
 * @property string $name
 * @property string $status
 * @property Carbon|null $start_date
 * @property Carbon|null $end_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Project $project
 * @property Collection|ProjectActivity[] $project_activities
 *
 * @package App\Models
 */
class ProjectPhase extends Model
{
	protected $table = 'project_phases';

	public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_NO_ACTIVITY = 'no_activity';

    public const STATUSES = [
        self::STATUS_PENDING => 'Pendiente',
        self::STATUS_IN_PROGRESS => 'En curso',
        self::STATUS_COMPLETED => 'Completada',
        self::STATUS_NO_ACTIVITY => 'Sin Actividad',
    ];


	protected $casts = [
		'project_id' => 'int',
		'order' => 'int',
		'start_date' => 'datetime',
		'end_date' => 'datetime'
	];

	protected $fillable = [
		'project_id',
		'order',
		'name',
		'status',
		'start_date',
		'end_date'
	];

	public function project()
	{
		return $this->belongsTo(Project::class);
	}

	public function activities()
	{
		return $this->hasMany(ProjectActivity::class, 'phase_id');
	}
}
