<?php

namespace App\Http\Controllers;
use App\Models\ProcessingStep;
use Illuminate\Http\Request;

class ProcessingStepController extends Controller
{
    /**
     * Display a listing of the processing steps.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $processingSteps = ProcessingStep::latest()->paginate(15);
        return view('admin.setup.processing-steps.index', compact('processingSteps'));
    }

    /**
     * Show the form for creating a new processing step.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.setup.processing-steps.create');
    }

    /**
     * Store a newly created processing step in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate form
        $validator = $request->validate([
            'name' => 'required|string|max:30|unique:processing_steps',
            'processingStepCode' => 'required|string|max:30|unique:processing_steps,code',
            'note' => 'nullable|string|max:255',
        ]);

        // store processing step
        $size = ProcessingStep::create([
            'name' => $request->name,
            'code' => $request->processingStepCode,
            'note' => clean($request->note),
            'status' => $request->status
        ]);
        return redirect()->route('processing-steps.index')->withSuccess('Processing step added successfully!');
    }

    /**
     * Display the specified processing step.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return redirect()->route('processing-steps.index');
    }

    /**
     * Show the form for editing the specified processing step.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $processingStep = ProcessingStep::where('slug', $slug)->first();
        return view('admin.setup.processing-steps.edit', compact('processingStep'));
    }

    /**
     * Update the specified processing step in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $processingStep = ProcessingStep::where('slug', $slug)->first();


        // validate form
        $validator = $request->validate([
            'name' => 'required|string|max:30|unique:processing_steps,name,'.$processingStep->id,
            'processingStepCode' => 'required|string|max:30|unique:processing_steps,code,'.$processingStep->id,
            'note' => 'nullable|string|max:255',
        ]);

        // update processing step
        $processingStep->update([
            'name' => $request->name,
            'code' => $request->processingStepCode,
            'note' => clean($request->note),
            'status' => $request->status
        ]);
        return redirect()->route('processing-steps.index')->withSuccess('Processing step updated successfully!');
    }

    /**
     * Remove the specified processing step from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $processingStep = ProcessingStep::where('slug', $slug)->first();

        // delete processing step
        $processingStep->delete();
        return redirect()->route('processing-steps.index')->withSuccess('Processing step deleted successfully!');
    }

    /**
     * Change the status of specified size.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $processingStep = ProcessingStep::where('slug', $slug)->first();

        // change status
        if($processingStep->status == 1)
        {
            $processingStep->update([
                'status' => 0
            ]);
        }
        else
        {
            $processingStep->update([
                'status' => 1
            ]);
        }
        return redirect()->route('processing-steps.index')->withSuccess('Processing step status changed successfully!');
    }
}
