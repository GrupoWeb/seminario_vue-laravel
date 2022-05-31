<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Document</title>
        <style>
        h1{
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
    </style>
    </head>
    <body>
        <h1>Despacho de Pedido</h1>
        <hr>
        <table >
            <tbody>
                <tr>
                    <td>Correlativo: </td>
                    <td>{{ $correlativo }}</td>
                    <td></td>
                    <td>Usuario Solicitante: </td>
                    <td>{{ $usuario }}</td>
                </tr>
            </tbody>
        </table>
        <hr>
        <br>
        <div class="contenido">
            <table border="1px" style="width:100%; ">
                <thead>
                    <tr>
                        <th style="width:33%;">PRODUCTO</th>
                        <th style="width:33%;">CANTIDAD</th>
                        <th style="width:33%;">PRECIO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dets as $items)
                        <tr>
                            <td>{{ $items->producto }}</td>
                            <td>{{ $items->cantidad }}</td>
                            <td>{{ $items->precio }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>