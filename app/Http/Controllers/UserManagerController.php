<?php

namespace App\Http\Controllers;

use App\Repositories\UserManager\UserManagerRepository;
use Illuminate\Http\Request;

class UserManagerController extends Controller
{
    private UserManagerRepository $userManagerRepository;

    public function __construct(UserManagerRepository $userManagerRepository)
    {
        $this->userManagerRepository = $userManagerRepository;
    }
    
    public function index(Request $request)
    {
        $key = $request->input('key');
        return $this->userManagerRepository->index($key);
    }
 
    public function show($id)
    {
        return $this->userManagerRepository->show($id);
    }

    public function update(Request $request, $id)
    {
        $this->userManagerRepository->update($request, $id);
        return redirect()->route('user-managers.index')
                ->with('success', 'User updated successfully.');
    }
}
