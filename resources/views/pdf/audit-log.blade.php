<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Audit Log Aziendale</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Registro di Sistema (Audit Log)</h1>
        <p>
            Periodo: 
            {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : 'Inizio' }} 
            - 
            {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : 'Oggi' }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Data e Ora</th>
                <th>Utente</th>
                <th>Azione</th>
                <th>Dettaglio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                <td>{{ $log->user ? $log->user->name : 'Sistema' }}</td>
                <td>{{ strtoupper($log->action) }}</td>
                <td>{{ $log->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>