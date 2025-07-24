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