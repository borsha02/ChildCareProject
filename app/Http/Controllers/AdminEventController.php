<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('start_time', 'desc')->get();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'location' => 'nullable|string|max:255',
            'category' => 'required|string',
            'audience' => 'required|string',
            'capacity' => 'nullable|integer|min:1',
        ]);

        $validated['created_by'] = Auth::id();

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'location' => 'nullable|string|max:255',
            'category' => 'required|string',
            'audience' => 'required|string',
            'capacity' => 'nullable|integer|min:1',
        ]);

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }

    public function calendar()
    {
        $events = Event::all();
        return view('admin.events.calendar', compact('events'));
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'events' => 'required|array',
            'events.*.title' => 'required|string',
            'events.*.start' => 'required|date',
            'events.*.type' => 'required|string', // holiday, etc
        ]);

        foreach ($request->events as $eventData) {
            Event::create([
                'title' => $eventData['title'],
                'start_time' => $eventData['start'] . ' 00:00:00',
                'end_time' => $eventData['start'] . ' 23:59:59',
                'category' => 'holiday', // default for bulk import, or use from data
                'audience' => 'all',
                'created_by' => Auth::id(),
                'description' => 'Imported Holiday',
                'location' => 'N/A'
            ]);
        }

        return response()->json(['success' => true]);
    }
    
}   