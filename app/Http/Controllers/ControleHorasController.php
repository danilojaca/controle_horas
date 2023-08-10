<?php

namespace App\Http\Controllers;

use App\Models\ControleHoras;
use Illuminate\Http\Request;

use function Ramsey\Uuid\v1;

class ControleHorasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ControleHoras $hora)
    {  
    //dd($hora);   
      
    $horarios = array("DS","DC","FO","BH");
    $bh = ControleHoras::where([['user_id',auth()->user()->id],['horarios','BH']])->pluck('horas')->toArray();

    $ds = ControleHoras::where([['user_id',auth()->user()->id],['horarios','DS']])->pluck('horas')->toArray();
        
    $extra = ControleHoras::where([['user_id',auth()->user()->id],['horarios','DC']])->OrWhere([['user_id',auth()->user()->id],['horarios','FO']])->OrWhere([['user_id',auth()->user()->id],['horarios','DS']])->pluck('horas')->toArray();

    $horas = ControleHoras::where('user_id',auth()->user()->id)->orderBy('data')->get();

    $extrapago = ControleHoras::where([['user_id',auth()->user()->id],['pago','1']])->pluck('horas')->toArray();
        
    $sum = strtotime('00:00:00');
    $totalextra = 0;
    $totalbh = 0;
    $totalds = 0;
    $totalextrapaga = 0;
    foreach ($extra as $element ) {
    $timeinsec = strtotime($element) - $sum;
    $totalextra = $totalextra + $timeinsec;
    }
    $extra = ($totalextra / 3600);
    
    foreach ($bh as $element ) {
     $timeinsec = strtotime($element) - $sum;
     $totalbh = $totalbh + $timeinsec;
     }
     $bh = ($totalbh / 3600);
     
     foreach ($ds as $element ) {
     $timeinsec = strtotime($element) - $sum;
     $totalds = $totalds + $timeinsec;
     }
     $ds = ($totalds / 3600);

     foreach ($extrapago as $element ) {
     $timeinsec = strtotime($element) - $sum; 
     $totalextrapaga = $totalextrapaga + $timeinsec;
     }
    $extrapago = ($totalextrapaga / 3600);
    

    $oh = "10.685";
    $extra = ($extra - $extrapago);
    $valor_recebido = ($extrapago * $oh);
    $valor_receber = ($extra  * $oh);
    $bancohoras = ($bh + $ds);
    $valor_receber_bh = ($bancohoras * $oh);
    $valor_receber_bh = number_format($valor_receber_bh,2, ".", ",");
    $valor_recebido = number_format($valor_recebido,2, ".", ",");
    $valor_receber = number_format($valor_receber,2, ".", ","); 
    
        return view('horas', compact("oh","valor_recebido","valor_receber_bh","valor_receber","horarios","extra","bancohoras","horas"));
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
        //dd($hora);
    $horarios = array("DS","DC","FO","BH");
    $bh = ControleHoras::where([['user_id',auth()->user()->id],['horarios','BH']])->pluck('horas')->toArray();

    $ds = ControleHoras::where([['user_id',auth()->user()->id],['horarios','DS']])->pluck('horas')->toArray();
        
    $extra = ControleHoras::where([['user_id',auth()->user()->id],['horarios','DC']])->OrWhere([['user_id',auth()->user()->id],['horarios','FO']])->OrWhere([['user_id',auth()->user()->id],['horarios','DS']])->pluck('horas')->toArray();

    $horas = ControleHoras::where('user_id',auth()->user()->id)->orderBy('data')->get();

    $extrapago = ControleHoras::where([['user_id',auth()->user()->id],['pago','1']])->pluck('horas')->toArray();

    $sum = strtotime('00:00:00');
    $totalextra = 0;
    $totalbh = 0;
    $totalds = 0;
    $totalextrapaga = 0;
    foreach ($extra as $element ) {
    $timeinsec = strtotime($element) - $sum;
    $totalextra = $totalextra + $timeinsec;
    }
    $extra = ($totalextra / 3600);
    
    foreach ($bh as $element ) {
     $timeinsec = strtotime($element) - $sum;
     $totalbh = $totalbh + $timeinsec;
     }
     $bh = ($totalbh / 3600);
     
     foreach ($ds as $element ) {
     $timeinsec = strtotime($element) - $sum;
     $totalds = $totalds + $timeinsec;
     }
     $ds = ($totalds / 3600);

     foreach ($extrapago as $element ) {
     $timeinsec = strtotime($element) - $sum; 
     $totalextrapaga = $totalextrapaga + $timeinsec;
     }
    $extrapago = ($totalextrapaga / 3600);
    

    $oh = "10.685";
    $extra = ($extra - $extrapago);
    $valor_recebido = ($extrapago * $oh);
    $valor_receber = ($extra  * $oh);
    $bancohoras = ($bh + $ds);
    $valor_receber_bh = ($bancohoras * $oh);
    $valor_receber_bh = number_format($valor_receber_bh,2, ".", ",");
    $valor_recebido = number_format($valor_recebido,2, ".", ",");
    $valor_receber = number_format($valor_receber,2, ".", ",");  
    
        return view('horas', compact("hora","oh","valor_recebido","valor_receber_bh","valor_receber","horarios","extra","bancohoras","horas"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ControleHoras $hora)
    {
       
       if (isset($request->pago)) {
        $hora->update($request->all());
       }else {
        $hora->update([
            "data" => $request->input("data"),
            "horarios" => $request->input("horarios"),
            "horas" => $request->input("horas"),
            "pago" => NULL,
        ]);
        
       } 
       

        return redirect()->route("horas.index");
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
