<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket de Support #{{ $ticket->id }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #007bff; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 28px; font-weight: bold; color: #007bff; }
        .ticket-info { margin-bottom: 30px; }
        .ticket-info table { width: 100%; border-collapse: collapse; }
        .ticket-info th { text-align: left; width: 30%; padding: 8px; background: #f8f9fa; }
        .ticket-info td { padding: 8px; border-bottom: 1px solid #eee; }
        .content-box { border: 1px solid #ddd; padding: 20px; border-radius: 5px; background: #fff; margin-bottom: 20px; }
        .box-title { font-weight: bold; color: #007bff; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .footer { text-align: center; font-size: 12px; color: #777; margin-top: 50px; border-top: 1px solid #eee; padding-top: 10px; }
        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 3px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .status-open { background: #fee2e2; color: #dc2626; }
        .status-in_progress { background: #dbeafe; color: #2563eb; }
        .status-closed { background: #dcfce7; color: #16a34a; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">COLISAPP</div>
        <p>Preuve de Demande d'Assistance</p>
    </div>

    <div class="ticket-info">
        <table>
            <tr>
                <th>Ticket ID</th>
                <td>#{{ $ticket->id }}</td>
            </tr>
            <tr>
                <th>Date</th>
                <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <th>Client</th>
                <td>{{ $ticket->customer_name }}</td>
            </tr>
            <tr>
                <th>Téléphone / Email</th>
                <td>{{ $ticket->phone }} / {{ $ticket->email }}</td>
            </tr>
            <tr>
                <th>Statut</th>
                <td>
                    <span class="status-badge status-{{ $ticket->status }}">
                        @if($ticket->status == 'open') Ouvert
                        @elseif($ticket->status == 'in_progress') En cours
                        @elseif($ticket->status == 'closed') Fermé
                        @endif
                    </span>
                </td>
            </tr>
            @if($ticket->package)
            <tr>
                <th>Colis Lié</th>
                <td>{{ $ticket->package->tracking_number }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="content-box">
        <div class="box-title">Sujet : {{ $ticket->subject }}</div>
        <div class="message">
            <strong>Message du client :</strong><br>
            {{ $ticket->message }}
        </div>
    </div>

    @if($ticket->admin_response)
    <div class="content-box" style="border-left: 5px solid #16a34a; background: #f0faf4;">
        <div class="box-title" style="color: #16a34a;">Réponse de ColisApp</div>
        <div class="message">
            {{ $ticket->admin_response }}
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Merci de faire confiance à ColisApp. Ceci est un document officiel généré automatiquement.</p>
        <p>© {{ date('Y') }} ColisApp - Service Transport & Logistique</p>
    </div>
</body>
</html>
