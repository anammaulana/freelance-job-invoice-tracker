<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::with('client')->latest()->paginate(10);

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        return view('projects.create', [
            'clients' => Client::orderBy('name')->get(),
            'statuses' => Project::STATUSES,
        ]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = Project::create($request->validated());

        return redirect()->route('projects.show', $project)->with('status', 'Project berhasil dibuat.');
    }

    public function show(Project $project): View
    {
        $project->load([
            'client',
            'milestones' => fn ($query) => $query->orderBy('target_date'),
            'milestones.tasks',
            'documents' => fn ($query) => $query->with('uploadedBy')->latest(),
            'tasks' => fn ($query) => $query->with(['milestone', 'documents.uploadedBy'])->orderBy('due_date')->latest(),
            'activities' => fn ($query) => $query->with('user')->latest()->limit(10),
        ]);

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        return view('projects.edit', [
            'project' => $project,
            'clients' => Client::orderBy('name')->get(),
            'statuses' => Project::STATUSES,
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $project->update($request->validated());

        return redirect()->route('projects.show', $project)->with('status', 'Project berhasil diperbarui.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('projects.index')->with('status', 'Project berhasil dihapus.');
    }
}
