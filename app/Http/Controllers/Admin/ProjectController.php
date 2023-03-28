<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

//Models
use App\Models\Project;
use App\Models\Technology;
use App\Models\Type;

// Helpers
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        return view('admin.projects.index', [
            'projects'=> Project::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::all();
        $technologies = Technology::all();

        return view('admin.projects.create', [
            'types'=>$types,
            'technologies'=>$technologies,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        $data = $request->validate();

        if(array_key_exists('img', $data)) {
            $imgPath = Storage::put('projects', $data['img']);
            $data['img'] = $imgPath;
        }

        $newProject = Project::create($data);

        if(array_key_exists('technologies', $data)) {
            foreach ($data['technologies'] as $techId) {
                $newProject->technologies()->sync($data['technologies']);
            }
        }

        return redirect()->route('admin.projects.show', $newProject->id)->with('success', 'New project created correctly');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', [
            'project'=> $project
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $types = Type::all();
        $technologies = Technology::all();

        return view('admin.projects.edit', [
            'project'=> $project,
            'types'=> $types,
            'technologies'=> $technologies,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $data = $request->validate();

        if(array_key_exists('img', $data)) {
            $imgPath = Storage::put('projects', $data['img']);
            $data['img'] = $imgPath;

            if($project->img) {
                Storage::delete($project->img);
            }
        }

        $project->update($data);

        if(array_key_exists('technologies', $data)) {
            $project->technologies()->sync($data['technologies']);
        }
        else {
            $project->technologies()->detach();
        }

        return redirect()->route('admin.projects.show', $project->id)->with('success', 'Project update correctly');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        Storage::delete($project->img);

        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Project deleted correctly');
    }
}