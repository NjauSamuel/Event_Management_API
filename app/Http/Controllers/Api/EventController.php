<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EventController extends Controller
{
    use CanLoadRelationships, AuthorizesRequests;
    
    private  array $relations = ['user', 'attendees', 'attendees.user'];
    
    public function index(Request $request)
    {
        // Authorize the viewAny action
        $this->authorize('viewAny', [Event::class, $request->user()]);

        //$relations = ['user', 'attendees', 'attendees.user'];

        $query = $this->loadRelationships(Event::query());

        return EventResource::collection($query->latest()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Authorize the create action
        $this->authorize('create', [Event::class, $request->user()]);
        
        $event = Event::create([
            ...$request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1800',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
            ]),

            'user_id' => $request->user()->id
        ]);

        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // Authorize the view action
        $this->authorize('view', $event);

        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        // Authorize the update action
        $this->authorize('update', [$event, $request->user()]);

        $event->update(
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string|max:1800',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time',
            ])
        );

        return new EventResource($this->loadRelationships($event));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Event $event)
    {
        // Authorize the delete action
        $this->authorize('delete', [$event, $request->user()]);

        $event->delete();

        return response(status: 204);
    }
}
