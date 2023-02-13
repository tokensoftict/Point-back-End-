<?php

namespace Modules\Auth\Http\Controllers\AccessControl;

use App\Traits\RespondsWithHttpStatus;

use Illuminate\Routing\Controller;
use Modules\Auth\Entities\User;
use Modules\Auth\Http\Requests\UserRequest;
use Modules\Auth\Transformers\AuthCollection;
use Modules\Settings\Entities\Branch;
use Modules\Settings\Entities\Userbranchesmapper;

class UserController extends Controller
{
    use RespondsWithHttpStatus;

    public function index()
    {
        $branches = \Arr::pluck(auth()->user()->branches,'id');
        $users = Userbranchesmapper::whereIn('branch_id', $branches)->get()->pluck('id');
        return $this->success("Data Fetched", AuthCollection::collection(User::whereIn('id',$users)->get()));
    }

    public function store(UserRequest $request)
    {
        if(empty($request->get('password'))){
            $request->request->set('password',bcrypt('123456'));
        }
        else
        {
            $request->request->set('password',bcrypt($request->get('password')));
        }
        return $this->success("Data Fetched", new AuthCollection(User::create($request->only([
            'name','username','usergroup_id','username','email','phone','password',
        ]))));
    }

    public function update(UserRequest $request, User $user)
    {
        if(empty($request->get('password'))){
            $request->request->remove('password');
        }
        else
        {
            $request->request->set('password',bcrypt($request->get('password')));
        }

        $user->update($request->only([ 'name','username','usergroup_id','username','email','phone','password',]));

        $user->updateUserBranches();

        return $this->success("Data Fetched", new AuthCollection($user));
    }

    public function toggle(User $user)
    {
        $this->toggleState($user);
        return $this->success("Data Fetched", new AuthCollection($user));
    }
}
