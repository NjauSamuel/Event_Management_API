<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AttendeeController extends Controller
{
    use CanLoadRelationships, AuthorizesRequests;

    private array $relations = ['user'];

    public function index(Request $request, Event $event)
    {
        // Authorize the viewAny action
        $this->authorize('viewAny', [Attendee::class, $request->user()]);

        $attendees = $this->loadRelationships($event->attendees()->latest());

        return AttendeeResource::collection($attendees->paginate());
    }

    public function store(Request $request, Event $event)
    {
        // Authorize the create action
        $this->authorize('create', [Attendee::class, $request->user()]);

        $attendee = $this->loadRelationships(
            $event->attendees()->create([
                'user_id' => $request->user()->id
            ])
        );

        return new AttendeeResource($attendee);
    }

    public function show(Event $event, Attendee $attendee)
    {
        // Authorize the view action
        $this->authorize('view', $attendee->event);

        return new AttendeeResource($this->loadRelationships($attendee));
    }

    public function destroy(Request $request, Event $event, Attendee $attendee)
    {
        // Authorize the update action
        $this->authorize('update', [$event, $request->user()]);

        $attendee->delete();

        return response(status: 204);
    }
}
