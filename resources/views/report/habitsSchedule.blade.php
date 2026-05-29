<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Horario</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            vertical-align: top;
            padding: 6px;
            text-align: center;
        }

        th {
            background: #e9f2d9;
        }

        .block {
            background: #dff0d8;
            margin: 2px 0;
            padding: 3px;
            border-radius: 3px;
        }

        .hour {
            font-weight: bold;
            display: block;
            margin-bottom: 4px;
        }
    </style>
</head>

<body>

<h3 style="text-align:center;">Horario semanal</h3>

<table>
    <thead>
        <tr>
            <th>Lunes</th>
            <th>Martes</th>
            <th>Miércoles</th>
            <th>Jueves</th>
            <th>Viernes</th>
            <th>Sábado</th>
            <th>Domingo</th>
        </tr>
    </thead>

    <tbody>
        <tr>

            {{-- LUNES --}}
            <td>
                @foreach ($habits->where('had_day', 'lunes') as $h)
                    <div class="block">
                        <span class="hour">{{ $h->had_schedule_ini }} - {{ $h->had_schedule_end }}</span>
                        {{ $h->had_description }}
                    </div>
                @endforeach
            </td>

            {{-- MARTES --}}
            <td>
                @foreach ($habits->where('had_day', 'martes') as $h)
                    <div class="block">
                        <span class="hour">{{ $h->had_schedule_ini }} - {{ $h->had_schedule_end }}</span>
                        {{ $h->had_description }}
                    </div>
                @endforeach
            </td>

            {{-- MIÉRCOLES --}}
            <td>
                @foreach ($habits->where('had_day', 'miercoles') as $h)
                    <div class="block">
                        <span class="hour">{{ $h->had_schedule_ini }} - {{ $h->had_schedule_end }}</span>
                        {{ $h->had_description }}
                    </div>
                @endforeach
            </td>

            {{-- JUEVES --}}
            <td>
                @foreach ($habits->where('had_day', 'jueves') as $h)
                    <div class="block">
                        <span class="hour">{{ $h->had_schedule_ini }} - {{ $h->had_schedule_end }}</span>
                        {{ $h->had_description }}
                    </div>
                @endforeach
            </td>

            {{-- VIERNES --}}
            <td>
                @foreach ($habits->where('had_day', 'viernes') as $h)
                    <div class="block">
                        <span class="hour">{{ $h->had_schedule_ini }} - {{ $h->had_schedule_end }}</span>
                        {{ $h->had_description }}
                    </div>
                @endforeach
            </td>

            {{-- SÁBADO --}}
            <td>
                @foreach ($habits->where('had_day', 'sabado') as $h)
                    <div class="block">
                        <span class="hour">{{ $h->had_schedule_ini }} - {{ $h->had_schedule_end }}</span>
                        {{ $h->had_description }}
                    </div>
                @endforeach
            </td>

            {{-- DOMINGO --}}
            <td>
                @foreach ($habits->where('had_day', 'domingo') as $h)
                    <div class="block">
                        <span class="hour">{{ $h->had_schedule_ini }} - {{ $h->had_schedule_end }}</span>
                        {{ $h->had_description }}
                    </div>
                @endforeach
            </td>

        </tr>
    </tbody>
</table>

</body>
</html>