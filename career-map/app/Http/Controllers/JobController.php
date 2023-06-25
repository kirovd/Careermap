<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    public function create()
    {
        return view('create-job');
    }

    public function store(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'title' => 'required',
                'description' => 'required',
            ]);

            // Create a new job
            $job = new Job;
            $job->title = $request->input('title');
            $job->description = $request->input('description');
            $job->save();

            // Return the newly created job's details
            return response()->json(['message' => 'Job created successfully', 'job' => $job]);

        } catch (Exception $e) {
            // Handle the exception
            return response()->json(['message' => 'Error creating job'], 500);
        }
    }

    public function index()
    {
        $jobs = Job::orderBy('created_at', 'desc')->get(['title', 'created_at', 'description']);

        return response()->json(['jobs' => $jobs]);
    }

    public function show($id)
    {
        $job = Job::findOrFail($id);
        return view('job.show', compact('job'));
    }


    public function accept(Request $request, $id)
    {
        $jobIds = explode(',', $id);
        try {
            $jobs = Job::whereIn('id', $jobIds)->get();

            foreach ($jobs as $job) {
                $job->status = 'Accepted';
                $job->save();
            }

            // Save the changes to the database
            DB::beginTransaction();
            try {
                foreach ($jobs as $job) {
                    $job->save();
                }
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }

            return response()->json(['message' => 'Jobs accepted successfully', 'status' => 'Accepted']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error accepting the jobs: ' . $e->getMessage()], 500);
        }
    }

























}
