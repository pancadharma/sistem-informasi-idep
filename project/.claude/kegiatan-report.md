# 📊 Dynamic Report Generation Guide - IDEP LTE

## 🎯 Purpose
This guide helps AI assistants generate flexible, filterable reports for the IDEP-LTE application with cascading/dependent filters and dynamic query building based on selected parameters.

---

## 🔍 Report Type: Activity Report (Laporan Kegiatan)

### Filter Parameters (Cascading/Dependent)

```
┌─────────────────────────────────────────────────────────────────┐
│  FILTER 1: Program (Required)                                   │
│  Source: trprogram                                              │
│  Display: nama + kode                                           │
│  [Select2 with AJAX search]                                     │
└──────────────────────┬──────────────────────────────────────────┘
                       │ Triggers load of ↓
┌─────────────────────────────────────────────────────────────────┐
│  FILTER 2: Activity Code (Optional)                             │
│  Source: trprogramoutcomeoutputactivity                         │
│  WHERE: Linked to selected program through hierarchy            │
│  Display: kode + nama                                           │
│  [Select2 AJAX - depends on FILTER 1]                           │
└──────────────────────┬──────────────────────────────────────────┘
                       │ Triggers load of ↓
┌─────────────────────────────────────────────────────────────────┐
│  FILTER 3: Activity Type (Optional)                             │
│  Source: mjeniskegiatan                                         │
│  WHERE: Based on trkegiatan linked to above selections          │
│  Display: nama                                                  │
│  [Select2 AJAX - depends on FILTER 1 & 2]                       │
└──────────────────────┬──────────────────────────────────────────┘
                       │ Triggers load of ↓
┌─────────────────────────────────────────────────────────────────┐
│  FILTER 4: Specific Activity Report (Optional)                  │
│  Source: trkegiatan                                             │
│  WHERE: Based on all above filters                              │
│  Display: tanggalmulai + jeniskegiatan                          │
│  [Select2 AJAX - depends on FILTER 1, 2 & 3]                    │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🔗 Data Relationship for Filtering

### The Connection Path (No Direct FK between trprogram ↔ trkegiatan):

```sql
trprogram.id
    ↓
trprogramoutcome.program_id = trprogram.id
    ↓
trprogramoutcomeoutput.programoutcome_id = trprogramoutcome.id
    ↓
trprogramoutcomeoutputactivity.programoutcomeoutput_id = trprogramoutcomeoutput.id
    ↓
trkegiatan.programoutcomeoutputactivity_id = trprogramoutcomeoutputactivity.id
```

**This means:** To filter trkegiatan by program, you MUST join through 4 intermediate tables.

---

## 📋 Filter Scenarios & Query Building

### Scenario 1: Only Program Selected
**User Input:** `program_id = 5`

**Query Logic:**
```sql
SELECT k.* 
FROM trkegiatan k
INNER JOIN trprogramoutcomeoutputactivity act ON k.programoutcomeoutputactivity_id = act.id
INNER JOIN trprogramoutcomeoutput outp ON act.programoutcomeoutput_id = outp.id
INNER JOIN trprogramoutcome outc ON outp.programoutcome_id = outc.id
INNER JOIN trprogram p ON outc.program_id = p.id
WHERE p.id = 5
ORDER BY k.tanggalmulai DESC
```

**Report Output:**
- All activities under Program ID 5
- Grouped by Activity Plan (trprogramoutcomeoutputactivity)
- Show summary statistics at the top

---

### Scenario 2: Program + Activity Code Selected
**User Input:** `program_id = 5, activity_code_id = 12`

**Query Logic:**
```sql
SELECT k.* 
FROM trkegiatan k
INNER JOIN trprogramoutcomeoutputactivity act ON k.programoutcomeoutputactivity_id = act.id
INNER JOIN trprogramoutcomeoutput outp ON act.programoutcomeoutput_id = outp.id
INNER JOIN trprogramoutcome outc ON outp.programoutcome_id = outc.id
INNER JOIN trprogram p ON outc.program_id = p.id
WHERE p.id = 5 
  AND act.id = 12
ORDER BY k.tanggalmulai DESC
```

**Report Output:**
- Only activities under specific Activity Plan (kode + nama)
- Show Activity Plan details at the top
- List all implementation reports (trkegiatan) below

---

### Scenario 3: Program + Activity Type Selected
**User Input:** `program_id = 5, jeniskegiatan_id = 3`

**Query Logic:**
```sql
SELECT k.* 
FROM trkegiatan k
INNER JOIN trprogramoutcomeoutputactivity act ON k.programoutcomeoutputactivity_id = act.id
INNER JOIN trprogramoutcomeoutput outp ON act.programoutcomeoutput_id = outp.id
INNER JOIN trprogramoutcome outc ON outp.programoutcome_id = outc.id
INNER JOIN trprogram p ON outc.program_id = p.id
WHERE p.id = 5 
  AND k.jeniskegiatan_id = 3
ORDER BY k.tanggalmulai DESC
```

**Report Output:**
- All activities of specific type (e.g., "Pelatihan") under Program ID 5
- Grouped by Activity Type
- Show type-specific aggregations

---

### Scenario 4: All Filters Selected (Most Specific)
**User Input:** `program_id = 5, activity_code_id = 12, jeniskegiatan_id = 3, kegiatan_id = 45`

**Query Logic:**
```sql
SELECT k.* 
FROM trkegiatan k
INNER JOIN trprogramoutcomeoutputactivity act ON k.programoutcomeoutputactivity_id = act.id
INNER JOIN trprogramoutcomeoutput outp ON act.programoutcomeoutput_id = outp.id
INNER JOIN trprogramoutcome outc ON outp.programoutcome_id = outc.id
INNER JOIN trprogram p ON outc.program_id = p.id
WHERE k.id = 45
```

**Report Output:**
- Single activity detail report
- Show full context (Program → Outcome → Output → Activity Plan → This Report)
- Include all related data (locations, partners, beneficiaries, type-specific details)

---

### Scenario 5: Program + Specific Activity Report Only
**User Input:** `program_id = 5, kegiatan_id = 45`

**Query Logic:**
```sql
SELECT k.* 
FROM trkegiatan k
INNER JOIN trprogramoutcomeoutputactivity act ON k.programoutcomeoutputactivity_id = act.id
INNER JOIN trprogramoutcomeoutput outp ON act.programoutcomeoutput_id = outp.id
INNER JOIN trprogramoutcome outc ON outp.programoutcome_id = outc.id
INNER JOIN trprogram p ON outc.program_id = p.id
WHERE p.id = 5 
  AND k.id = 45
```

**Report Output:**
- Same as Scenario 4 (single detail)
- Validate that kegiatan_id actually belongs to program_id

---

## 🎨 Report Output Structure

### Report Header Section
```
┌──────────────────────────────────────────────────────────────┐
│  LAPORAN KEGIATAN                                            │
│  [Organization Logo]                                         │
├──────────────────────────────────────────────────────────────┤
│  Program: [Program Name] ([Program Code])                   │
│  Periode: [Program Start Date] - [Program End Date]         │
│  Activity Plan: [If selected: Activity Code + Name]         │
│  Activity Type: [If selected: Activity Type Name]           │
│  Generated: [Current Date Time]                             │
└──────────────────────────────────────────────────────────────┘
```

### Summary Statistics Section (Aggregated Data)
```
┌──────────────────────────────────────────────────────────────┐
│  RINGKASAN KEGIATAN                                          │
├──────────────────────────────────────────────────────────────┤
│  Total Activities: [Count]                                   │
│  Date Range: [Earliest] - [Latest]                          │
│  Total Beneficiaries: [Sum of penerimamanfaattotal]         │
│    - Female: [Sum of penerimamanfaatperempuantotal]         │
│    - Male: [Sum of penerimamanfaatlakilakitotal]            │
│  Locations Covered: [Count distinct locations]              │
│  Partners Involved: [Count distinct partners]               │
│  Activity Types: [List of unique types]                     │
└──────────────────────────────────────────────────────────────┘
```

### Beneficiary Demographics Chart (Aggregated)
```
┌──────────────────────────────────────────────────────────────┐
│  DEMOGRAFI PENERIMA MANFAAT                                  │
├─────────────────────┬──────────┬─────────┬──────────────────┤
│  Category           │  Female  │  Male   │  Total           │
├─────────────────────┼──────────┼─────────┼──────────────────┤
│  Adults (Dewasa)    │  [SUM]   │  [SUM]  │  [SUM]           │
│  Elderly (Lansia)   │  [SUM]   │  [SUM]  │  [SUM]           │
│  Youth (Remaja)     │  [SUM]   │  [SUM]  │  [SUM]           │
│  Children (Anak)    │  [SUM]   │  [SUM]  │  [SUM]           │
│  With Disability    │  [SUM]   │  [SUM]  │  [SUM]           │
│  Marginalized       │  [SUM]   │  [SUM]  │  [SUM]           │
├─────────────────────┼──────────┼─────────┼──────────────────┤
│  TOTAL              │  [SUM]   │  [SUM]  │  [SUM]           │
└─────────────────────┴──────────┴─────────┴──────────────────┘
```

### Activity List Section (Looping)

#### Option A: Grouped by Activity Plan (When only program selected)
```
┌──────────────────────────────────────────────────────────────┐
│  ACTIVITY PLAN: [Activity Code] - [Activity Name]           │
│  Target: [Target from trprogramoutcomeoutputactivity]       │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ ACTIVITY REPORT #1                                     │ │
│  │ Date: [tanggalmulai] - [tanggalselesai]              │ │
│  │ Type: [jeniskegiatan name]                            │ │
│  │ Status: [status]                                       │ │
│  │                                                        │ │
│  │ Background: [deskripsilatarbelakang]                  │ │
│  │ Purpose: [deskripsitujuan]                            │ │
│  │ Output: [deskripsikeluaran]                           │ │
│  │                                                        │ │
│  │ Beneficiaries: [penerimamanfaattotal]                 │ │
│  │ Locations: [List from trkegiatan_lokasi]              │ │
│  │ Partners: [List from trkegiatan_mitra]                │ │
│  │                                                        │ │
│  │ [Type-Specific Details - Expandable]                  │ │
│  └────────────────────────────────────────────────────────┘ │
│                                                              │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ ACTIVITY REPORT #2                                     │ │
│  │ ...                                                    │ │
│  └────────────────────────────────────────────────────────┘ │
│                                                              │
└──────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────┐
│  ACTIVITY PLAN: [Next Activity Code] - [Name]               │
│  ...                                                         │
└──────────────────────────────────────────────────────────────┘
```

#### Option B: Flat List (When activity type selected)
```
┌──────────────────────────────────────────────────────────────┐
│  ACTIVITY TYPE: [Activity Type Name]                        │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  [Loop through all activities of this type]                 │
│  - Show compact card for each                               │
│  - Click to expand for full details                         │
│                                                              │
└──────────────────────────────────────────────────────────────┘
```

#### Option C: Single Detail (When specific kegiatan_id selected)
```
┌──────────────────────────────────────────────────────────────┐
│  DETAILED ACTIVITY REPORT                                    │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  [Full breadcrumb path]                                     │
│  Program → Outcome → Output → Activity Plan → THIS REPORT   │
│                                                              │
│  [ALL SECTIONS FROM KEGIATAN DETAIL PAGE]                   │
│  - Activity Header                                          │
│  - Activity Description                                     │
│  - Beneficiary Demographics (Full Table)                    │
│  - Activity Context (Locations, Partners, Sectors)          │
│  - Type-Specific Details (Full)                             │
│  - Media/Attachments                                        │
│                                                              │
└──────────────────────────────────────────────────────────────┘
```

---

## 🔧 Implementation Guide for AI Code Tools

### 1. Filter Form (Blade/HTML)

```html
<form id="reportFilterForm" method="GET" action="{{ route('reports.kegiatan') }}">
    <!-- Filter 1: Program (Required) -->
    <div class="form-group">
        <label>Program <span class="text-danger">*</span></label>
        <select name="program_id" id="program_id" class="form-control select2" required>
            <option value="">-- Pilih Program --</option>
        </select>
    </div>

    <!-- Filter 2: Activity Code (Optional, depends on Filter 1) -->
    <div class="form-group">
        <label>Kode Aktivitas (Opsional)</label>
        <select name="activity_code_id" id="activity_code_id" class="form-control select2">
            <option value="">-- Semua Aktivitas --</option>
        </select>
    </div>

    <!-- Filter 3: Activity Type (Optional, depends on Filter 1 & 2) -->
    <div class="form-group">
        <label>Jenis Kegiatan (Opsional)</label>
        <select name="jeniskegiatan_id" id="jeniskegiatan_id" class="form-control select2">
            <option value="">-- Semua Jenis --</option>
        </select>
    </div>

    <!-- Filter 4: Specific Report (Optional, depends on all above) -->
    <div class="form-group">
        <label>Laporan Spesifik (Opsional)</label>
        <select name="kegiatan_id" id="kegiatan_id" class="form-control select2">
            <option value="">-- Semua Laporan --</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-file-pdf"></i> Generate Report
    </button>
</form>
```

### 2. Select2 AJAX Configuration (JavaScript)

```javascript
// Filter 1: Program (Always loaded)
$('#program_id').select2({
    ajax: {
        url: '/api/programs',
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                search: params.term,
                page: params.page || 1
            };
        },
        processResults: function (data) {
            return {
                results: data.data.map(item => ({
                    id: item.id,
                    text: `${item.nama} (${item.kode})`
                })),
                pagination: {
                    more: data.current_page < data.last_page
                }
            };
        }
    },
    placeholder: '-- Pilih Program --',
    minimumInputLength: 0
});

// Filter 2: Activity Code (Depends on program_id)
$('#program_id').on('change', function() {
    const programId = $(this).val();
    
    // Reset dependent filters
    $('#activity_code_id').val(null).trigger('change');
    $('#jeniskegiatan_id').val(null).trigger('change');
    $('#kegiatan_id').val(null).trigger('change');
    
    if (!programId) return;
    
    // Reload activity codes for selected program
    $('#activity_code_id').select2({
        ajax: {
            url: '/api/activity-codes',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term,
                    program_id: programId,
                    page: params.page || 1
                };
            },
            processResults: function (data) {
                return {
                    results: data.data.map(item => ({
                        id: item.id,
                        text: `${item.kode} - ${item.nama}`
                    })),
                    pagination: {
                        more: data.current_page < data.last_page
                    }
                };
            }
        },
        placeholder: '-- Semua Aktivitas --',
        allowClear: true
    });
});

// Filter 3: Activity Type (Depends on program_id and activity_code_id)
$('#activity_code_id').on('change', function() {
    const programId = $('#program_id').val();
    const activityCodeId = $(this).val();
    
    // Reset dependent filters
    $('#jeniskegiatan_id').val(null).trigger('change');
    $('#kegiatan_id').val(null).trigger('change');
    
    if (!programId) return;
    
    // Reload activity types
    $('#jeniskegiatan_id').select2({
        ajax: {
            url: '/api/activity-types',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term,
                    program_id: programId,
                    activity_code_id: activityCodeId || null,
                    page: params.page || 1
                };
            },
            processResults: function (data) {
                return {
                    results: data.data.map(item => ({
                        id: item.id,
                        text: item.nama
                    })),
                    pagination: {
                        more: data.current_page < data.last_page
                    }
                };
            }
        },
        placeholder: '-- Semua Jenis --',
        allowClear: true
    });
});

// Filter 4: Specific Report (Depends on all above)
$('#jeniskegiatan_id').on('change', function() {
    const programId = $('#program_id').val();
    const activityCodeId = $('#activity_code_id').val();
    const jeniskegiatanId = $(this).val();
    
    // Reset dependent filter
    $('#kegiatan_id').val(null).trigger('change');
    
    if (!programId) return;
    
    // Reload specific reports
    $('#kegiatan_id').select2({
        ajax: {
            url: '/api/kegiatan-reports',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term,
                    program_id: programId,
                    activity_code_id: activityCodeId || null,
                    jeniskegiatan_id: jeniskegiatanId || null,
                    page: params.page || 1
                };
            },
            processResults: function (data) {
                return {
                    results: data.data.map(item => ({
                        id: item.id,
                        text: `${item.tanggalmulai} - ${item.jeniskegiatan_nama}`
                    })),
                    pagination: {
                        more: data.current_page < data.last_page
                    }
                };
            }
        },
        placeholder: '-- Semua Laporan --',
        allowClear: true
    });
});
```

### 3. API Endpoints (Laravel Routes)

```php
// routes/api.php

Route::get('/programs', 'Api\ProgramController@index');
Route::get('/activity-codes', 'Api\ActivityCodeController@index');
Route::get('/activity-types', 'Api\ActivityTypeController@index');
Route::get('/kegiatan-reports', 'Api\KegiatanController@index');
```

### 4. Controller Logic (Dynamic Query Building)

```php
// app/Http/Controllers/ReportController.php

public function kegiatanReport(Request $request)
{
    $programId = $request->input('program_id'); // Required
    $activityCodeId = $request->input('activity_code_id'); // Optional
    $jeniskegiatanId = $request->input('jeniskegiatan_id'); // Optional
    $kegiatanId = $request->input('kegiatan_id'); // Optional
    
    // Validate required filter
    $request->validate([
        'program_id' => 'required|exists:trprogram,id'
    ]);
    
    // Start building query
    $query = Trkegiatan::query()
        ->with([
            'programoutcomeoutputactivity',
            'programoutcomeoutputactivity.programoutcomeoutput',
            'programoutcomeoutputactivity.programoutcomeoutput.programoutcome',
            'programoutcomeoutputactivity.programoutcomeoutput.programoutcome.program',
            'jeniskegiatan',
            'user',
            'kegiatanLokasi.desa.kecamatan.kabupaten.provinsi',
            'kegiatanMitra.mitra',
            'kegiatanSektor.sektor',
            'kegiatanPenulis.user',
            'kegiatanPenulis.peran'
        ]);
    
    // Filter by program (through joins)
    $query->whereHas('programoutcomeoutputactivity.programoutcomeoutput.programoutcome', function($q) use ($programId) {
        $q->where('program_id', $programId);
    });
    
    // Apply optional filters
    if ($activityCodeId) {
        $query->where('programoutcomeoutputactivity_id', $activityCodeId);
    }
    
    if ($jeniskegiatanId) {
        $query->where('jeniskegiatan_id', $jeniskegiatanId);
    }
    
    if ($kegiatanId) {
        $query->where('id', $kegiatanId);
    }
    
    // Execute query
    $kegiatan = $query->orderBy('tanggalmulai', 'DESC')->get();
    
    // Get program data for header
    $program = Trprogram::with([
        'programPartner.partner',
        'programPendonor.pendonor',
        'programUser.user',
        'programLokasi.provinsi'
    ])->findOrFail($programId);
    
    // Calculate summary statistics
    $summary = [
        'total_activities' => $kegiatan->count(),
        'date_range' => [
            'start' => $kegiatan->min('tanggalmulai'),
            'end' => $kegiatan->max('tanggalselesai')
        ],
        'total_beneficiaries' => $kegiatan->sum('penerimamanfaattotal'),
        'female_beneficiaries' => $kegiatan->sum('penerimamanfaatperempuantotal'),
        'male_beneficiaries' => $kegiatan->sum('penerimamanfaatlakilakitotal'),
        'demographics' => [
            'adults' => [
                'female' => $kegiatan->sum('penerimamanfaatdewasaperempuan'),
                'male' => $kegiatan->sum('penerimamanfaatdewasalakilaki'),
                'total' => $kegiatan->sum('penerimamanfaatdewasatotal')
            ],
            'elderly' => [
                'female' => $kegiatan->sum('penerimamanfaatlansiaperempuan'),
                'male' => $kegiatan->sum('penerimamanfaatlansialakilaki'),
                'total' => $kegiatan->sum('penerimamanfaatlansiatotal')
            ],
            'youth' => [
                'female' => $kegiatan->sum('penerimamanfaatremajaperempuan'),
                'male' => $kegiatan->sum('penerimamanfaatremajalakilaki'),
                'total' => $kegiatan->sum('penerimamanfaatremajatotal')
            ],
            'children' => [
                'female' => $kegiatan->sum('penerimamanfaatanakperempuan'),
                'male' => $kegiatan->sum('penerimamanfaatanaklakilaki'),
                'total' => $kegiatan->sum('penerimamanfaatanaktotal')
            ],
            'disability' => [
                'female' => $kegiatan->sum('penerimamanfaatdisabilitasperempuan'),
                'male' => $kegiatan->sum('penerimamanfaatdisabilitaslakilaki'),
                'total' => $kegiatan->sum('penerimamanfaatdisabilitastotal')
            ],
            'marginalized' => [
                'female' => $kegiatan->sum('penerimamanfaatmarjinalperempuan'),
                'male' => $kegiatan->sum('penerimamanfaatmarjinallakilaki'),
                'total' => $kegiatan->sum('penerimamanfaatmarjinaltotal')
            ]
        ],
        'unique_locations' => $kegiatan->flatMap(function($k) {
            return $k->kegiatanLokasi;
        })->unique('desa_id')->count(),
        'unique_partners' => $kegiatan->flatMap(function($k) {
            return $k->kegiatanMitra;
        })->unique('mitra_id')->count(),
        'activity_types' => $kegiatan->pluck('jeniskegiatan.nama')->unique()->values()
    ];
    
    // Determine report layout based on filters
    $reportLayout = $this->determineReportLayout($activityCodeId, $jeniskegiatanId, $kegiatanId);
    
    // Return view or PDF
    return view('reports.kegiatan', compact('program', 'kegiatan', 'summary', 'reportLayout'));
}

private function determineReportLayout($activityCodeId, $jeniskegiatanId, $kegiatanId)
{
    if ($kegiatanId) {
        return 'single_detail'; // Show full detail of one report
    } elseif ($activityCodeId) {
        return 'grouped_by_activity'; // Group by activity plan
    } elseif ($jeniskegiatanId) {
        return 'grouped_by_type'; // Group by activity type
    } else {
        return 'grouped_by_activity'; // Default: group by activity plan
    }
}
```

### 5. API Controller for Filter Data

```php
// app/Http/Controllers/Api/ActivityCodeController.php

public function index(Request $request)
{
    $programId = $request->input('program_id');
    $search = $request->input('search');
    
    $query = Trprogramoutcomeoutputactivity::query()
        ->select('trprogramoutcomeoutputactivity.*')
        ->join('trprogramoutcomeoutput', 'trprogramoutcomeoutputactivity.programoutcomeoutput_id', '=', 'trprogramoutcomeoutput.id')
        ->join('trprogramoutcome', 'trprogramoutcomeoutput.programoutcome_id', '=', 'trprogramoutcome.id')
        ->where('trprogramoutcome.program_id', $programId);
    
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('trprogramoutcomeoutputactivity.kode', 'LIKE', "%{$search}%")
              ->orWhere('trprogramoutcomeoutputactivity.nama', 'LIKE', "%{$search}%");
        });
    }
    
    return $query->paginate(20);
}

// app/Http/Controllers/Api/ActivityTypeController.php

public function index(Request $request)
{
    $programId = $request->input('program_id');
    $activityCodeId = $request->input('activity_code_id');
    $search = $request->input('search');
    
    $query = Mjeniskegiatan::query()
        ->whereHas('kegiatan', function($q) use ($programId, $activityCodeId) {
            $q->whereHas('programoutcomeoutputactivity.programoutcomeoutput.programoutcome', function($sq) use ($programId) {
                $sq->where('program_id', $programId);
            });
            
            if ($activityCodeId) {
                $q->where('programoutcomeoutputactivity_id', $activityCodeId);
            }
        });
    
    if ($search) {
        $query->where('nama', 'LIKE', "%{$search}%");
    }
    
    return $query->paginate(20);
}
```

---

## 📄 Report View Template (Blade)

```blade
{{-- resources/views/reports/kegiatan.blade.php --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kegiatan - {{ $program->nama }}</title>
    <style>
        @media print {
            .no-print { display: none !important; }
            table { page-break-inside: avoid; }
            .page-break { page-break-before: always; }
            @page { size: A4; margin: 2cm; }
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 20pt;
            margin: 10px 0;
            color: #2c3e50;
        }
        
        .header .subtitle {
            font-size: 12pt;
            color: #7f8c8d;
        }
        
        .info-box {
            background: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .info-box table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-box td {
            padding: 5px;
        }
        
        .info-box td:first-child {
            font-weight: bold;
            width: 150px;
        }
        
        .summary-box {
            background: #3498db;
            color: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        
        .summary-box h2 {
            margin-top: 0;
            border-bottom: 2px solid white;
            padding-bottom: 10px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 15px;
        }
        
        .summary-item {
            background: rgba(255, 255, 255, 0.2);
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        
        .summary-item .value {
            font-size: 24pt;
            font-weight: bold;
            display: block;
        }
        
        .summary-item .label {
            font-size: 9pt;
            opacity: 0.9;
        }
        
        .demographics-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .demographics-table th,
        .demographics-table td {
            border: 1px solid #bdc3c7;
            padding: 10px;
            text-align: center;
        }
        
        .demographics-table th {
            background: #34495e;
            color: white;
            font-weight: bold;
        }
        
        .demographics-table tbody tr:nth-child(even) {
            background: #ecf0f1;
        }
        
        .demographics-table tfoot {
            background: #3498db;
            color: white;
            font-weight: bold;
        }
        
        .activity-card {
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            background: white;
        }
        
        .activity-card h3 {
            color: #2c3e50;
            margin-top: 0;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        .activity-meta {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin: 15px 0;
            font-size: 10pt;
        }
        
        .activity-meta .item {
            background: #ecf0f1;
            padding: 8px;
            border-radius: 3px;
        }
        
        .activity-meta .item strong {
            display: block;
            color: #7f8c8d;
            font-size: 9pt;
        }
        
        .breadcrumb {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            font-size: 9pt;
            margin-bottom: 15px;
            color: #7f8c8d;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9pt;
            font-weight: bold;
        }
        
        .badge-success { background: #27ae60; color: white; }
        .badge-warning { background: #f39c12; color: white; }
        .badge-info { background: #3498db; color: white; }
        
        .group-header {
            background: #2c3e50;
            color: white;
            padding: 15px;
            margin-top: 30px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        
        .group-header h2 {
            margin: 0;
            font-size: 14pt;
        }
        
        .group-header .subtitle {
            font-size: 10pt;
            opacity: 0.8;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    {{-- HEADER SECTION --}}
    <div class="header">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 60px;">
        <h1>LAPORAN KEGIATAN</h1>
        <div class="subtitle">{{ config('app.organization_name', 'IDEP Foundation') }}</div>
    </div>

    {{-- PROGRAM INFO BOX --}}
    <div class="info-box">
        <table>
            <tr>
                <td>Program</td>
                <td>: <strong>{{ $program->nama }}</strong> ({{ $program->kode }})</td>
            </tr>
            <tr>
                <td>Periode Program</td>
                <td>: {{ \Carbon\Carbon::parse($program->tanggalmulai)->isoFormat('D MMMM Y') }} - {{ \Carbon\Carbon::parse($program->tanggalselesai)->isoFormat('D MMMM Y') }}</td>
            </tr>
            @if(request('activity_code_id'))
                <tr>
                    <td>Kode Aktivitas</td>
                    <td>: {{ $kegiatan->first()->programoutcomeoutputactivity->kode ?? '-' }} - {{ $kegiatan->first()->programoutcomeoutputactivity->nama ?? '-' }}</td>
                </tr>
            @endif
            @if(request('jeniskegiatan_id'))
                <tr>
                    <td>Jenis Kegiatan</td>
                    <td>: {{ $kegiatan->first()->jeniskegiatan->nama ?? '-' }}</td>
                </tr>
            @endif
            <tr>
                <td>Tanggal Generate</td>
                <td>: {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y, HH:mm') }} WIB</td>
            </tr>
        </table>
    </div>

    {{-- SUMMARY STATISTICS --}}
    <div class="summary-box">
        <h2>📊 RINGKASAN KEGIATAN</h2>
        
        <div class="summary-grid">
            <div class="summary-item">
                <span class="value">{{ $summary['total_activities'] }}</span>
                <span class="label">Total Kegiatan</span>
            </div>
            <div class="summary-item">
                <span class="value">{{ number_format($summary['total_beneficiaries']) }}</span>
                <span class="label">Total Penerima Manfaat</span>
            </div>
            <div class="summary-item">
                <span class="value">{{ $summary['unique_locations'] }}</span>
                <span class="label">Lokasi Terlibat</span>
            </div>
            <div class="summary-item">
                <span class="value">{{ number_format($summary['female_beneficiaries']) }}</span>
                <span class="label">Penerima Manfaat Perempuan</span>
            </div>
            <div class="summary-item">
                <span class="value">{{ number_format($summary['male_beneficiaries']) }}</span>
                <span class="label">Penerima Manfaat Laki-laki</span>
            </div>
            <div class="summary-item">
                <span class="value">{{ $summary['unique_partners'] }}</span>
                <span class="label">Mitra Terlibat</span>
            </div>
        </div>
        
        @if($summary['date_range']['start'] && $summary['date_range']['end'])
            <p style="margin-top: 15px; text-align: center; font-size: 10pt;">
                <strong>Rentang Waktu Kegiatan:</strong> 
                {{ \Carbon\Carbon::parse($summary['date_range']['start'])->isoFormat('D MMM Y') }} - 
                {{ \Carbon\Carbon::parse($summary['date_range']['end'])->isoFormat('D MMM Y') }}
            </p>
        @endif
    </div>

    {{-- DEMOGRAPHICS TABLE --}}
    <h2 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px;">
        👥 Demografi Penerima Manfaat
    </h2>
    
    <table class="demographics-table">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Perempuan</th>
                <th>Laki-laki</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Dewasa</strong></td>
                <td>{{ number_format($summary['demographics']['adults']['female']) }}</td>
                <td>{{ number_format($summary['demographics']['adults']['male']) }}</td>
                <td><strong>{{ number_format($summary['demographics']['adults']['total']) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Lansia</strong></td>
                <td>{{ number_format($summary['demographics']['elderly']['female']) }}</td>
                <td>{{ number_format($summary['demographics']['elderly']['male']) }}</td>
                <td><strong>{{ number_format($summary['demographics']['elderly']['total']) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Remaja</strong></td>
                <td>{{ number_format($summary['demographics']['youth']['female']) }}</td>
                <td>{{ number_format($summary['demographics']['youth']['male']) }}</td>
                <td><strong>{{ number_format($summary['demographics']['youth']['total']) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Anak-anak</strong></td>
                <td>{{ number_format($summary['demographics']['children']['female']) }}</td>
                <td>{{ number_format($summary['demographics']['children']['male']) }}</td>
                <td><strong>{{ number_format($summary['demographics']['children']['total']) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Disabilitas</strong></td>
                <td>{{ number_format($summary['demographics']['disability']['female']) }}</td>
                <td>{{ number_format($summary['demographics']['disability']['male']) }}</td>
                <td><strong>{{ number_format($summary['demographics']['disability']['total']) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Kelompok Marjinal</strong></td>
                <td>{{ number_format($summary['demographics']['marginalized']['female']) }}</td>
                <td>{{ number_format($summary['demographics']['marginalized']['male']) }}</td>
                <td><strong>{{ number_format($summary['demographics']['marginalized']['total']) }}</strong></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>TOTAL</td>
                <td>{{ number_format($summary['female_beneficiaries']) }}</td>
                <td>{{ number_format($summary['male_beneficiaries']) }}</td>
                <td>{{ number_format($summary['total_beneficiaries']) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="page-break"></div>

    {{-- ACTIVITY LIST SECTION --}}
    <h2 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; margin-top: 30px;">
        📋 Daftar Kegiatan
    </h2>

    @if($reportLayout === 'single_detail')
        {{-- SINGLE DETAIL VIEW --}}
        @include('reports.partials.kegiatan_detail', ['activity' => $kegiatan->first()])
        
    @elseif($reportLayout === 'grouped_by_activity')
        {{-- GROUPED BY ACTIVITY PLAN --}}
        @php
            $groupedByActivity = $kegiatan->groupBy('programoutcomeoutputactivity_id');
        @endphp
        
        @foreach($groupedByActivity as $activityId => $activities)
            @php
                $activityPlan = $activities->first()->programoutcomeoutputactivity;
                $output = $activityPlan->programoutcomeoutput;
                $outcome = $output->programoutcome;
            @endphp
            
            <div class="group-header">
                <h2>{{ $activityPlan->kode }} - {{ $activityPlan->nama }}</h2>
                <div class="subtitle">
                    Output: {{ $output->deskripsi ?? '-' }} | 
                    Outcome: {{ $outcome->deskripsi ?? '-' }}
                </div>
                @if($activityPlan->target)
                    <div class="subtitle">Target: {{ $activityPlan->target }}</div>
                @endif
            </div>
            
            @foreach($activities as $index => $activity)
                @include('reports.partials.kegiatan_card', ['activity' => $activity, 'index' => $index + 1])
                
                @if(($index + 1) % 3 === 0 && !$loop->last)
                    <div class="page-break"></div>
                @endif
            @endforeach
            
            @if(!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
        
    @elseif($reportLayout === 'grouped_by_type')
        {{-- GROUPED BY ACTIVITY TYPE --}}
        @php
            $groupedByType = $kegiatan->groupBy('jeniskegiatan_id');
        @endphp
        
        @foreach($groupedByType as $typeId => $activities)
            @php
                $activityType = $activities->first()->jeniskegiatan;
            @endphp
            
            <div class="group-header">
                <h2>{{ $activityType->nama }}</h2>
                <div class="subtitle">{{ $activities->count() }} kegiatan</div>
            </div>
            
            @foreach($activities as $index => $activity)
                @include('reports.partials.kegiatan_card', ['activity' => $activity, 'index' => $index + 1])
                
                @if(($index + 1) % 3 === 0 && !$loop->last)
                    <div class="page-break"></div>
                @endif
            @endforeach
            
            @if(!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    @endif

    {{-- FOOTER --}}
    <div style="margin-top: 50px; text-align: center; font-size: 9pt; color: #7f8c8d; border-top: 1px solid #bdc3c7; padding-top: 20px;" class="no-print">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem Informasi IDEP</p>
        <p>© {{ date('Y') }} IDEP Foundation</p>
    </div>

    {{-- PRINT BUTTON --}}
    <div class="no-print" style="position: fixed; bottom: 20px; right: 20px;">
        <button onclick="window.print()" style="padding: 15px 30px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 12pt; box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
            🖨️ Cetak / Save PDF
        </button>
    </div>

</body>
</html>
```

---

## 📄 Partial Views

### Kegiatan Card (Compact View)

```blade
{{-- resources/views/reports/partials/kegiatan_card.blade.php --}}

<div class="activity-card">
    <h3>
        <span class="badge badge-info">#{{ $index ?? $activity->id }}</span>
        {{ $activity->jeniskegiatan->nama ?? 'Kegiatan' }}
        <span class="badge badge-{{ $activity->status === 'completed' ? 'success' : 'warning' }}">
            {{ ucfirst($activity->status) }}
        </span>
    </h3>
    
    <div class="breadcrumb">
        📍 {{ $activity->programoutcomeoutputactivity->programoutcomeoutput->programoutcome->program->nama ?? '-' }}
        → {{ $activity->programoutcomeoutputactivity->kode ?? '-' }}
    </div>
    
    <div class="activity-meta">
        <div class="item">
            <strong>📅 Tanggal</strong>
            {{ \Carbon\Carbon::parse($activity->tanggalmulai)->isoFormat('D MMM Y') }} - 
            {{ \Carbon\Carbon::parse($activity->tanggalselesai)->isoFormat('D MMM Y') }}
        </div>
        
        <div class="item">
            <strong>👥 Penerima Manfaat</strong>
            {{ number_format($activity->penerimamanfaattotal ?? 0) }} orang
            ({{ number_format($activity->penerimamanfaatperempuantotal ?? 0) }} P, 
            {{ number_format($activity->penerimamanfaatlakilakitotal ?? 0) }} L)
        </div>
        
        <div class="item">
            <strong>📍 Lokasi</strong>
            @if($activity->kegiatanLokasi->isNotEmpty())
                {{ $activity->kegiatanLokasi->first()->desa->nama ?? '-' }}, 
                {{ $activity->kegiatanLokasi->first()->desa->kecamatan->nama ?? '-' }}
                @if($activity->kegiatanLokasi->count() > 1)
                    <em>(+{{ $activity->kegiatanLokasi->count() - 1 }} lokasi lainnya)</em>
                @endif
            @else
                <em>Tidak ada data lokasi</em>
            @endif
        </div>
        
        <div class="item">
            <strong>🤝 Mitra</strong>
            @if($activity->kegiatanMitra->isNotEmpty())
                {{ $activity->kegiatanMitra->pluck('mitra.nama')->take(2)->implode(', ') }}
                @if($activity->kegiatanMitra->count() > 2)
                    <em>(+{{ $activity->kegiatanMitra->count() - 2 }} mitra lainnya)</em>
                @endif
            @else
                <em>Tidak ada mitra</em>
            @endif
        </div>
    </div>
    
    @if($activity->deskripsilatarbelakang)
        <div style="margin-top: 15px;">
            <strong>Latar Belakang:</strong>
            <p style="font-size: 10pt; margin: 5px 0;">
                {{ Str::limit($activity->deskripsilatarbelakang, 200) }}
            </p>
        </div>
    @endif
    
    @if($activity->deskripsitujuan)
        <div style="margin-top: 10px;">
            <strong>Tujuan:</strong>
            <p style="font-size: 10pt; margin: 5px 0;">
                {{ Str::limit($activity->deskripsitujuan, 200) }}
            </p>
        </div>
    @endif
</div>
```

### Kegiatan Detail (Full View)

```blade
{{-- resources/views/reports/partials/kegiatan_detail.blade.php --}}

<div class="activity-card" style="padding: 25px;">
    
    {{-- BREADCRUMB PATH --}}
    <div class="breadcrumb" style="font-size: 10pt; margin-bottom: 20px;">
        <strong>Hierarki Program:</strong><br>
        📂 {{ $activity->programoutcomeoutputactivity->programoutcomeoutput->programoutcome->program->nama ?? '-' }}
        <br>→ Outcome: {{ $activity->programoutcomeoutputactivity->programoutcomeoutput->programoutcome->deskripsi ?? '-' }}
        <br>→ Output: {{ $activity->programoutcomeoutputactivity->programoutcomeoutput->deskripsi ?? '-' }}
        <br>→ Activity: {{ $activity->programoutcomeoutputactivity->kode ?? '-' }} - {{ $activity->programoutcomeoutputactivity->nama ?? '-' }}
        <br><strong>→ LAPORAN INI</strong>
    </div>
    
    {{-- ACTIVITY HEADER --}}
    <h2 style="color: #2c3e50; margin-top: 0;">
        {{ $activity->jeniskegiatan->nama ?? 'Kegiatan' }}
        <span class="badge badge-{{ $activity->status === 'completed' ? 'success' : 'warning' }}">
            {{ ucfirst($activity->status) }}
        </span>
    </h2>
    
    <div class="info-box">
        <table>
            <tr>
                <td>Tanggal Pelaksanaan</td>
                <td>: {{ \Carbon\Carbon::parse($activity->tanggalmulai)->isoFormat('D MMMM Y') }} - {{ \Carbon\Carbon::parse($activity->tanggalselesai)->isoFormat('D MMMM Y') }}</td>
            </tr>
            <tr>
                <td>Fase Pelaporan</td>
                <td>: Fase {{ $activity->fasepelaporan }}</td>
            </tr>
            <tr>
                <td>Dibuat oleh</td>
                <td>: {{ $activity->user->nama ?? '-' }}</td>
            </tr>
        </table>
    </div>
    
    {{-- DESCRIPTIONS --}}
    @if($activity->deskripsilatarbelakang)
        <h3 style="color: #34495e; margin-top: 25px;">Latar Belakang</h3>
        <p style="text-align: justify;">{{ $activity->deskripsilatarbelakang }}</p>
    @endif
    
    @if($activity->deskripsitujuan)
        <h3 style="color: #34495e; margin-top: 20px;">Tujuan</h3>
        <p style="text-align: justify;">{{ $activity->deskripsitujuan }}</p>
    @endif
    
    @if($activity->deskripsikeluaran)
        <h3 style="color: #34495e; margin-top: 20px;">Keluaran</h3>
        <p style="text-align: justify;">{{ $activity->deskripsikeluaran }}</p>
    @endif
    
    @if($activity->deskripsiyangdikaji)
        <h3 style="color: #34495e; margin-top: 20px;">Yang Dikaji</h3>
        <p style="text-align: justify;">{{ $activity->deskripsiyangdikaji }}</p>
    @endif
    
    {{-- BENEFICIARY DEMOGRAPHICS --}}
    <h3 style="color: #34495e; margin-top: 30px;">Demografi Penerima Manfaat</h3>
    <table class="demographics-table">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Perempuan</th>
                <th>Laki-laki</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Dewasa</strong></td>
                <td>{{ number_format($activity->penerimamanfaatdewasaperempuan ?? 0) }}</td>
                <td>{{ number_format($activity->penerimamanfaatdewasalakilaki ?? 0) }}</td>
                <td><strong>{{ number_format($activity->penerimamanfaatdewasatotal ?? 0) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Lansia</strong></td>
                <td>{{ number_format($activity->penerimamanfaatlansiaperempuan ?? 0) }}</td>
                <td>{{ number_format($activity->penerimamanfaatlansialakilaki ?? 0) }}</td>
                <td><strong>{{ number_format($activity->penerimamanfaatlansiatotal ?? 0) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Remaja</strong></td>
                <td>{{ number_format($activity->penerimamanfaatremajaperempuan ?? 0) }}</td>
                <td>{{ number_format($activity->penerimamanfaatremajalakilaki ?? 0) }}</td>
                <td><strong>{{ number_format($activity->penerimamanfaatremajatotal ?? 0) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Anak-anak</strong></td>
                <td>{{ number_format($activity->penerimamanfaatanakperempuan ?? 0) }}</td>
                <td>{{ number_format($activity->penerimamanfaatanaklakilaki ?? 0) }}</td>
                <td><strong>{{ number_format($activity->penerimamanfaatanaktotal ?? 0) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Disabilitas</strong></td>
                <td>{{ number_format($activity->penerimamanfaatdisabilitasperempuan ?? 0) }}</td>
                <td>{{ number_format($activity->penerimamanfaatdisabilitaslakilaki ?? 0) }}</td>
                <td><strong>{{ number_format($activity->penerimamanfaatdisabilitastotal ?? 0) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Kelompok Marjinal</strong></td>
                <td>{{ number_format($activity->penerimamanfaatmarjinalperempuan ?? 0) }}</td>
                <td>{{ number_format($activity->penerimamanfaatmarjinallakilaki ?? 0) }}</td>
                <td><strong>{{ number_format($activity->penerimamanfaatmarjinaltotal ?? 0) }}</strong></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>TOTAL</td>
                <td>{{ number_format($activity->penerimamanfaatperempuantotal ?? 0) }}</td>
                <td>{{ number_format($activity->penerimamanfaatlakilakitotal ?? 0) }}</td>
                <td>{{ number_format($activity->penerimamanfaattotal ?? 0) }}</td>
            </tr>
        </tfoot>
    </table>
    
    {{-- LOCATIONS --}}
    @if($activity->kegiatanLokasi->isNotEmpty())
        <h3 style="color: #34495e; margin-top: 30px;">Lokasi Kegiatan</h3>
        <table class="demographics-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Desa/Kelurahan</th>
                    <th>Kecamatan</th>
                    <th>Kabupaten/Kota</th>
                    <th>Provinsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activity->kegiatanLokasi as $index => $lokasi)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $lokasi->desa->nama ?? '-' }}</td>
                        <td>{{ $lokasi->desa->kecamatan->nama ?? '-' }}</td>
                        <td>{{ $lokasi->desa->kecamatan->kabupaten->nama ?? '-' }}</td>
                        <td>{{ $lokasi->desa->kecamatan->kabupaten->provinsi->nama ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    
    {{-- PARTNERS --}}
    @if($activity->kegiatanMitra->isNotEmpty())
        <h3 style="color: #34495e; margin-top: 30px;">Mitra yang Terlibat</h3>
        <ul>
            @foreach($activity->kegiatanMitra as $mitra)
                <li>{{ $mitra->mitra->nama ?? '-' }}</li>
            @endforeach
        </ul>
    @endif
    
    {{-- SECTORS --}}
    @if($activity->kegiatanSektor->isNotEmpty())
        <h3 style="color: #34495e; margin-top: 30px;">Sektor/Target Renstra</h3>
        <ul>
            @foreach($activity->kegiatanSektor as $sektor)
                <li>{{ $sektor->sektor->nama ?? '-' }}</li>
            @endforeach
        </ul>
    @endif
    
    {{-- AUTHORS --}}
    @if($activity->kegiatanPenulis->isNotEmpty())
        <h3 style="color: #34495e; margin-top: 30px;">Tim Penulis</h3>
        <table class="demographics-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Peran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activity->kegiatanPenulis as $penulis)
                    <tr>
                        <td>{{ $penulis->user->nama ?? '-' }}</td>
                        <td>{{ $penulis->peran->nama ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    
    {{-- TYPE-SPECIFIC DETAILS --}}
    <div class="page-break"></div>
    
    <h3 style="color: #34495e; margin-top: 30px;">Detail Spesifik Kegiatan</h3>
    
    @php
        $jenisKegiatanId = $activity->jeniskegiatan_id;
    @endphp
    
    {{-- Assessment --}}
    @if($activity->trkegiatanassessment)
        <div class="info-box">
            <h4 style="margin-top: 0;">📋 Detail Assessment</h4>
            
            @if($activity->trkegiatanassessment->assessmentyangterlibat)
                <p><strong>Yang Terlibat:</strong></p>
                <p>{{ $activity->trkegiatanassessment->assessmentyangterlibat }}</p>
            @endif
            
            @if($activity->trkegiatanassessment->assessmenttemuan)
                <p><strong>Temuan:</strong></p>
                <p>{{ $activity->trkegiatanassessment->assessmenttemuan }}</p>
            @endif
            
            @if($activity->trkegiatanassessment->assessmenttambahan)
                <p><strong>Assessment Tambahan:</strong> Ya</p>
                @if($activity->trkegiatanassessment->assessmenttambahan_ket)
                    <p>{{ $activity->trkegiatanassessment->assessmenttambahan_ket }}</p>
                @endif
            @endif
            
            @if($activity->trkegiatanassessment->assessmentkendala)
                <p><strong>Kendala:</strong></p>
                <p>{{ $activity->trkegiatanassessment->assessmentkendala }}</p>
            @endif
            
            @if($activity->trkegiatanassessment->assessmentisu)
                <p><strong>Isu:</strong></p>
                <p>{{ $activity->trkegiatanassessment->assessmentisu }}</p>
            @endif
            
            @if($activity->trkegiatanassessment->assessmentpembelajaran)
                <p><strong>Pembelajaran:</strong></p>
                <p>{{ $activity->trkegiatanassessment->assessmentpembelajaran }}</p>
            @endif
        </div>
    @endif
    
    {{-- Campaign --}}
    @if($activity->trkegiatankampanye)
        <div class="info-box">
            <h4 style="margin-top: 0;">📢 Detail Kampanye</h4>
            
            @if($activity->trkegiatankampanye->kampanyeyangdikampanyekan)
                <p><strong>Yang Dikampanyekan:</strong></p>
                <p>{{ $activity->trkegiatankampanye->kampanyeyangdikampanyekan }}</p>
            @endif
            
            @if($activity->trkegiatankampanye->kampanyejenis)
                <p><strong>Jenis Kampanye:</strong> {{ $activity->trkegiatankampanye->kampanyejenis }}</p>
            @endif
            
            @if($activity->trkegiatankampanye->kampanyebentukkegiatan)
                <p><strong>Bentuk Kegiatan:</strong></p>
                <p>{{ $activity->trkegiatankampanye->kampanyebentukkegiatan }}</p>
            @endif
            
            @if($activity->trkegiatankampanye->kampanyeyangterlibat)
                <p><strong>Yang Terlibat:</strong></p>
                <p>{{ $activity->trkegiatankampanye->kampanyeyangterlibat }}</p>
            @endif
            
            @if($activity->trkegiatankampanye->kampanyeyangdisasar)
                <p><strong>Target Sasaran:</strong></p>
                <p>{{ $activity->trkegiatankampanye->kampanyeyangdisasar }}</p>
            @endif
            
            @if($activity->trkegiatankampanye->kampanyejangkauan)
                <p><strong>Jangkauan:</strong></p>
                <p>{{ $activity->trkegiatankampanye->kampanyejangkauan }}</p>
            @endif
            
            @if($activity->trkegiatankampanye->kampanyerencana)
                <p><strong>Rencana Selanjutnya:</strong></p>
                <p>{{ $activity->trkegiatankampanye->kampanyerencana }}</p>
            @endif
            
            @if($activity->trkegiatankampanye->kampanyekendala)
                <p><strong>Kendala:</strong></p>
                <p>{{ $activity->trkegiatankampanye->kampanyekendala }}</p>
            @endif
            
            @if($activity->trkegiatankampanye->kampanyeisu)
                <p><strong>Isu:</strong></p>
                <p>{{ $activity->trkegiatankampanye->kampanyeisu }}</p>
            @endif
            
            @if($activity->trkegiatankampanye->kampanyepembelajaran)
                <p><strong>Pembelajaran:</strong></p>
                <p>{{ $activity->trkegiatankampanye->kampanyepembelajaran }}</p>
            @endif
        </div>
    @endif
    
    {{-- Training --}}
    @if($activity->trkegiatanpelatihan)
        <div class="info-box">
            <h4 style="margin-top: 0;">🎓 Detail Pelatihan</h4>
            
            @if($activity->trkegiatanpelatihan->pelatihanpelatih)
                <p><strong>Pelatih/Fasilitator:</strong></p>
                <p>{{ $activity->trkegiatanpelatihan->pelatihanpelatih }}</p>
            @endif
            
            @if($activity->trkegiatanpelatihan->pelatihanhasil)
                <p><strong>Hasil Pelatihan:</strong></p>
                <p>{{ $activity->trkegiatanpelatihan->pelatihanhasil }}</p>
            @endif
            
            @if($activity->trkegiatanpelatihan->pelatihandistribusi)
                <p><strong>Material Didistribusikan:</strong> Ya</p>
                @if($activity->trkegiatanpelatihan->pelatihandistribusi_ket)
                    <p>{{ $activity->trkegiatanpelatihan->pelatihandistribusi_ket }}</p>
                @endif
            @endif
            
            @if($activity->trkegiatanpelatihan->pelatihanrencana)
                <p><strong>Rencana Selanjutnya:</strong></p>
                <p>{{ $activity->trkegiatanpelatihan->pelatihanrencana }}</p>
            @endif
            
            @if($activity->trkegiatanpelatihan->pelatihanunggahan)
                <p><strong>File Diunggah:</strong> Ya</p>
            @endif
            
            @if($activity->trkegiatanpelatihan->pelatihanisu)
                <p><strong>Isu:</strong></p>
                <p>{{ $activity->trkegiatanpelatihan->pelatihanisu }}</p>
            @endif
            
            @if($activity->trkegiatanpelatihan->pelatihanpembelajaran)
                <p><strong>Pembelajaran:</strong></p>
                <p>{{ $activity->trkegiatanpelatihan->pelatihanpembelajaran }}</p>
            @endif
        </div>
    @endif
    
    {{-- Consultation --}}
    @if($activity->trkegiatankonsultasi)
        <div class="info-box">
            <h4 style="margin-top: 0;">💬 Detail Konsultasi</h4>
            
            @if($activity->trkegiatankonsultasi->konsultasilembaga)
                <p><strong>Lembaga:</strong></p>
                <p>{{ $activity->trkegiatankonsultasi->konsultasilembaga }}</p>
            @endif
            
            @if($activity->trkegiatankonsultasi->konsultasikomponen)
                <p><strong>Komponen:</strong></p>
                <p>{{ $activity->trkegiatankonsultasi->konsultasikomponen }}</p>
            @endif
            
            @if($activity->trkegiatankonsultasi->konsultasiyangdilakukan)
                <p><strong>Yang Dilakukan:</strong></p>
                <p>{{ $activity->trkegiatankonsultasi->konsultasiyangdilakukan }}</p>
            @endif
            
            @if($activity->trkegiatankonsultasi->konsultasihasil)
                <p><strong>Hasil:</strong></p>
                <p>{{ $activity->trkegiatankonsultasi->konsultasihasil }}</p>
            @endif
            
            @if($activity->trkegiatankonsultasi->konsultasipotensipendapatan)
                <p><strong>Potensi Pendapatan:</strong></p>
                <p>{{ $activity->trkegiatankonsultasi->konsultasipotensipendapatan }}</p>
            @endif
            
            @if($activity->trkegiatankonsultasi->konsultasirencana)
                <p><strong>Rencana:</strong></p>
                <p>{{ $activity->trkegiatankonsultasi->konsultasirencana }}</p>
            @endif
            
            @if($activity->trkegiatankonsultasi->konsultasikendala)
                <p><strong>Kendala:</strong></p>
                <p>{{ $activity->trkegiatankonsultasi->konsultasikendala }}</p>
            @endif
            
            @if($activity->trkegiatankonsultasi->konsultasiisu)
                <p><strong>Isu:</strong></p>
                <p>{{ $activity->trkegiatankonsultasi->konsultasiisu }}</p>
            @endif
            
            @if($activity->trkegiatankonsultasi->konsultasipembelajaran)
                <p><strong>Pembelajaran:</strong></p>
                <p>{{ $activity->trkegiatankonsultasi->konsultasipembelajaran }}</p>
            @endif
        </div>
    @endif
    
    {{-- Monitoring --}}
    @if($activity->trkegiatanmonitoring)
        <div class="info-box">
            <h4 style="margin-top: 0;">🔍 Detail Monitoring</h4>
            
            @if($activity->trkegiatanmonitoring->monitoringyangdipantau)
                <p><strong>Yang Dipantau:</strong></p>
                <p>{{ $activity->trkegiatanmonitoring->monitoringyangdipantau }}</p>
            @endif
            
            @if($activity->trkegiatanmonitoring->monitoringdata)
                <p><strong>Data yang Dikumpulkan:</strong></p>
                <p>{{ $activity->trkegiatanmonitoring->monitoringdata }}</p>
            @endif
            
            @if($activity->trkegiatanmonitoring->monitoringyangterlibat)
                <p><strong>Yang Terlibat:</strong></p>
                <p>{{ $activity->trkegiatanmonitoring->monitoringyangterlibat }}</p>
            @endif
            
            @if($activity->trkegiatanmonitoring->monitoringmetode)
                <p><strong>Metode:</strong></p>
                <p>{{ $activity->trkegiatanmonitoring->monitoringmetode }}</p>
            @endif
            
            @if($activity->trkegiatanmonitoring->monitoringhasil)
                <p><strong>Hasil:</strong></p>
                <p>{{ $activity->trkegiatanmonitoring->monitoringhasil }}</p>
            @endif
            
            @if($activity->trkegiatanmonitoring->monitoringkegiatanselanjutnya)
                <p><strong>Ada Kegiatan Selanjutnya:</strong> Ya</p>
                @if($activity->trkegiatanmonitoring->monitoringkegiatanselanjutnya_ket)
                    <p>{{ $activity->trkegiatanmonitoring->monitoringkegiatanselanjutnya_ket }}</p>
                @endif
            @endif
            
            @if($activity->trkegiatanmonitoring->monitoringkendala)
                <p><strong>Kendala:</strong></p>
                <p>{{ $activity->trkegiatanmonitoring->monitoringkendala }}</p>
            @endif
            
            @if($activity->trkegiatanmonitoring->monitoringisu)
                <p><strong>Isu:</strong></p>
                <p>{{ $activity->trkegiatanmonitoring->monitoringisu }}</p>
            @endif
            
            @if($activity->trkegiatanmonitoring->monitoringpembelajaran)
                <p><strong>Pembelajaran:</strong></p>
                <p>{{ $activity->trkegiatanmonitoring->monitoringpembelajaran }}</p>
            @endif
        </div>
    @endif
    
    {{-- Procurement --}}
    @if($activity->trkegiatanpembelanjaan)
        <div class="info-box">
            <h4 style="margin-top: 0;">🛒 Detail Pembelanjaan</h4>
            
            @if($activity->trkegiatanpembelanjaan->pembelanjaandetailbarang)
                <p><strong>Detail Barang:</strong></p>
                <p>{{ $activity->trkegiatanpembelanjaan->pembelanjaandetailbarang }}</p>
            @endif
            
            @if($activity->trkegiatanpembelanjaan->pembelanjaanmulai && $activity->trkegiatanpembelanjaan->pembelanjaanselesai)
                <p><strong>Periode Pembelanjaan:</strong></p>
                <p>{{ \Carbon\Carbon::parse($activity->trkegiatanpembelanjaan->pembelanjaanmulai)->isoFormat('D MMMM Y') }} - 
                   {{ \Carbon\Carbon::parse($activity->trkegiatanpembelanjaan->pembelanjaanselesai)->isoFormat('D MMMM Y') }}</p>
            @endif
            
            @if($activity->trkegiatanpembelanjaan->pembelanjaandistribusimulai && $activity->trkegiatanpembelanjaan->pembelanjaandistribusiselesai)
                <p><strong>Periode Distribusi:</strong></p>
                <p>{{ \Carbon\Carbon::parse($activity->trkegiatanpembelanjaan->pembelanjaandistribusimulai)->isoFormat('D MMMM Y') }} - 
                   {{ \Carbon\Carbon::parse($activity->trkegiatanpembelanjaan->pembelanjaandistribusiselesai)->isoFormat('D MMMM Y') }}</p>
            @endif
            
            @if($activity->trkegiatanpembelanjaan->pembelanjaanterdistribusi)
                <p><strong>Status Distribusi:</strong> Sudah Terdistribusi</p>
            @endif
            
            @if($activity->trkegiatanpembelanjaan->pembelanjaanakandistribusi)
                <p><strong>Akan Didistribusikan:</strong> Ya</p>
                @if($activity->trkegiatanpembelanjaan->pembelanjaanakandistribusi_ket)
                    <p>{{ $activity->trkegiatanpembelanjaan->pembelanjaanakandistribusi_ket }}</p>
                @endif
            @endif
            
            @if($activity->trkegiatanpembelanjaan->pembelanjaan kendala)
                <p><strong>Kendala:</strong></p>
                <p>{{ $activity->trkegiatanpembelanjaan->pembelanjaankendala }}</p>
            @endif
            
            @if($activity->trkegiatanpembelanjaan->pembelanjaanisu)
                <p><strong>Isu:</strong></p>
                <p>{{ $activity->trkegiatanpembelanjaan->pembelanjaanisu }}</p>
            @endif
            
            @if($activity->trkegiatanpembelanjaan->pembelanjaanpembelajaran)
                <p><strong>Pembelajaran:</strong></p>
                <p>{{ $activity->trkegiatanpembelanjaan->pembelanjaanpembelajaran }}</p>
            @endif
        </div>
    @endif
    
    {{-- Add other activity type-specific details similarly --}}
    {{-- Pemetaan, Pengembangan, Sosialisasi, Kunjungan, Lainnya --}}
    
</div>
```

---

## 🔄 Additional Report Types

### Report Type 2: Program Overview Report

**Filter Parameters:**
- Program ID (required)
- Date Range (optional)

**Output Includes:**
- Program summary
- All outcomes with progress
- All outputs with activities count
- Total beneficiaries across all activities
- Budget utilization
- Partners and donors list
- Geographic coverage map
- Timeline visualization

### Report Type 3: Beneficiary Report (MEALS)

**Filter Parameters:**
- Program ID (required)
- Activity ID (optional)
- Age Group (optional)
- Gender (optional)
- Marginalized Group (optional)
- Location (optional)

**Output Includes:**
- Beneficiary list with demographics
- Group type breakdown
- Activity participation
- Location distribution
- Charts and visualizations

### Report Type 4: Progress Report (MEALS Target Progress)

**Filter Parameters:**
- Program ID (required)
- Date Range (optional)
- Progress Level (Outcome/Output/Activity)

**Output Includes:**
- Progress timeline
- Achievement vs target
- Status summary (On Track / At Risk / Behind)
- Challenges and mitigation
- Risk assessment

---

## 💡 Best Practices for AI Code Generation

### 1. Always Use Eager Loading
```php
// ❌ Bad - N+1 Problem
$kegiatan = Trkegiatan::where('id', $id)->first();
echo $kegiatan->jeniskegiatan->nama; // Additional query

// ✅ Good - Eager Loading
$kegiatan = Trkegiatan::with([
    'jeniskegiatan',
    'kegiatanLokasi.desa.kecamatan.kabupaten.provinsi'
])->where('id', $id)->first();
```

### 2. Use Query Scope for Filtering
```php
// In Trkegiatan Model
public function scopeFilterByProgram($query, $programId)
{
    return $query->whereHas('programoutcomeoutputactivity.programoutcomeoutput.programoutcome', 
        function($q) use ($programId) {
            $q->where('program_id', $programId);
        }
    );
}

// Usage
$kegiatan = Trkegiatan::filterByProgram($programId)->get();
```

### 3. Create Reusable Report Components
```php
// app/Services/ReportService.php
class ReportService
{
    public function calculateBeneficiarySummary($kegiatan)
    {
        return [
            'total' => $kegiatan->sum('penerimamanfaattotal'),
            'female' => $kegiatan->sum('penerimamanfaatperempuantotal'),
            'male' => $kegiatan->sum('penerimamanfaatlakilakitotal'),
            // ... etc
        ];
    }
    
    public function getActivityPath($activity)
    {
        // Returns: Program → Outcome → Output → Activity
    }
}
```

### 4. Handle Empty Data Gracefully
```blade
@if($kegiatan->isEmpty())
    <div class="alert alert-warning">
        <p>Tidak ada data kegiatan yang sesuai dengan filter yang dipilih.</p>
    </div>
@else
    {{-- Show report --}}
@endif
```

### 5. Optimize Large Reports
```php
// Use chunk for large datasets
Trkegiatan::filterByProgram($programId)
    ->chunk(100, function($activities) {
        foreach($activities as $activity) {
            // Process each activity
        }
    });
```

---

## 🎯 Summary for AI Code Tools

When generating reports for IDEP-LTE:

1. **Understand the hierarchy:** Program → Outcome → Output → Activity → Kegiatan
2. **No direct FK between trprogram ↔ trkegiatan** - must join through 4 tables
3. **Use cascading filters** with Select2 AJAX for better UX
4. **Build dynamic queries** based on optional parameters
5. **Group data appropriately** based on selected filters
6. **Always aggregate beneficiary demographics** for summary
7. **Include type-specific details** based on jeniskegiatan_id
8. **Show full context** (breadcrumb path) in detail views
9. **Make reports printable** with proper CSS
10. **Handle empty states** and validation

### Quick Command for AI:
```
"Generate a Laravel Activity Report with:
- Filters: program_id (required), activity_code_id, jeniskegiatan_id, kegiatan_id (all optional)
- Cascading Select2 dropdowns with AJAX
- Dynamic query building based on filters
- Grouped output by Activity Plan
- Summary statistics with demographics table
- Printable PDF layout
- Follow the structure in Report Generation Guide"
```

---

*This comprehensive guide ensures AI code tools can generate accurate, complete, and flexible reports for the IDEP-LTE application.*
