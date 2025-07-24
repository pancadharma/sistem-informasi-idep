# IDEP Controllers Context

## Controller Architecture for Training Management System

### Base Controller Structure
All controllers should extend the base Controller and follow these patterns:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

abstract class Controller
{
    protected function successResponse($data = null, $message = 'Success', $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($message = 'Error', $errors = null, $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    protected function viewResponse(string $view, array $data = []): View
    {
        return view($view, $data);
    }
}
```

### TrainingController - Core Training Management
```php
<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingCategory;
use App\Models\User;
use App\Http\Requests\StoreTrainingRequest;
use App\Http\Requests\UpdateTrainingRequest;
use Illuminate\Http\Request;
use DataTables;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $trainings = Training::with(['trainer', 'category'])
                ->select('trainings.*');

            return DataTables::of($trainings)
                ->addColumn('trainer_name', function($training) {
                    return $training->trainer ? $training->trainer->name : '-';
                })
                ->addColumn('participants_count', function($training) {
                    return $training->participants()->count() . '/' . $training->max_participants;
                })
                ->addColumn('status_badge', function($training) {
                    $badgeClass = match($training->status) {
                        'draft' => 'badge-secondary',
                        'published' => 'badge-primary',
                        'ongoing' => 'badge-warning',
                        'completed' => 'badge-success',
                        'cancelled' => 'badge-danger',
                        default => 'badge-secondary'
                    };
                    return '<span class="badge ' . $badgeClass . '">' . ucfirst($training->status) . '</span>';
                })
                ->addColumn('actions', function($training) {
                    return '
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-info" onclick="viewTraining(' . $training->id . ')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-warning" onclick="editTraining(' . $training->id . ')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteTraining(' . $training->id . ')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['status_badge', 'actions'])
                ->make(true);
        }

        $categories = TrainingCategory::active()->get();
        $trainers = User::byRole(User::ROLE_TRAINER)->active()->get();

        return $this->viewResponse('trainings.index', compact('categories', 'trainers'));
    }

    public function create()
    {
        $categories = TrainingCategory::active()->get();
        $trainers = User::byRole(User::ROLE_TRAINER)->active()->get();

        return $this->viewResponse('trainings.create', compact('categories', 'trainers'));
    }

    public function store(StoreTrainingRequest $request)
    {
        try {
            $training = Training::create($request->validated());

            if ($request->ajax()) {
                return $this->successResponse($training, 'Training created successfully');
            }

            return redirect()->route('trainings.index')
                ->with('success', 'Training created successfully');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return $this->errorResponse('Failed to create training: ' . $e->getMessage());
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create training']);
        }
    }

    public function show(Training $training)
    {
        $training->load(['trainer', 'category', 'participants', 'sessions']);
        
        return $this->viewResponse('trainings.show', compact('training'));
    }

    public function edit(Training $training)
    {
        $categories = TrainingCategory::active()->get();
        $trainers = User::byRole(User::ROLE_TRAINER)->active()->get();

        return $this->viewResponse('trainings.edit', compact('training', 'categories', 'trainers'));
    }

    public function update(UpdateTrainingRequest $request, Training $training)
    {
        try {
            $training->update($request->validated());

            if ($request->ajax()) {
                return $this->successResponse($training, 'Training updated successfully');
            }

            return redirect()->route('trainings.index')
                ->with('success', 'Training updated successfully');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return $this->errorResponse('Failed to update training: ' . $e->getMessage());
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update training']);
        }
    }

    public function destroy(Training $training)
    {
        try {
            $training->delete();

            return $this->successResponse(null, 'Training deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete training: ' . $e->getMessage());
        }
    }

    public function participants(Training $training)
    {
        $participants = $training->participants()->with('attendances')->get();
        
        return $this->viewResponse('trainings.participants', compact('training', 'participants'));
    }

    public function enrollParticipant(Request $request, Training $training)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        try {
            $training->participants()->attach($request->user_id, [
                'enrollment_date' => now(),
                'status' => 'enrolled'
            ]);

            return $this->successResponse(null, 'Participant enrolled successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to enroll participant: ' . $e->getMessage());
        }
    }
}
```

### AttendanceController - Attendance Management
```php
<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Training;
use App\Models\TrainingSession;
use App\Http\Requests\StoreAttendanceRequest;
use Illuminate\Http\Request;
use DataTables;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $attendances = Attendance::with(['user', 'training', 'session'])
                ->when($request->training_id, function($query, $trainingId) {
                    return $query->where('training_id', $trainingId);
                })
                ->when($request->date_range, function($query, $dateRange) {
                    $dates = explode(' - ', $dateRange);
                    return $query->whereBetween('attendance_date', $dates);
                })
                ->select('attendances.*');

            return DataTables::of($attendances)
                ->addColumn('participant_name', function($attendance) {
                    return $attendance->user->name;
                })
                ->addColumn('training_title', function($attendance) {
                    return $attendance->training->title;
                })
                ->addColumn('status_badge', function($attendance) {
                    $badgeClass = match($attendance->status) {
                        'present' => 'badge-success',
                        'absent' => 'badge-danger',
                        'late' => 'badge-warning',
                        'excused' => 'badge-info',
                        default => 'badge-secondary'
                    };
                    return '<span class="badge ' . $badgeClass . '">' . ucfirst($attendance->status) . '</span>';
                })
                ->addColumn('actions', function($attendance) {
                    return '
                        <button type="button" class="btn btn-sm btn-warning" onclick="editAttendance(' . $attendance->id . ')">
                            <i class="fas fa-edit"></i>
                        </button>
                    ';
                })
                ->rawColumns(['status_badge', 'actions'])
                ->make(true);
        }

        $trainings = Training::active()->get();
        return $this->viewResponse('attendances.index', compact('trainings'));
    }

    public function store(StoreAttendanceRequest $request)
    {
        try {
            $attendance = Attendance::create($request->validated());

            return $this->successResponse($attendance, 'Attendance recorded successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to record attendance: ' . $e->getMessage());
        }
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'session_id' => 'required|exists:training_sessions,id',
            'attendance_date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.user_id' => 'required|exists:users,id',
            'attendances.*.status' => 'required|in:present,absent,late,excused',
        ]);

        try {
            foreach ($request->attendances as $attendanceData) {
                Attendance::updateOrCreate(
                    [
                        'user_id' => $attendanceData['user_id'],
                        'training_id' => $request->training_id,
                        'training_session_id' => $request->session_id,
                        'attendance_date' => $request->attendance_date,
                    ],
                    [
                        'status' => $attendanceData['status'],
                        'notes' => $attendanceData['notes'] ?? null,
                        'check_in_time' => $attendanceData['check_in_time'] ?? null,
                    ]
                );
            }

            return $this->successResponse(null, 'Bulk attendance recorded successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to record bulk attendance: ' . $e->getMessage());
        }
    }

    public function getAttendanceStats(Training $training)
    {
        $stats = Attendance::where('training_id', $training->id)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return $this->successResponse($stats);
    }
}
```

### ReportController - Report Generation
```php
<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;

class ReportController extends Controller
{
    public function index()
    {
        return $this->viewResponse('reports.index');
    }

    public function trainingReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,excel,json'
        ]);

        $trainings = Training::with(['trainer', 'participants', 'category'])
            ->whereBetween('start_date', [$request->start_date, $request->end_date])
            ->get();

        switch ($request->format) {
            case 'pdf':
                return $this->generateTrainingPdfReport($trainings, $request->start_date, $request->end_date);
            case 'excel':
                return $this->generateTrainingExcelReport($trainings);
            case 'json':
                return $this->successResponse($trainings);
        }
    }

    public function attendanceReport(Request $request)
    {
        $request->validate([
            'training_id' => 'sometimes|exists:trainings,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,excel,json'
        ]);

        $attendances = Attendance::with(['user', 'training', 'session'])
            ->when($request->training_id, function($query, $trainingId) {
                return $query->where('training_id', $trainingId);
            })
            ->whereBetween('attendance_date', [$request->start_date, $request->end_date])
            ->get();

        switch ($request->format) {
            case 'pdf':
                return $this->generateAttendancePdfReport($attendances, $request->start_date, $request->end_date);
            case 'excel':
                return $this->generateAttendanceExcelReport($attendances);
            case 'json':
                return $this->successResponse($attendances);
        }
    }

    public function participantProgressReport(Request $request)
    {
        $request->validate([
            'participant_id' => 'required|exists:users,id',
            'format' => 'required|in:pdf,excel,json'
        ]);

        $participant = User::with([
            'trainings',
            'attendances.training',
            'achievements.training'
        ])->findOrFail($request->participant_id);

        switch ($request->format) {
            case 'pdf':
                return $this->generateParticipantPdfReport($participant);
            case 'json':
                return $this->successResponse($participant);
        }
    }

    private function generateTrainingPdfReport($trainings, $startDate, $endDate)
    {
        $data = [
            'trainings' => $trainings,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'generated_at' => now()
            ];
        $pdf = PDF::loadView('reports.training_report', $data);

        