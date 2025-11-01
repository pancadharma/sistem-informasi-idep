# IDEP Models Context

## Model Architecture for Training Management System

### Core Models Structure

#### User Model
```php
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'address', 
        'organization', 'position', 'is_active'
    ];

    protected $hidden = ['password', 'remember_token'];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    // Role constants
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_ADMIN = 'admin';
    const ROLE_STAFF = 'staff';
    const ROLE_TRAINER = 'trainer';
    const ROLE_PARTICIPANT = 'participant';

    // Relationships
    public function trainings()
    {
        return $this->belongsToMany(Training::class, 'training_participants');
    }

    public function assignedTrainings()
    {
        return $this->hasMany(Training::class, 'trainer_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
}
```

#### Training Model (Program Pelatihan)
```php
class Training extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'description', 'start_date', 'end_date', 'location',
        'max_participants', 'trainer_id', 'status', 'category_id',
        'objectives', 'requirements', 'materials', 'cost'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'objectives' => 'array',
        'requirements' => 'array',
        'materials' => 'array',
        'cost' => 'decimal:2',
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'training_participants')
                    ->withPivot('enrollment_date', 'status', 'completion_date')
                    ->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(TrainingCategory::class, 'category_id');
    }

    public function sessions()
    {
        return $this->hasMany(TrainingSession::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', '!=', self::STATUS_CANCELLED);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', self::STATUS_ONGOING);
    }
}
```

#### TrainingSession Model (Sesi Pelatihan)
```php
class TrainingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_id', 'session_number', 'title', 'description',
        'session_date', 'start_time', 'end_time', 'location',
        'materials', 'notes'
    ];

    protected $casts = [
        'session_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'materials' => 'array',
    ];

    // Relationships
    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
```

#### Attendance Model (Kehadiran)
```php
class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'training_id', 'training_session_id',
        'attendance_date', 'status', 'notes', 'check_in_time',
        'check_out_time'
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    // Status constants
    const STATUS_PRESENT = 'present';
    const STATUS_ABSENT = 'absent';
    const STATUS_LATE = 'late';
    const STATUS_EXCUSED = 'excused';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function session()
    {
        return $this->belongsTo(TrainingSession::class, 'training_session_id');
    }

    // Scopes
    public function scopePresent($query)
    {
        return $query->where('status', self::STATUS_PRESENT);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('attendance_date', [$startDate, $endDate]);
    }
}
```

#### TrainingCategory Model (Kategori Pelatihan)
```php
class TrainingCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'color', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function trainings()
    {
        return $this->hasMany(Training::class, 'category_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
```

#### Achievement Model (Pencapaian Peserta)
```php
class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'training_id', 'achievement_type', 'title',
        'description', 'points', 'awarded_date', 'certificate_url'
    ];

    protected $casts = [
        'awarded_date' => 'date',
        'points' => 'integer',
    ];

    // Achievement types
    const TYPE_COMPLETION = 'completion';
    const TYPE_EXCELLENCE = 'excellence';
    const TYPE_PARTICIPATION = 'participation';
    const TYPE_SPECIAL = 'special';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }
}
```

## Model Relationships Summary

### User Relationships
- `belongsToMany(Training)` - Participant enrollments
- `hasMany(Training)` - Assigned as trainer
- `hasMany(Attendance)` - Attendance records
- `hasMany(Achievement)` - Earned achievements

### Training Relationships
- `belongsTo(User)` - Trainer assignment
- `belongsToMany(User)` - Enrolled participants
- `belongsTo(TrainingCategory)` - Category classification
- `hasMany(TrainingSession)` - Training sessions
- `hasMany(Attendance)` - Attendance records
- `hasMany(Achievement)` - Achievements earned

## Common Query Patterns

### Training Management Queries
```php
// Get active trainings with trainer and category
Training::with(['trainer', 'category'])
    ->active()
    ->orderBy('start_date')
    ->get();

// Get upcoming trainings for a specific trainer
Training::where('trainer_id', $trainerId)
    ->upcoming()
    ->with('participants')
    ->get();

// Get participant's enrolled trainings
$user->trainings()
    ->with('trainer')
    ->active()
    ->orderBy('start_date')
    ->get();
```

### Attendance Queries
```php
// Get attendance for a specific training
Attendance::where('training_id', $trainingId)
    ->with(['user', 'session'])
    ->orderBy('attendance_date')
    ->get();

// Get attendance statistics
Attendance::where('training_id', $trainingId)
    ->selectRaw('status, COUNT(*) as count')
    ->groupBy('status')
    ->get();
```

### Reporting Queries
```php
// Monthly training report
Training::whereMonth('start_date', $month)
    ->whereYear('start_date', $year)
    ->with(['participants', 'trainer'])
    ->get();

// Participant achievement summary
Achievement::where('user_id', $userId)
    ->with('training')
    ->orderBy('awarded_date', 'desc')
    ->get();
```

## Model Validation Rules
Always use Form Request classes for validation, but here are common rules:

### User Validation
```php
'name' => 'required|string|max:255',
'email' => 'required|email|unique:users,email',
'phone' => 'nullable|string|max:20',
'role' => 'required|in:super_admin,admin,staff,trainer,participant',
```

### Training Validation
```php
'title' => 'required|string|max:255',
'start_date' => 'required|date|after:today',
'end_date' => 'required|date|after:start_date',
'max_participants' => 'required|integer|min:1',
'trainer_id' => 'required|exists:users,id',
```

## Best Practices for IDEP Models
1. Always use soft deletes for important data
2. Implement proper relationships with foreign key constraints
3. Use constants for status/type values
4. Add scopes for common query patterns
5. Cast dates and booleans appropriately
6. Use meaningful accessor and mutator methods
7. Implement proper validation in Form Requests
8. Add database indexes for frequently queried fields