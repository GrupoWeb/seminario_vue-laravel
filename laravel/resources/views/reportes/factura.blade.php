<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <style>
        @page {
		    margin: 0cm 0cm;
            /* size: 360pt 360pt; */
	    }

        body{
            margin: 1cm 2cm 2cm;
        }

        span{
        text-align: center;
        text-transform: uppercase;
        }
        .contenido{
        font-size: 20px;
        }
        #primero{
        background-color: #ccc;
        }
        #segundo{
        color:#44a359;
        }
        #tercero{
        text-decoration:line-through;
        }

        .informacion{
            font-size: 12px;
            text-align: center;
        }

        .color{
            background-color: #a03131;
            font-size: 12px;
            color: #FFF;
            
        }

        .color_descripcion{
            background-color: #a03131;
            margin-top: 5px;
            color: #FFF;
            border: 1px solid #000;
            border-radius: 30px;
        }

        .table {
            border: 1px solid #000;
        }

        .table_nit{
            border: 1px solid #000;
            border-radius: 30px;
        }


        .nit_texto{
            background-color: #a03131;
            color: #FFF;
            position: relative;
            top: 8px; left: 100px;
            width: 25%;
            /* height: 26px; */
            padding: 5px;
            text-align: center;
        }

    </style>
    </head>
    <body>
        @foreach($data as $item)
        <table>
            <tbody>
                <tr>
                    <td>
                        <img src="{{ $base }}" width="200" height="100">
                    </td>
                    <td>
                        <div class="informacion">
                            <span>TRANSPORT S.A</span><br>
                            Av. Reforma 3-48 zona 9 Ed. Anel 2 nivel<br>
                            Teléfono (502)2285-7000 / FAX (502)2285-7010<br>
                            https://transport.com.gt
                        </div>
                    </td>
                    <td>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="color" colspan=2>Factura No:</td>
                                </tr>
                                <tr>
                                    <td colspan=2>{{ $item->fel }}</td>
                                </tr>
                                <tr>
                                    <td class="color"> Fecha Emisión:</td>
                                    <td> {{ $item->fecha }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        
        <div class="table_nit nit_texto">NIT: {{ $item->nit }}</div>
        </table>
        <table class="table" width="100%">
            <tbody>
                <tr>
                    <td colspan=10></td>
                </tr>
                <tr>
                    <td>Nombre: </td>
                    <td colspan=9>{{ $item->cliente }}</td>
                </tr>
                <tr>
                    <td colspan=10></td>
                </tr>
                <tr>
                    <td>Dirección: </td>
                    <td colspan=9>{{ $item->address }}</td>
                </tr>
                <tr>
                    <td colspan=10></td>
                </tr>
                <tr>
                    <td colspan=1></td>
                    <td colspan=3></td>
                    <td colspan=1>Teléfono:</td>
                    <td colspan=2> {{ $item->phone }} </td>
                    <td colspan=2>Email:</td>
                    <td>{{ $item->correo }}</td>
                </tr>
            </tbody>
        </table>
        @endforeach
        
        <table width="100%" class="color_descripcion">
            <tbody>
                <tr>
                    <td width="5%">Cantidad</td>
                    <td width="50%">Descripción</td>
                    <td width="20%">Precio Unitario</td>
                </tr>
            </tbody>
        </table>
        <table width="100%" >
            <tbody>
                @foreach($dets as $pro)
                <tr>
                    <td width="5%">{{ $pro->cantidad }}</td>
                    <td width="50%">{{ $pro->producto }}</td>
                    <td width="20%">{{ $pro->precio }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan=2></td>
                    <td>
                        @foreach($monto as $total)
                          <b>Total</b>: {{ $total->monto_total }}
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
        <img src="{{ $logoFel }}" width="250" height="300" > 
    </body>
</html>