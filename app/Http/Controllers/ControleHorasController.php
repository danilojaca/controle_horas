<?php

namespace App\Http\Controllers;

use App\Models\ControleHoras;
use Illuminate\Http\Request;

class ControleHorasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ControleHoras $hora)
    {  
    //dd($hora);   
      
    $horarios = array("DS","DC","FO","BH");
    $bhoras = ControleHoras::where([['user_id',auth()->user()->id],['horarios','BH']])->pluck('horas')->toArray();

    $folgads = ControleHoras::where([['user_id',auth()->user()->id],['horarios','DS']])->pluck('horas')->toArray();
        
    $ex = ControleHoras::where([['user_id',auth()->user()->id],['horarios','DC']])->OrWhere([['user_id',auth()->user()->id],['horarios','FO']])->OrWhere([['user_id',auth()->user()->id],['horarios','DS']])->pluck('horas')->toArray();

    $horas = ControleHoras::where('user_id',auth()->user()->id)->get();

    $extra = array_sum($ex);
    $bh = array_sum($bhoras);
    $ds = array_sum($folgads);

    $oh = "4.645";
    
    $valor_receber = (($extra * $oh)* 2);  
    $bancohoras = ($bh + $ds);
    $valor_receber_bh = ($bancohoras * $oh);
        
        
        return view('horas', compact("valor_receber_bh","valor_receber","horarios","extra","bancohoras","horas"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
       $horario = ControleHoras::create($request->all());

        return redirect()->back();//route('horas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ControleHoras $controleHoras)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ControleHoras $hora)
    {
        
        $horarios = array("DS","DC","FO","BH");
        $bhoras = ControleHoras::where([['user_id',auth()->user()->id],['horarios','BH']])->pluck('horas')->toArray();
    
        $folgads = ControleHoras::where([['user_id',auth()->user()->id],['horarios','DS']])->pluck('horas')->toArray();
            
        $ex = ControleHoras::where([['user_id',auth()->user()->id],['horarios','DC']])->OrWhere([['user_id',auth()->user()->id],['horarios','FO']])->OrWhere([['user_id',auth()->user()->id],['horarios','DS']])->pluck('horas')->toArray();
    
        $horas = ControleHoras::where('user_id',auth()->user()->id)->get();
    
        $extra = array_sum($ex);
        $bh = array_sum($bhoras);
        $ds = array_sum($folgads);
        $oh = "4.645";
    
        $valor_receber = ($extra + $oh);  
        $bancohoras = ($bh + $ds);
        $valor_receber_bh = ($bancohoras + $oh);
        
        
            return view('horas', compact("valor_receber_bh","valor_receber","horarios","extra","bancohoras","horas","hora"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ControleHoras $hora)
    {

        $hora->update($request->all());

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ControleHoras $hora)
    {
        
        $hora->delete();

        return redirect()->back();

    }
}
