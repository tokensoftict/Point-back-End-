<?php

namespace Modules\Auth\Http\Controllers\AccessControl;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\Module;
use Modules\Auth\Entities\User;
use Modules\Auth\Entities\Usergroup;
use Modules\Auth\Http\Requests\GroupUserRequest;
use Modules\Auth\Transformers\UserGroupListResource;
use Illuminate\Support\Facades\Cache;

class GroupController extends Controller
{
    use RespondsWithHttpStatus;

    public function index()
    {
        return $this->success("Data Fetched", UserGroupListResource::collection(Usergroup::all()));
    }


    public function store(GroupUserRequest $request)
    {
        return $this->success("Data fetched",new UserGroupListResource( Usergroup::create($request->only(['name']))));
    }

    public function show(Usergroup $usergroup)
    {
        return $this->success("Data fetched",new UserGroupListResource($usergroup));
    }

    public function update(GroupUserRequest $request, Usergroup $usergroup)
    {

        $usergroup->update($request->only(['name']));

        return $this->success("Data fetched",new UserGroupListResource($usergroup));
    }


    public function toggle( Usergroup $usergroup)
    {
        $this->toggleState($usergroup);

        return $this->success("Data fetched",new UserGroupListResource($usergroup));
    }


    private  function assignGroupPrivileges($group,$data){

        $values = json_decode($data['privileges'],true);

        foreach ($group->users as $user)
        {
            Cache::forget("'route-task-permission-".$user->id);
            loadUserMenu($user->id); // refresh cache for all users under the group.
        }
        return $group->group_tasks()->sync($values);
    }

    public function permission(Request $request, Usergroup $usergroup)
    {
        if ($request->isMethod('post')) {
            $postData = $request->all();
            if (!empty($postData['privileges'])) {
                $grpassign = $this->assignGroupPrivileges($usergroup,$postData);
                if($grpassign) return $this->success("Saved",['status'=>true]);
            }
        }

        $group =  $usergroup->load('users');

        $modules = Module::where('status', '=', '1')
            ->with(['tasks','tasks.permissions' => function ($q) use ($usergroup) {
                $q->where('usergroup_id', '=', $usergroup->id);
            }])
            ->get(['id', 'name','label' ,'icon']);

        $data['modules'] = $modules;
        $data['group'] = $group;

        return $this->success("Data fetched",['status'=>true,"message"=>"","data"=>$data]);
    }

}
