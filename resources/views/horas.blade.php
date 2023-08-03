@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-7">
            <div class="row">
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
                <div class="col-md-3">
                    <table>
                        <thead>
                        <th>Horas Pendentes</th>
                        </thead>
                        <tbody>
                        <td>{{$extra}}</td>
                        </tbody>           
                    </table>
                </div>
                <div class="col-md-2">
                    <table>
                    <thead>
                        <th>Valor a Receber</th>
                        </thead>
                        <tbody>
                        <td>{{$valor_receber}}</td> 
                        </tbody>            
                    </table>
                </div> 
                <div class="col-md-2">
                    <table>
                    <thead>
                        <th>Valor Recebido</th>
                        </thead>
                        <tbody>
                        <td></td> 
                        </tbody>            
                    </table>
                </div> 
                <div class="col-md-3">
                    <table>
                    <thead>
                        <th>Valor a Receber BH</th>
                        </thead>
                        <tbody>
                        <td>{{$valor_receber_bh}}</td> 
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
            <div class="row g-2 justify-content-center">
                    <div class="col-md-4">
                        <input type="date" class="form-control" placeholder="Data" name="data" value="{{$hora->data}}">
                        <input type="hidden" class="form-control"  name="user_id" value="{{auth()->user()->id}} ">
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="horarios">
                        <option  disabled selected >{{"Horario"}}</option>
                        @foreach ($horarios as  $horario)
                          <option value={{$horario}} {{$horario == $hora->horarios ? 'selected':''}}>{{$horario}}</option>  
                        @endforeach
                        
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" placeholder="Horas" name="horas" value="{{$hora->horas}}" >
                    </div>
                    <div class="col-md-2 pt-2">
                        <input type="checkbox" class="form-check-input" name="pago" value="{{$hora->horas}}">
                        <label>Pago</label>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary">Editar</button>
                    </div>
                    </div>
        @else
            <form  action="{{route('horas.store')}}" method="POST">
                @csrf
                
                <div class="row g-2 justify-content-center">
                    <div class="col-md-4">
                        <input type="date" class="form-control" placeholder="Data" name="data">
                        <input type="hidden" class="form-control"  name="user_id" value="{{auth()->user()->id}} ">
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="horarios">
                        <option  disabled selected >{{"Horario"}}</option>
                        @foreach ($horarios as  $horario)
                          <option value={{$horario}}>{{$horario}}</option>  
                        @endforeach
                        
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" placeholder="Horas" name="horas" >
                    </div>
                    <div class="col-md-2 pt-2">
                        <input type="checkbox" class="form-check-input" name="pago" value='1'>
                        <label>Pago</label>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary">Adicionar</button>
                   </div>
                
                </div>
                @endif  
            </form>
          
        </div>
        <div class="container justify-content-center pt-5">
        <div class="col-md-3 pb-2">
        <input type="text" id="myInput" class="form-control" onkeyup="myFunction()" placeholder="Filtrar Data ou Horario">
         </div>   
            <table class="table table-bordered table-sm" id="myTable">
                <thead>
                    <tr>
                        <th colspan="5" style='text-align:center' >Horas Extras Totais</th>
                    </tr>
                    <tr>
                        <th >Data</th>
                        <th >Horario</th>
                        <th onclick="sortTable(0)">Horas</th>
                        <th onclick="sortTable(1)">Status</th>
                        <th></th>
                    </tr>
                               
                </thead>
                <tbody>
                @foreach ( $horas as $hora )
                    
                    <tr>
                        <td>{{$hora->data}}</td>
                        <td>{{$hora->horarios}}</td> 
                        <td>{{$hora->horas}}</td> 
                        <td>{{$hora->pago == "1" ? "Pago" : "Não Pago"}}</td>
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
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
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

function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("myTable");
  switching = true;
  //Set the sorting direction to ascending:
  dir = "asc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
</script>
@endsection
