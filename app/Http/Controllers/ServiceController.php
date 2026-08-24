<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Service;


class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //all services
        $services = Service::orderBy('id', 'DESC')->get();

        //convert array into json string to display in log file
        Log::info("All services:", ["services" => $services]);

        return view('services.index', compact("services"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //go to the view
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            "name" => 'required|string|max:200',
            "price" => 'required|numeric|min:1'
        ]);

        $created = Service::create($validated);

        if ($created) {
            Log::info("Insert successful");
            return redirect()->route('services.index');
        } else {
            Log::info("Insert Failed");
            return back()->with('error', 'Insert failed!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //get service by id
        return view('services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //get the view and use the values in the db array to populate fields
        return view('services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        //
        $validated = $request->validate([
            "name" => 'required|string|max:200',
            "price" => 'required|numeric|min:1'
        ]);

        $updated = $service->update($validated);

        if ($updated) {
            Log::info("Update successful.");
            return redirect()->route('services.index')->with('success', 'Update successful.');
        }else {
            Log::error("Update failed");
            return back()->with('error','Update failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
