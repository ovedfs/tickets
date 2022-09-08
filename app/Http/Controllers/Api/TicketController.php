<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Contract;
use Illuminate\Http\Request;
use Psy\Exception\Exception;
use App\Http\Controllers\Controller;
use App\Notifications\NewTicketNotification;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:tickets.index')->only('index');
        $this->middleware('can:tickets.store')->only('store');
        $this->middleware('can:tickets.show')->only('show');
        $this->middleware('can:tickets.update')->only('update');
        $this->middleware('can:tickets.destroy')->only('destroy');
        $this->middleware('can:tickets.cancel')->only('cancel');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Contract $contract)
    {
        if(! (auth()->user()->id === $contract->user->id)) {
            return response()->json([
                'message' => 'No autorizado',
            ]);
        }
    
        return response()->json([
            'message' => 'Listado de tickets',
            'tickets' => $contract->tickets,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Contract $contract)
    {
        if(! (auth()->user()->id === $contract->user->id)) {
            return response()->json([
                'message' => 'No autorizado',
            ]);
        }

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'sometimes|image',
        ]);

        $abogado = User::role('abogado')->get()->random();

        $ticket = $contract->tickets()->create([
            'title' => $request->title,
            'description' => $request->description,
            'arrendador_id' => $contract->user_id,
            'abogado_id' => $abogado->id,
        ]);

        auth()->user()->notify(new NewTicketNotification([
            'message' => 'Hemos recibido tu mensaje, en breve te daremos respuesta',
            'ticket' => $ticket,
            'url' => url("contracts/{$ticket->contract->id}/tickets/$ticket->id")
        ]));

        $abogado->notify(new NewTicketNotification([
            'message' => 'Se te ha asignado un nuevo ticket',
            'ticket' => $ticket,
            'url' => url("abogado-tickets/$ticket->id")
        ]));

        return response()->json([
            'message' => 'Ticket creado satisfactoriamente',
            'ticket' => $ticket
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Contract $contract, Ticket $ticket)
    {
        if(! (auth()->user()->id === $contract->user->id)) {
            return response()->json([
                'message' => 'No autorizado',
            ]);
        }

        if(! ($contract->tickets->contains($ticket))) {
            return response()->json([
                'message' => 'No autorizado',
            ]);
        }

        return response()->json([
            'message' => 'Ticket obtenido satisfactoriamente',
            'ticket' => $ticket->load('ticketResponses')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contract $contract, Ticket $ticket)
    {
        if(! (auth()->user()->id === $contract->user->id)) {
            return response()->json([
                'message' => 'No autorizado',
            ]);
        }

        if(! ($contract->tickets->contains($ticket))) {
            return response()->json([
                'message' => 'No autorizado',
            ]);
        }

        if(! $ticket->open) {
            return response()->json([
                'message' => 'No autorizado, el ticket esta cerrado',
            ]);
        }

        $request->validate([
            'response' => 'required',
            'image' => 'sometimes|image'
        ]);

        $ticketResponse = $ticket->ticketResponses()->create([
            'response' => $request->response,
            'user_id' => auth()->user()->id,
        ]);

        return response()->json([
            'message' => 'Respuesta creada satisfactoriamente',
            'response' => $ticketResponse
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contract $contract, Ticket $ticket)
    {
        //
    }

    public function close(Contract $contract, Ticket $ticket)
    {
        if(! (auth()->user()->id === $contract->user->id)) {
            return response()->json([
                'message' => 'No autorizado',
            ]);
        }

        if(! ($contract->tickets->contains($ticket))) {
            return response()->json([
                'message' => 'No autorizado',
            ]);
        }

        $ticket->open = false;
        $ticket->save();

        return response()->json([
            'message' => 'Ticket cerrado satisfactoriamente',
            'ticket' => $ticket->load('ticketResponses')
        ]);
    }
}
