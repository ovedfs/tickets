<?php

namespace App\Http\Controllers\Api;

use App\Models\Ticket;
use App\Models\Contract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AbogadoTicketController extends Controller
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
    public function index()
    {
        $tickets = Ticket::where('abogado_id', auth()->user()->id)->get();
    
        return response()->json([
            'message' => 'Listado de tickets',
            'tickets' => $tickets,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        if(!(auth()->user()->id == $ticket->abogado_id)) {
            return response()->json([
                'message' => 'This action is unauthorized',
            ]);
        }

        return response()->json([
            'message' => 'Ticket obtenido correctamente',
            'ticket' => $ticket,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        if(!(auth()->user()->id == $ticket->abogado_id)) {
            return response()->json([
                'message' => 'This action is unauthorized',
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
            'response' => $ticket->load('ticketResponses')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        if(!(auth()->user()->id == $ticket->abogado_id)) {
            return response()->json([
                'message' => 'This action is unauthorized',
            ]);
        }

        $ticket->delete();

        return response()->json([
            'message' => 'Ticket eliminado satisfactoriamente',
        ]);
    }

    public function close(Ticket $ticket)
    {
        if(!(auth()->user()->id == $ticket->abogado_id)) {
            return response()->json([
                'message' => 'This action is unauthorized',
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
