## Project Context
I'm building a Laravel 10 application with MVC architecture using Blade templates and AJAX for a MEALS (Monitoring, Evaluation, Accountability, Learning, Sustainability) program management system. The system manages component models, beneficiaries, geographic locations, and program progress tracking.

## Database Structure
The core table is `trmeals_komponen_model` which connects to:
- `trprogram` (programs)
- `mkomponenmodel` (component model types)
- `trmeals_komponen_model_lokasi` (geographic locations)
- `trmeals_komponen_model_targetreinstra` (strategic targets)
- `trmeals_penerima_manfaat` (beneficiaries)
- Geographic hierarchy: `provinsi` → `kabupaten` → `kecamatan` → `kelurahan` → `dusun`
- Progress tracking: `trmeals_target_progress` and related tables

## Required Dashboard Components

### 1. Executive Overview Dashboard
Create a comprehensive dashboard with:
- KPI cards showing total components, programs, geographic coverage, beneficiaries
- Program distribution pie chart
- Geographic heat map of component density
- Timeline chart of component deployment
- Progress completion rates

### 2. Geographic Distribution Dashboard
Build an interactive map system with:
- Leaflet/Google Maps integration showing component locations
- Province-level summary with drill-down capability
- District/regency view with clustering
- Village-level detail display
- Location-based filtering and search

### 3. Component Model Analysis Dashboard
Develop analytics for:
- Component type breakdown charts
- Quantity analysis across models
- Utilization rate tracking
- Target vs actual achievement metrics
- User assignment distribution

### 4. Beneficiary Impact Dashboard
Implement beneficiary tracking with:
- Demographic breakdowns (gender, age, vulnerability groups)
- Direct vs indirect beneficiary counts
- Program participation rates
- Pre/post assessment tracking
- Family structure analysis

### 5. Program Integration View
Create hierarchical program display:
- Program-Outcome-Output-Activity tree navigation
- Component allocation visualization
- Cross-program resource analysis
- Budget distribution tracking

### 6. Operational Management Dashboard
Build management tools for:
- User assignment matrix
- Data quality monitoring
- Update activity tracking
- Error reporting system

## Technical Requirements

### Laravel Structure
- Use proper MVC with Controllers, Models, and Blade views
- Implement Repository pattern for data access
- Use Eloquent relationships for efficient queries
- Create API endpoints for AJAX calls
- Implement proper validation and error handling

### Frontend Requirements
- Responsive design using Bootstrap 5 or Tailwind CSS
- Interactive charts using Chart.js or ApexCharts
- Map integration with Leaflet or Google Maps
- DataTables for tabular data with server-side processing
- AJAX-powered filtering and real-time updates
- Export functionality (PDF, Excel)

### Key Features to Implement
1. **Real-time Data Updates**: Auto-refresh mechanisms
2. **Advanced Filtering**: Multi-level geographic and program filters
3. **Export Capabilities**: PDF reports and Excel downloads
4. **User Permissions**: Role-based access control
5. **Mobile Responsive**: Full mobile compatibility
6. **Performance Optimization**: Efficient queries and caching

### Database Relationships to Implement
```php
// Example Model relationships needed
class TrmealsKomponenModel extends Model {
    public function program() { return $this->belongsTo(Trprogram::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function komponenModel() { return $this->belongsTo(Mkomponenmodel::class); }
    public function lokasi() { return $this->hasMany(TrmealsKomponenModelLokasi::class); }
    public function targetReinstra() { return $this->hasMany(TrmealsKomponenModelTargetreinstra::class); }
}
```

### Sample Queries Needed
- Component summary by program with totals
- Geographic distribution with location hierarchy
- Beneficiary demographics and impact metrics
- Progress tracking with percentage completion
- User workload and assignment analysis

## Deliverables Expected
1. **Controllers**: Dashboard controllers with proper data aggregation
2. **Models**: Eloquent models with relationships
3. **Views**: Blade templates with responsive layouts
4. **Routes**: API and web routes for AJAX functionality
5. **JavaScript**: Frontend interaction and chart generation
6. **Migrations**: Database structure if modifications needed
7. **Seeders**: Sample data for testing

## Code Style Preferences
- Follow Laravel conventions and PSR standards
- Use meaningful variable and method names
- Include comprehensive comments
- Implement proper error handling
- Use dependency injection where appropriate
- Follow SOLID principles

## Additional Considerations
- Implement caching for performance
- Add logging for debugging
- Create artisan commands for data processing
- Include unit tests for critical functionality
- Prepare for internationalization if needed

Please create a complete Laravel 10 application structure that implements these dashboard requirements using MVC architecture, Blade templates, and AJAX for dynamic interactions. Focus on clean, maintainable code with proper separation of concerns.

now using the @project\resources\views\dashboard\komodel-v4\complete-template.php to build v5-of komodel dashboard, 

I've completed the comprehensive MEALS Dashboard using AdminLTE3. Here's what the template includes:

## Key Features Implemented:

**1. Six Main Dashboard Sections:**
- **Executive Overview** - KPI cards, charts, and progress tables
- **Geographic Distribution** - Interactive map with location analytics
- **Component Analysis** - Type distribution and utilization charts
- **Beneficiary Impact** - Demographics and participation tracking
- **Program Integration** - Hierarchical program structure
- **Operations Management** - User assignments and data quality

**2. Interactive Elements:**
- Tab navigation system
- Real-time data refresh functionality
- Advanced filtering (program, location, date range)
- Export capabilities
- Responsive design for mobile devices

**3. Chart Implementations:**
- Doughnut charts for program distribution
- Line charts for deployment timelines
- Bar charts for component analysis
- Radar charts for performance metrics
- Pie charts for demographic breakdowns

**4. Data Visualization:**
- Interactive Leaflet maps with component markers
- Progress bars and status indicators
- DataTables with server-side processing capability
- Color-coded status badges

**5. Technical Structure:**
- Clean separation of concerns
- AJAX endpoints ready for Laravel integration
- Proper error handling and loading states
- Auto-refresh functionality
- Mobile-responsive layout

The template is ready to integrate with your Laravel 10 backend. You'll need to create corresponding API endpoints that return the data in the format expected by the JavaScript functions. The structure follows AdminLTE3 conventions and includes all the dashboard components I analyzed from your database structure.
