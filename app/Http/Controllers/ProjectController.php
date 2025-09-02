<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project; 

class ProjectController extends Controller
{

    public function index()
    {
        $projects = Project::all();
    
        return view('projects.index', ['projects' => $projects]);
    }

  public function store(Request $request)
  {
    $request->validate([
      'title' => 'required|max:255',
      'body' => 'required',
    ]);
    Project::create($request->all());
    return redirect()->route('projects.index')
      ->with('success', 'Project created successfully.');
  }
  
  public function update(Request $request, $id)
  {
    $request->validate([
      'title' => 'required|max:255',
      'body' => 'required',
    ]);
    $project = Project::find($id);
    $project->update($request->all());
    return redirect()->route('projects.index')
      ->with('success', 'Project updated successfully.');
  }

  public function destroy($id)
  {
    $project = Project::find($id);
    $project->delete();
    return redirect()->route('projects.index')
      ->with('success', 'Project deleted successfully');
  }
  
  public function create()
  {
    return view('projects.create');
  }
  
  public function show($id)
  {
    $project = Project::find($id);
    return view('projects.show', compact('project'));
  }
 
  public function edit($id)
  {
    $project = Project::find($id);
    return view('projects.edit', compact('project'));
  }
  public function about()
  {
      return view('about');
  }
  

  public function contact()
  {

      return view('contact');
  }

  public function home()
    {

        return view('home');
    }

}
