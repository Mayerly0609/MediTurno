<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Projects;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectActivity
 * 
 * @property int $id
 * @property int $project_id
 * @property int $phase_id
 * @property string $title
 * @property string|null $description
 * @property int $responsible_id
 * @property string $status
 * @property string $priority
 * @property Carbon|null $due_date
 * @property float|null $estimated_hours
 * @property int $progress
 * @property int $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property ProjectPhase $project_phase
 * @property Project $project
 *
 * @package App\Models
 */
class ProjectActivity extends Model
{
	protected $table = 'project_activities';

	public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_PENDING => 'Pendiente',
        self::STATUS_IN_PROGRESS => 'En curso',
        self::STATUS_COMPLETED => 'Completada',
        self::STATUS_CANCELLED => 'Cancelada',
    ];

	protected $casts = [
		'project_id' => 'int',
		'phase_id' => 'int',
		'responsible_id' => 'int',
		'due_date' => 'datetime',
		'estimated_hours' => 'float',
		'progress' => 'int',
		'created_by' => 'int'
	];

	protected $fillable = [
		'project_id',
		'phase_id',
		'title',
		'description',
		'responsible_id',
		'status',
		'priority',
		'due_date',
		'estimated_hours',
		'progress',
		'created_by'
	];

	public function responsible()
	{
		return $this->belongsTo(User::class, 'responsible_id');
	}

	public function project_phase()
	{
		return $this->belongsTo(ProjectPhase::class, 'phase_id');
	}

	public function project()
	{
		return $this->belongsTo(Project::class);
	}
}
