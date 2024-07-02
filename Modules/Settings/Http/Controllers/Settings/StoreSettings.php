<?php


namespace Modules\Settings\Http\Controllers\Settings;

use App\Classes\Settings;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Modules\Auth\Entities\Module;
use Modules\Auth\Entities\Permission;
use Modules\Auth\Entities\Task;
use Modules\Settings\Entities\Branch;
use Modules\Settings\Http\Requests\StoreSettingsRequest;
use Modules\Settings\Transformers\SettingsResources;


class StoreSettings extends Controller
{
    use RespondsWithHttpStatus;

    public function show(Settings $settings): JsonResponse
    {

        $store = $settings->all();

        if (!$store) $store = [];

        return $this->success("Data fetched", new SettingsResources(collect($store)));
    }


    public function update(Settings $settings, Request $request): JsonResponse
    {

        $store = $this->settings->all();

        $file = $request->file('logo');

        $data = $request->except(['_token','_method']);

        if ($file) {
            $imageName = time().'.'. $request->logo->getClientOriginalExtension();

            $request->logo->move(public_path('img'), $imageName);

            $data['logo'] = $imageName;


            if(!empty($store->logo)) {
                @unlink(public_path('img/' . $store->logo));
            }
        }

        $this->settings->put($data);

        $branch = Branch::find(1);

        $branch->address_1 = $data['address_1'];
        $branch->address_2 = $data['address_2'];
        $branch->phone = $data['contact_number'];

        $branch->update();

        return $this->success("Data fetched", $store);
    }

    public function syncTask(Request $request)
    {
        $routes = json_decode($request->get('routes'),true);

        foreach($routes as $route)
        {
            $module =  Module::updateOrCreate(
                [
                    'name'=>$route['moduleName']
                ],
                [
                    'name' => $route['moduleName'],
                    'label' => $route['moduleName'],
                    'description' => $route['moduleName'],
                    'status' => 1,
                    'order' => 0,
                    'icon' => 'fa fa-circle-o'
                ]
            );

            foreach ($route['children'] as $children)
            {
               $task = Task::updateOrCreate(
                    [
                        'name' =>$children['name'],
                    ],
                    [
                        'module_id' => $module->id,
                        'parent_task_id' => 0,
                        'route' =>$children['path'],
                        'name' =>$children['name'],
                        'description' =>  $children['title'],
                        'status' => 1,
                        'order' => 1,
                        'icon' => NULL
                    ]
                );

               Permission::updateOrCreate(
                   [
                       'usergroup_id' => 1,
                       'task_id' => $task->id
                   ],
                   [
                       'usergroup_id' => 1,
                       'task_id' => $task->id
                   ]
               );
            }

            $route_names = \Arr::pluck($route['children'],'name');

            $module->tasks()->whereNotIn('name',$route_names)->delete();
        }
        $moduleNames = \Arr::pluck($routes,'moduleName');

        Module::whereNotIn('name', $moduleNames)->delete();

        return $this->success("synced",['status'=>true]);
    }
}
