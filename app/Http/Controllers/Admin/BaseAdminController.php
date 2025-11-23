<?php
// app/Http/Controllers/Admin/BaseAdminController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class BaseAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || auth()->user()->role !== 'administrador') {
                abort(403);
            }
            return $next($request);
        });
    }
}
