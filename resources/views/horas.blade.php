@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-7">
            <div class="row g-1">
                <div class="col-md-2">
                    <table>
                    <thead>
                        <th>Banco de Horas</th>
                        </thead>
                        <tbody>
                        <td>{{$bancohoras}}</td> 
                        </tbody>           
                    </table>
                </div>
                <div class="col-md-2">
                    <table>
                        <thead>
                        <th>Horas a Pagar</th>
                        </thead>
                        <tbody>
                        <td>{{$extra}}</td>
                        </tbody>           
                    </table>
                </div>
                <div class="col-md-3">
                    <table>
                    <thead>
                        <th>Valor a Receber Extras</th>
                        </thead>
                        <tbody>
                        <td>€ {{$valor_receber}}</td> 
                        </tbody>            
                    </table>
                </div> 
                <div class="col-md-2">
                    <table>
                    <thead>
                        <th>Valor Recebido Extras</th>
                        </thead>
                        <tbody>
                        <td>€ {{$valor_recebido}}</td> 
                        </tbody>            
                    </table>
                </div> 
                <div class="col-md-3">
                    <table>
                    <thead>
                        <th>Valor a Receber BH</th>
                        </thead>
                        <tbody>
                        <td>€ {{$valor_receber_bh}}</td> 
                        </tbody>            
                    </table>
                </div>              
            </div> 
        </div>        
        <div class="col-sm-5">
        @if (isset($hora->id))
           <form  action={{route('horas.update', ['hora' => $hora->id])}} method="POST">
           @method('PUT')
            @csrf
            <div class="row g-1 justify-content-center">
                    <div class="col-md-4">
                        <input type="date" class="form-control" placeholder="Data" name="data" value="{{$hora->data}}">
                        <input type="hidden" class="form-control"  name="user_id" value="{{auth()->user()->id}} ">
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="horarios">
                        <option  disabled selected >{{"Horario"}}</option>
                        @foreach ($horarios as  $horario)
                          <option value={{$horario}} {{$horario == $hora->horarios ? 'selected':''}}>{{$horario}}</option>  
                        @endforeach
                        
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="time" class="form-control" placeholder="Horas" name="horas" value="{{$hora->horas}}" >
                    </div>
                    <div class="col-md-2 pt-2">
                        <input type="checkbox" class="form-check-input" name="pago" value="1"{{$hora->pago == "1" ? "checked" :""}}>
                        <label>Pago</label>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary">Editar</button>
                    </div>
                    </div>
            </form>
        @else
            <form  action="{{route('horas.store')}}" method="POST">
                @csrf
                
                <div class="row g-1 justify-content-center">
                    <div class="col-md-4">
                        <input type="date" class="form-control" placeholder="Data" name="data">
                        <input type="hidden" class="form-control"  name="user_id" value="{{auth()->user()->id}} ">
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" name="horarios">
                        <option  disabled selected >{{"Horario"}}</option>
                        @foreach ($horarios as  $horario)
                          <option value={{$horario}}>{{$horario}}</option>  
                        @endforeach
                        
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="time" class="form-control" placeholder="Horas" name="horas" >
                    </div>
                    <div class="col-md-2 pt-2">
                        <input type="checkbox" class="form-check-input" name="pago" value="1">
                        <label>Pago</label>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary">Adicionar</button>
                   </div>
                
                </div>                  
            </form>
         @endif 
        </div>
        <div class="container justify-content-center pt-5">
        <div class="col-md-3 pb-2">
        <input type="text" id="myInput" class="form-control" onkeyup="myFunction()" placeholder="Filtrar Data">
         </div>
         <div class="row g-1">
         <div class="col-md-9 ">   
            <table class="table table-bordered table-sm" id="myTable">
                <thead>
                    <tr>
                        <th colspan="6" style='text-align:center' >Horas Extras Totais</th>
                    </tr>
                    <tr>
                        <th >Data</th>
                        <th >Horario</th>
                        <th >Horas</th>
                        <th >Status</th>
                        <th>Valor Estimado</th>
                        <th></th>
                    </tr>
                               
                </thead>
                <tbody>
                @foreach ( $horas as $hora )
                    
                    <tr>
                        <td>
                        @php
                            $data = $hora->data;
                            $data = explode("-", $data);
                            $data = array_reverse($data);
                            $data = implode("-", $data);
                        @endphp
                        {{$data}}</td>
                        <td>{{$hora->horarios}}</td> 
                        <td>{{$hora->horas}}</td> 
                        <td>{{$hora->pago == "1" ? "Pago" : ($hora->horarios == "BH" ? "Pago no Final do Contrato" : "Não Pago")}}</td>
                        <td>
                        @php 
                                $sum = strtotime('00:00:00');
                                $totalvalores = 0;
                                $timeinsec = strtotime($hora->horas) - $sum;   
                                $totalvalores = $totalvalores + $timeinsec;
                            
                            $valores = ($totalvalores / 3600);
                            $valor = $valores * $oh;
                            $valor = number_format($valor,2, ".", ",");
                        @endphp
                        € {{$valor}}
                         </td>
                        <td>
                        <div class="btn-group">          
                                <button class="btn btn-outline-light text-dark" onclick="window.location.href='{{route('horas.edit', ['hora' => $hora->id])}}';">edit</button>
                            <form method="post" action="{{route("horas.destroy", ["hora" => $hora->id])}}">
                            @method("DELETE")
                            @csrf
                                <button class="btn btn-outline-light text-dark" type="submit">delete</button>           
                            </form>
                        </div>
                    </td>
                    </tr> 
                @endforeach        
                </tbody>           
            </table>
        </div>
       <div class="col-md-3">
            <div class="justify-content-center pb-2">
                <span >Calcular Ganhos Por hora</span>
            </div>
            <div class="justify-content-center pb-2">
                <input type="time" class="form-control"  onchange="timePassed(this.value)" id="hora_calculo">
            </div>
            <div class="justify-content-center pb-2">
            <label>Valor</label>
                <input type="text" class="form-control" readonly id="valor">
            </div>  
       </div>
       </div>
       </div> 
    </div>
</div>
<script>
function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
  }
function timePassed(time) {                
        let[hours, mins] = time.split(":");
        var n1 = hours;
        var n2 = mins/60;
        var n3 = Number(n1) + Number(n2);
        var n4 = n3 * 10.685;
       
        document.getElementById('valor').value = new Intl.NumberFormat("pt-PT", { style: "currency", currency: "EUR" }).format(n4) ;
}
       
  
</script>
@endsection
