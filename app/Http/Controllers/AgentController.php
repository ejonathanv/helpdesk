<?php

namespace App\Http\Controllers;

use Mail;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Agent;
use App\Mail\WelcomeMail;
use App\Models\Department;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\UserPermission;
use App\Mail\PasswordChangedMail;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreAgentRequest;
use App\Http\Requests\UpdateAgentRequest;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agents = DB::table('agents')
            ->join('users', 'agents.user_id', '=', 'users.id')
            ->join('departments', 'agents.department_id', '=', 'departments.id')
            ->join('user_permissions', 'users.id', '=', 'user_permissions.user_id')
            ->join('permissions', 'user_permissions.permission_id', '=', 'permissions.id')
            ->select([
                'agents.*', 
                'users.name as user_name',
                'users.email as user_email', 
                'departments.name as department_name',
                'permissions.name as permission_name',
            ])
            ->orderBy('users.name', 'asc')
            ->get()
            ->map(function ($agent) {
                $agent->status_name = $agent->status == 'active' ? 'Activo' : 'Inactivo';
                return $agent;
            });

        return Inertia::render('Agents/Index', [
            'agents' => $agents
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $permissions = Permission::all();
        $departments = DB::table('departments')
            ->select(['id', 'name'])
            ->orderBy('name', 'asc')
            ->get();

        return Inertia::render('Agents/Create', [
            'permissions' => $permissions,
            'departments' => $departments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAgentRequest $request)
    {
        // Creamos el usuario del ingeniero
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = \Hash::make($request->password);
        $user->save();

        // Creamos el perfil del ingeniero
        $agent = new Agent();
        $agent->user_id = $user->id;
        $agent->department_id = $request->department;
        $agent->phone = $request->phone;
        $agent->mobile = $request->mobile;
        $agent->job_title = $request->job_title;
        $agent->save();

        // Vamos a asignar los permisos al usuario
        $permissions = new UserPermission();
        $permissions->user_id = $user->id;
        $permissions->permission_id = $request->permissions;
        $permissions->save();

        // Enviamos un email al ingeniero con sus credenciales
        $details = [
            'title' => 'Bienvenido a HelpDesk - Dynamic Communications',
            'body' => 'Estas son tus credenciales para acceder a la plataforma de tickets',
            'email' => $request->email,
            'password' => $request->password,
        ];

        Mail::to($request->email)->send(new WelcomeMail($details));

        return redirect()->route('agents.show', $agent->id)->with('agentCreated', 'Ingeniero creado correctamente');

    }

    /**
     * Display the specified resource.
     */
    public function show(Agent $agent)
    {
        $permissions = Permission::all();
        $deparments = Department::all();
        $agent = DB::table('agents')
            ->where('agents.id', $agent->id)
            ->join('users', 'agents.user_id', '=', 'users.id')
            ->join('departments', 'agents.department_id', '=', 'departments.id')
            ->join('user_permissions', 'users.id', '=', 'user_permissions.user_id')
            ->join('permissions', 'user_permissions.permission_id', '=', 'permissions.id')
            ->select([
                'agents.*', 
                'users.name as user_name',
                'users.email as user_email', 
                'departments.name as department_name',
                'departments.id as department_id',
                'user_permissions.permission_id as permission_id',
                'permissions.name as permission_name',
            ])
            ->where('agents.id', $agent->id)
            ->first();

        return Inertia::render('Agents/Show', [
            'agent' => $agent,
            'permissions' => $permissions,
            'departments' => $deparments,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agent $agent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAgentRequest $request, Agent $agent)
    {
        //
    }

    public function personal_data(Request $request, Agent $agent){

        $request->validate([
            'user_name' => 'required|string',
            'user_email' => 'required|email|unique:users,email,' . $agent->user->id,
            'department_name' => 'required|exists:departments,name',
            'phone' => 'nullable|string',
            'mobile' => 'nullable|string',
            'job_title' => 'nullable|string',
        ], [
            'user_name.required' => 'El campo nombre es requerido',
            'user_name.string' => 'El campo nombre debe ser una cadena de texto',
            'user_email.required' => 'El campo correo electrónico es requerido',
            'user_email.email' => 'El campo correo electrónico debe ser un correo electrónico válido',
            'department_name.required' => 'El campo departamento es requerido',
            'department_name.exists' => 'El campo departamento debe ser un departamento existente',
            'phone.string' => 'El campo teléfono debe ser una cadena de texto',
            'mobile.string' => 'El campo móvil debe ser una cadena de texto',
            'job_title.string' => 'El campo puesto debe ser una cadena de texto',
        ]);

        $department = Department::where('name', $request->department_name)->first();

        $agent->user->name = $request->user_name;
        $agent->user->email = $request->user_email;
        $agent->user->save();

        $agent->department_id = $department->id;
        $agent->phone = $request->phone;
        $agent->mobile = $request->mobile;
        $agent->job_title = $request->job_title;
        $agent->save();

        return redirect()->back()->with('agentUpdated', 'Datos personales actualizados correctamente');

    }

    public function update_permissions(Request $request, Agent $agent){
    
            $request->validate([
                'permission_id' => 'required|exists:permissions,id',
            ], [
                'permission_id.required' => 'El campo permisos es requerido',
                'permission_id.exists' => 'El campo permisos debe ser un permiso existente',
            ]);
    
            $agent->user->permission->delete();
    
            $permission = new UserPermission();
            $permission->user_id = $agent->user->id;
            $permission->permission_id = $request->permission_id;
            $permission->save();
    
            return redirect()->back()->with('agentPermissionsUpdated', 'Permisos actualizados correctamente');
    }

    public function update_security(Request $request, Agent $agent){
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ], [
            'password.required' => 'El campo contraseña es requerido',
            'password.string' => 'El campo contraseña debe ser una cadena de texto',
            'password.min' => 'El campo contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'El campo contraseña debe ser igual al campo confirmar contraseña',
            'password_confirmation.required' => 'El campo confirmar contraseña es requerido',
            'password_confirmation.string' => 'El campo confirmar contraseña debe ser una cadena de texto',
            'password_confirmation.min' => 'El campo confirmar contraseña debe tener al menos 8 caracteres',
        ]);

        $agent->user->password = \Hash::make($request->password);
        $agent->user->save();

        Mail::to($agent->user->email)->send(new PasswordChangedMail());

        return redirect()->back()->with('agentSecurityUpdated', 'Contraseña actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agent $agent)
    {
        //
    }
}
