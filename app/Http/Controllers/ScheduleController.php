<?php

namespace App\Http\Controllers;

use App\Events\AppointmentNotification;
use App\Models\DoctorProfile;
use App\Models\Schedule;
use App\Notifications\DoctorScheduleNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    use Notifiable;
    function index()
    {
        if (Auth::user()->isPatient()) {
            // return view('.index');
        };
        return view('schedule.index');
    }

    function store(Request $request)
    {
        $validate = $request->validate([
            'doctor_id' => 'required|exists:doctor_profiles,id',
            'title' => 'string|required',
            'start' => 'after:now|required|date',
            'end' => 'after:start|required|date',
            'color' => 'nullable|string'
        ]);

        $user = Auth::user();
        // Assuming the creator is a patient. If doctor creates, logic might differ.
        $patientProfileId = $user->patientProfile->id ?? null;

        // Map request doctor_id to doctor_profile_id column
        $validate['doctor_profile_id'] = $request->doctor_id;
        $validate['patient_profile_id'] = $patientProfileId;

        $validate['start'] = Carbon::parse($validate['start'])->format('Y-m-d H:i:s');
        if (!empty($validate['end'])) {
            $validate['end'] = Carbon::parse($validate['end'])->format('Y-m-d H:i:s');
        } else {
            $validate['end'] = null;
        }

        $item = Schedule::create($validate);

        $patientName = $user->full_name;
        $doctorProfile = DoctorProfile::with('user')->find($request->doctor_id);

        if ($doctorProfile) {
            $doctorProfile->user->notify(new DoctorScheduleNotification('created', $item->toArray(), $patientName));
            
            // Broadcast to the doctor's user ID
            event(new AppointmentNotification("New appointment: {$item->title} by {$patientName}", $doctorProfile->user_id));
            
            // Broadcast to the patient's user ID
            if ($item->patient && $item->patient->user_id) {
                event(new AppointmentNotification("Appointment confirmed: {$item->title} with Dr. {$doctorProfile->user->last_name}", $item->patient->user_id));
            }
        }

        return response()->json([
            'message' => 'Schedule created successfully',
            'schedule' => $item,
        ], 200);
    }

    function update(Request $request, $id)
    {
        $validate = $request->validate([
            'title' => 'string|required',
            'start' => 'required|date',
            'end' => 'required|date',
            'color' => 'nullable|string'
        ]);
        $event = Schedule::with('doctor')->findOrFail($id);
        $validate['start'] = Carbon::parse($validate['start'])->format('Y-m-d H:i:s');
        if (!empty($validate['end'])) {
            $validate['end'] = Carbon::parse($validate['end'])->format('Y-m-d H:i:s');
        } else {
            $validate['end'] = null;
        }
        $event->update($validate);

        $patientName = Auth::user()->full_name;
        $doctorProfile = $event->doctor;

        if ($doctorProfile) {
            $doctorProfile->user->notify(new DoctorScheduleNotification('updated', $event->toArray(), $patientName));
            
            // Broadcast to the doctor's user ID
            event(new AppointmentNotification("Appointment updated: {$event->title}", $doctorProfile->user_id));
            
            // Broadcast to the patient's user ID
            if ($event->patient && $event->patient->user_id) {
                event(new AppointmentNotification("Your appointment was updated: {$event->title}", $event->patient->user_id));
            }
        }

        return response()->json([
            'message' => 'Schedule updated successfully',
            'schedule' => $event,
        ], 200);
    }

    function deleteEvent($id)
    {
        $item = Schedule::with('doctor')->findOrFail($id);
        $doctorProfile = $item->doctor;
        $patientName = Auth::user()->full_name ?? 'Patient';

        $item->delete();

        if ($doctorProfile) {
            // We pass the data here because the record is already deleted from DB!
            $doctorProfile->user->notify(new DoctorScheduleNotification('deleted', $item->toArray(), $patientName));
            
            // Broadcast to the doctor's user ID
            event(new AppointmentNotification("Appointment cancelled: {$item->title}", $doctorProfile->user_id));
            
            // Broadcast to the patient's user ID
            if ($item->patient && $item->patient->user_id) {
                event(new AppointmentNotification("Your appointment was cancelled: {$item->title}", $item->patient->user_id));
            }
        }

        return response()->json([
            'message' => 'Schedule deleted successfully',
        ], 200);
    }

    function show($id)
    {
        $item = Schedule::findOrFail($id);
        return response()->json([
            'schedule' => $item,
        ], 200);
    }

    function getEvents(Request $request)
    {
        $query = Schedule::with('doctor.user', 'patient');

        if ($request->has('doctor_id')) {
            // Viewing a specific doctor's schedule (e.g. for booking)
            $query->where('doctor_profile_id', $request->doctor_id);
        } else {
            // "My Calendar" View
            $user = Auth::user();
            if ($user->hasRole('doctor')) {
                $query->where('doctor_profile_id', $user->doctorProfile->id);
            } elseif ($user->hasRole('patient')) {
                $query->where('patient_profile_id', $user->patientProfile->id);
            }
        }

        if ($request->has('start') && $request->has('end')) {
            $query->where(function($q) use ($request) {
                $q->whereBetween('start', [$request->start, $request->end])
                  ->orWhereBetween('end', [$request->start, $request->end]);
            });
        }

        $events = $query->get();

        $formattedEvents = $events->map(function ($event) {
            $user = Auth::user();
            $isOwner = false;

            if ($user->hasRole('doctor') && $event->doctor_profile_id == $user->doctorProfile->id) {
                $isOwner = true;
            } elseif ($user->hasRole('patient') && $event->patient_profile_id == $user->patientProfile->id) {
                $isOwner = true;
            }

            // Anonymize title if not owner and not admin
            $title = $isOwner || $user->isAdmin() ? $event->title : 'Booked';

            return [
                'id' => $event->id,
                'title' => $title,
                'start' => $event->start,
                'end' => $event->end,
                'color' => $isOwner ? $event->color : '#888888', // Grey out others
                'doctor_id' => $event->doctor_profile_id,
                'patient_id' => $event->patient_profile_id,
                'is_owner' => $isOwner, // Helper for frontend
                'extendedProps' => [
                    'doctor_id' => $event->doctor_profile_id,
                    'patient_id' => $event->patient_profile_id,
                ]
            ];
        });
        return response()->json($formattedEvents);

        // $item = $query->get();
        // return response()->json([
        //     'schedule' => $item,
        // ], 200);
    }

    function searchEvents(Request $request)
    {
        $item = Schedule::where('title', 'like', '%' . $request->search . '%')->get();
        return response()->json([
            'schedule' => $item,
        ], 200);
    }
}
