<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Projects;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Project
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $status
 * @property int $leader_id
 * @property int $created_by
 * @property Carbon|null $start_date
 * @property Carbon|null $end_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Project extends Model
{
	protected $table = 'projects';

	public const STATUS_PLANNED = 'planned';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_ON_HOLD = 'on_hold';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_PLANNED => 'Planeado',
        self::STATUS_IN_PROGRESS => 'En curso',
        self::STATUS_COMPLETED => 'Completado',
        self::STATUS_ON_HOLD => 'En pausa',
        self::STATUS_CANCELLED => 'Cancelado',
    ];

	protected $casts = [
		'leader_id' => 'int',
		'created_by' => 'int',
		'start_date' => 'datetime',
		'end_date' => 'datetime'
	];

	protected $fillable = [
		'name',
		'description',
		'status',
		'leader_id',
		'created_by',
		'start_date',
		'end_date'
	];

	public function leader()
	{
		return $this->belongsTo(User::class, 'leader_id');
	}

    public function creator()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

    public function members()
    {
        return $this->belongsToMany(
            User::class,
            'project_members'
        );
    }

    public function phases()
    {
        return $this->hasMany(ProjectPhase::class);
    }

    public function activities()
    {
        return $this->hasMany(ProjectActivity::class);
    }

}
