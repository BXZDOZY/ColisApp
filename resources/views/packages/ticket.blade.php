<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket Colis - {{ $package->tracking_number }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.4; margin: 0; padding: 20px; }
        .ticket-container { border: 2px dashed #333; padding: 20px; max-width: 800px; margin: auto; }
        .header { text-align: center; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #007bff; }
        .tracking-section { text-align: center; margin-bottom: 20px; background: #f8f9fa; padding: 10px; border-radius: 5px; }
        .tracking-number { font-size: 20px; font-weight: bold; letter-spacing: 2px; }
        .info-grid { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-grid td { width: 50%; vertical-align: top; padding: 10px; border: 1px solid #eee; }
        .section-title { font-weight: bold; color: #007bff; text-transform: uppercase; font-size: 12px; margin-bottom: 5px; border-bottom: 1px solid #eee; }
        .details-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .details-table th { text-align: left; background: #f8f9fa; padding: 8px; font-size: 12px; border: 1px solid #ddd; }
        .details-table td { padding: 8px; border: 1px solid #ddd; font-size: 13px; }
        .qr-placeholder { text-align: center; margin-top: 20px; font-size: 10px; color: #777; }
        .footer { text-align: center; font-size: 10px; color: #777; margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="header">
            <div class="logo">COLISAPP</div>
            <p style="margin: 5px 0;">Bon d'Expédition & Reçu Client</p>
        </div>

        <div class="tracking-section">
            <div class="section-title">Récapitulatif de l'Envoi</div>
            <div class="tracking-number">{{ $package->tracking_number }}</div>
            <div style="margin-top: 5px;">
                <span style="background: #007bff; color: white; padding: 2px 8px; border-radius: 10px; font-size: 11px;">{{ $package->type }}</span>
                <span style="background: #e9ecef; color: #495057; padding: 2px 8px; border-radius: 10px; font-size: 11px; margin-left: 5px;">{{ $package->weight }} kg</span>
            </div>
        </div>

        <table class="info-grid">
            <tr>
                <td>
                    <div class="section-title">Expéditeur</div>
                    <strong>{{ $package->sender_name }}</strong><br>
                    Tél: {{ $package->sender_phone }}<br>
                    {{ $package->sender_address }}
                </td>
                <td>
                    <div class="section-title">Destinataire</div>
                    <strong>{{ $package->receiver_name }}</strong><br>
                    Tél: {{ $package->receiver_phone }}<br>
                    {{ $package->receiver_address }}
                </td>
            </tr>
        </table>

        @if($package->description)
        <div style="margin-bottom: 20px; padding: 10px; border: 1px solid #eee; border-radius: 5px;">
            <div class="section-title">Description du Contenu</div>
            <div style="font-size: 13px;">{{ $package->description }}</div>
        </div>
        @endif

        <div class="section-title" style="margin-bottom: 10px; border-bottom: 2px solid #eee;">Historique & Suivi du Colis</div>
        <table class="details-table" style="margin-top: 5px; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th style="width: 25%;">Date & Heure</th>
                    <th style="width: 25%;">Lieu</th>
                    <th>Étape / Détails</th>
                </tr>
            </thead>
            <tbody>
                @foreach($package->histories as $history)
                <tr>
                    <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $history->location }}</td>
                    <td>
                        <strong style="font-size: 11px;">
                            @if($history->status == 'pending') En attente
                            @elseif($history->status == 'in_transit') En transit
                            @elseif($history->status == 'delivered') ✅ Livré
                            @elseif($history->status == 'cancelled') ❌ Annulé
                            @endif
                        </strong><br>
                        {{ $history->details }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="qr-placeholder">
            <p>Scannez ce code pour vérifier le statut en temps réel ou valider la livraison.</p>
            <div style="margin: 10px auto;">
                <img src="data:image/svg+xml;base64,{{ $qrcode }}" width="100">
            </div>
            <div style="font-weight: bold; margin-top: 5px;">{{ $package->tracking_number }}</div>
        </div>

        <div class="footer">
            <p>Document généré le {{ date('d/m/Y à H:i') }}</p>
            <p>Merci d'avoir choisi ColisApp pour vos envois.</p>
            <p>© {{ date('Y') }} ColisApp - Logistique Rapide & Sécurisée</p>
        </div>
    </div>
</body>
</html>
