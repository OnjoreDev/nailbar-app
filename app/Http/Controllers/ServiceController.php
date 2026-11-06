<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        $merchant = Auth::user()->merchant;
        $services = Service::where('merchant_id', $merchant->id)->latest()->get();

        return view('merchant.services.index', compact('services'));
    }

    public function create()
    {
        return view('merchant.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('services', 'public');
        }

        Service::create([
            'merchant_id' => Auth::user()->merchant->id,
            'name' => $request->name,
            'price' => $request->price,
            'image' => $imagePath,
            'description' => $request->description,
        ]);

        return redirect()->route('merchant.services.index')->with('success', 'Service added successfully.');
    }

    public function edit(Service $service)
    {
        // Ensure service belongs to authenticated merchant
        abort_if($service->merchant_id !== Auth::user()->merchant->id, 403);

        return view('merchant.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        abort_if($service->merchant_id !== Auth::user()->merchant->id, 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $service->image = $request->file('image')->store('services', 'public');
        }

        $service->update([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $service->image,
            'description' => $request->description,
        ]);

        return redirect()->route('merchant.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        abort_if($service->merchant_id !== Auth::user()->merchant->id, 403);

        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }
        $service->delete();

        return redirect()->route('merchant.services.index')->with('success', 'Service deleted successfully.');
    }
}
