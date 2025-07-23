<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project; // Fixed import for Project model

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all(); // Assuming you have a Project model
    
        return view('projects.index', ['projects' => $projects]);
    }
  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
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
  
  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
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
  
  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $project = Project::find($id);
    $project->delete();
    return redirect()->route('projects.index')
      ->with('success', 'Project deleted successfully');
  }
  
  // Route functions
  
  /**
   * Show the form for creating a new project.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('projects.create');
  }
  
  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $project = Project::find($id);
    return view('projects.show', compact('project'));
  }
  
  /**
   * Show the form for editing the specified project.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $project = Project::find($id);
    return view('projects.edit', compact('project'));
  }
  public function about()
  {
      return view('about');
  }
  

  /**
   * Show the contact page.
   *
   * @return \Illuminate\Http\Response
   */
  public function contact()
  {
      // Add logic here if needed
      return view('contact');
  }

  public function home()
    {
        // Add logic here if needed
        return view('home');
    }

}
