# Gemini CLI Setup Guide for Laravel Project

## Overview
This guide shows how to optimize your Laravel project (`sistem-informasi-idep`) for use with Gemini CLI using markdown context files. Gemini CLI uses hierarchical context management to understand your project structure and provide intelligent assistance.

<!-- ## Installation and Initial Setup -->

<!-- ### 1. Install Gemini CLI
```bash
# Install Gemini CLI globally
npm install -g @google/gemini-cli

# Or use npx
npx @google/gemini-cli

# Authenticate with your Google AI API key
gemini auth login
```

### 2. Initialize in Your Laravel Project
```bash
cd sistem-informasi-idep
gemini init
``` -->

## Context Files Structure

Create a hierarchical context system using `.gemini` files at different levels:

```
sistem-informasi-idep/
├── .gemini/                    # Root context (global project info)
│   ├── context.md
│   ├── laravel-patterns.md
│   ├── database-schema.md
│   └── coding-standards.md
├── app/
│   ├── Http/Controllers/
│   │   └── .gemini/           # Controller-specific context
│   │       └── context.md
│   └── Models/
│       └── .gemini/           # Model-specific context
│           └── context.md
├── resources/
│   ├── views/
│   │   └── .gemini/           # Blade template context
│   │       └── context.md
│   └── js/
│       └── .gemini/           # Frontend JS/jQuery context
│           └── context.md
├── routes/
│   └── .gemini/               # Routing context
│       └── context.md
└── tests/
    └── .gemini/               # Testing context
        └── context.md
```

## Root Context File (`.gemini/context.md`)

```markdown
# IDEP Information System - Project Context

## Project Overview
This is a Laravel-based information management system using Blade templates, jQuery, and AJAX for dynamic user interfaces.

## Technology Stack
- **Backend**: Laravel 9/10 with PHP 8.1+
- **Frontend**: Blade templates, jQuery 3.x, AJAX
- **Database**: MySQL/PostgreSQL
- **Styling**: Bootstrap 5 / Tailwind CSS
- **Build**: Vite

## Development Principles
- Follow Laravel conventions and best practices
- Use RESTful API design for AJAX endpoints
- Implement proper validation on both client and server side
- Maintain clean, readable code with proper documentation
- Use Eloquent ORM for database operations
- Follow MVC architecture strictly

## Code Style Guidelines
- PSR-12 coding standards for PHP
- camelCase for JavaScript variables and functions
- kebab-case for CSS classes
- Meaningful variable and function names
- Comprehensive commenting for complex logic

## Common Patterns Used
- Repository pattern for data access
- Service classes for business logic
- Form requests for validation
- Resource controllers for CRUD operations
- API resources for JSON responses
- jQuery AJAX for form submissions
- DataTables for data grids
- Bootstrap modals for popups

## Security Considerations
- CSRF protection on all forms
- Input validation and sanitization
- SQL injection prevention via Eloquent
- XSS protection in Blade templates
- Authentication via Laravel's built-in system
- Authorization using Gates and Policies

## Performance Optimization
- Database query optimization
- Eager loading for relationships
- Caching where appropriate
- Asset minification and concatenation
- Image optimization
- Lazy loading for large datasets
```

## Laravel-Specific Context (`.gemini/laravel-patterns.md`)

```markdown
# Laravel Development Patterns

## Controller Patterns
- Use Resource Controllers for CRUD operations
- Keep controllers thin, move logic to Services
- Return appropriate HTTP status codes
- Use Form Requests for validation

### Example Controller Pattern
```php
class UserController extends Controller
{
    public function __construct(private UserService $userService) {}
    
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return UserResource::collection(
                $this->userService->paginate($request->all())
            );
        }
        
        return view('users.index');
    }
    
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create($request->validated());
        
        if ($request->wantsJson()) {
            return new UserResource($user);
        }
        
        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }
}
```

## Service Layer Pattern
- Encapsulate business logic in Service classes
- Use dependency injection
- Handle exceptions appropriately
- Return consistent data structures

### Example Service Pattern
```php
class UserService
{
    public function create(array $data): User
    {
        DB::beginTransaction();
        
        try {
            $user = User::create($data);
            
            // Additional business logic here
            event(new UserCreated($user));
            
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollback();
            throw new UserCreationException('Failed to create user: ' . $e->getMessage());
        }
    }
}
```

## Blade Template Patterns
- Use components for reusable UI elements
- Implement proper data binding
- Include CSRF tokens in forms
- Use @auth, @guest directives appropriately

### Example Blade Component
```blade
{{-- components/data-table.blade.php --}}
<div class="table-responsive">
    <table class="table table-striped" id="{{ $tableId }}">
        <thead>
            <tr>
                @foreach($columns as $column)
                    <th>{{ $column['title'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{-- DataTable will populate this --}}
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    $('#{{ $tableId }}').DataTable({
        ajax: '{{ $ajaxUrl }}',
        columns: @json($columns),
        processing: true,
        serverSide: true
    });
});
</script>
```

## jQuery/AJAX Patterns
- Use consistent error handling
- Implement loading states
- Validate forms client-side
- Update UI dynamically

### Example AJAX Pattern
```javascript
function submitForm(formId, successCallback) {
    const form = $(formId);
    const submitBtn = form.find('button[type="submit"]');
    
    form.on('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        submitBtn.prop('disabled', true).text('Processing...');
        
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (successCallback) {
                    successCallback(response);
                }
                showAlert('success', 'Operation completed successfully');
            },
            error: function(xhr) {
                handleAjaxError(xhr);
            },
            complete: function() {
                // Reset button state
                submitBtn.prop('disabled', false).text('Submit');
            }
        });
    });
}
```
```

## Database Schema Context (`.gemini/database-schema.md`)

```markdown
# Database Schema Information

## Core Tables

### users
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user', 'manager') DEFAULT 'user',
    is_active BOOLEAN DEFAULT TRUE,
    last_login_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

**Relationships:**
- Has many: sessions, activities, assigned_tasks
- Belongs to many: roles, permissions

### [Add other tables from your project]

## Model Relationships

### User Model Relationships
```php
class User extends Authenticatable
{
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
    
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }
    
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
```

## Query Patterns
- Always use Eloquent for complex queries
- Implement scopes for common filters
- Use eager loading to prevent N+1 queries
- Index frequently queried columns

### Example Query Patterns
```php
// Efficient user listing with relationships
User::with(['roles', 'latestActivity'])
    ->active()
    ->paginate(15);

// Complex filtering
User::query()
    ->when($search, fn($q) => $q->search($search))
    ->when($role, fn($q) => $q->hasRole($role))
    ->when($active, fn($q) => $q->active())
    ->orderBy('created_at', 'desc')
    ->paginate();
```
```

## Frontend Context (`.gemini/frontend-context.md`)

```
markdown
# Frontend Development Context

## jQuery/AJAX Standards

### Form Handling
```javascript
// Standard form submission pattern
class FormHandler {
    constructor(formSelector) {
        this.form = $(formSelector);
        this.init();
    }
    
    init() {
        this.form.on('submit', (e) => this.handleSubmit(e));
        this.setupValidation();
    }
    
    handleSubmit(e) {
        e.preventDefault();
        
        if (!this.form.valid()) return;
        
        this.showLoading();
        
        $.ajax({
            url: this.form.attr('action'),
            method: this.form.attr('method'),
            data: this.form.serialize(),
            success: (response) => this.handleSuccess(response),
            error: (xhr) => this.handleError(xhr),
            complete: () => this.hideLoading()
        });
    }
    
    setupValidation() {
        this.form.validate({
            errorClass: 'is-invalid',
            validClass: 'is-valid',
            errorElement: 'div',
            errorPlacement: (error, element) => {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            }
        });
    }
}
```

### DataTable Patterns
```javascript
// Standard DataTable configuration
function initDataTable(selector, options = {}) {
    const defaultOptions = {
        processing: true,
        serverSide: true,
        responsive: true,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        language: {
            processing: '<div class="spinner-border text-primary" role="status"></div>'
        },
        drawCallback: function() {
            // Reinitialize tooltips and other components
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    };
    
    return $(selector).DataTable({...defaultOptions, ...options});
}
```

### Modal Management
```javascript
// Modal handling utilities
class ModalManager {
    static show(modalId, data = {}) {
        const modal = $(modalId);
        
        // Populate modal with data
        Object.keys(data).forEach(key => {
            modal.find(`[name="${key}"]`).val(data[key]);
        });
        
        modal.modal('show');
    }
    
    static hide(modalId) {
        $(modalId).modal('hide');
    }
    
    static reset(modalId) {
        const modal = $(modalId);
        modal.find('form')[0].reset();
        modal.find('.is-invalid').removeClass('is-invalid');
        modal.find('.invalid-feedback').remove();
    }
}
```

## CSS/Styling Guidelines
- Use Bootstrap utility classes when possible
- Create custom CSS classes for reusable components
- Follow BEM methodology for complex components
- Ensure responsive design for all components

<!-- 
## Usage Examples

Now you can use Gemini CLI effectively with your Laravel project:

### 1. Basic Usage
```bash
# Navigate to your project
cd sistem-informasi-idep

# Start interactive session
gemini

# Ask questions about your code
> "How can I optimize the user listing page for better performance?"

# Analyze specific files
> "/file app/Http/Controllers/UserController.php"
> "Review this controller and suggest improvements following Laravel best practices"
```

### 2. Code Generation
```bash
# Generate a new feature
> "Create a new Task management feature with CRUD operations, including:
  - Task model with relationships to User
  - TaskController with API endpoints
  - Blade views with DataTable
  - jQuery/AJAX form handling
  Follow the patterns established in the project"
```

### 3. Bug Fixes
```bash
# Debug issues
> "The user registration form is not validating properly on the frontend. 
  Check the validation logic and fix any issues."

# With file injection
> "/file resources/views/auth/register.blade.php"
> "/file public/js/auth.js"
> "Fix the validation issues in these files"
```

### 4. Testing
```bash
# Generate tests
> "Create comprehensive tests for the UserService class, including:
  - Unit tests for all methods
  - Integration tests for database operations  
  - Mock external dependencies
  Follow Laravel testing best practices"
```

### 5. Documentation
```bash
# Generate documentation
> "Create API documentation for all the AJAX endpoints in the project.
  Include request/response examples and error codes."
```

## Advanced Tips

### 1. Project-Specific Commands
Create aliases in your shell for common tasks:

```bash
# In ~/.bashrc or ~/.zshrc
alias gemini-review="gemini 'Review the current changes and suggest improvements'"
alias gemini-test="gemini 'Generate tests for the modified files'"
alias gemini-doc="gemini 'Update documentation for the changes made'"
```

### 2. Git Integration
Gemini CLI has built-in git awareness:

```bash
# Review staged changes
> "Review my staged changes and suggest improvements"

# Generate commit messages
> "Generate a conventional commit message for my staged changes"

# Code review
> "Perform a code review on the last 3 commits"
```

### 3. Context File Updates
Keep your context files updated as your project evolves:

```bash
# Update schema documentation when migrations change
> "Update the database schema documentation based on recent migrations"

# Refresh patterns when you introduce new coding patterns
> "Document the new Service pattern I introduced in the recent commits"
``` -->

<!-- ## Best Practices

1. **Keep Context Current**: Regularly update your context files as your project evolves
2. **Be Specific**: Use detailed context files for different areas of your application
3. **Include Examples**: Always provide code examples in your context files
4. **Document Patterns**: Document your specific coding patterns and conventions
5. **Regular Reviews**: Use Gemini CLI for regular code reviews and refactoring suggestions

This setup will give Gemini CLI deep understanding of your Laravel project structure, enabling it to provide highly relevant and contextual assistance for development tasks. -->