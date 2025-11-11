<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Historial Médico - {{ $medicalHistory->patient->first_name }} {{ $medicalHistory->patient->last_name }}</title>
    <style>
        @page {
            margin: 1in;
            size: A4;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #2c3e50;
            line-height: 1.6;
            font-size: 12px;
        }

        .header {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 300;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .header .subtitle {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 0 20px;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-header {
            background: #ecf0f1;
            color: #2c3e50;
            padding: 12px 15px;
            margin: -15px -15px 15px -15px;
            border-left: 4px solid #9b59b6;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            width: 30%;
            padding: 10px 15px 10px 0;
            font-weight: 600;
            color: #34495e;
            background: #f8f9fa;
            border-bottom: 1px solid #ecf0f1;
            vertical-align: top;
        }

        .info-value {
            display: table-cell;
            width: 70%;
            padding: 10px 0;
            border-bottom: 1px solid #ecf0f1;
        }

        .event-card {
            background: white;
            border: 1px solid #ecf0f1;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            page-break-inside: avoid;
        }

        .event-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ecf0f1;
        }

        .event-date {
            font-weight: 600;
            color: #2c3e50;
            font-size: 13px;
        }

        .event-type {
            background: #9b59b6;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .event-content {
            margin-bottom: 10px;
        }

        .event-summary {
            font-weight: 600;
            color: #34495e;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .event-notes {
            color: #7f8c8d;
            line-height: 1.5;
            font-style: italic;
            margin-bottom: 10px;
        }

        .event-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: #95a5a6;
        }

        .event-doctor {
            font-weight: 500;
        }

        .diseases-list {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            margin-top: 10px;
            border-left: 3px solid #e74c3c;
        }

        .diseases-list h5 {
            margin: 0 0 8px 0;
            color: #2c3e50;
            font-size: 12px;
            font-weight: 600;
        }

        .diseases-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .disease-tag {
            background: #e74c3c;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 500;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
            margin-top: 20px;
        }

        .timeline-item {
            position: relative;
            padding: 8px 0;
            border-left: 2px solid #ecf0f1;
        }

        .timeline-item:before {
            content: '';
            position: absolute;
            left: -6px;
            top: 12px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #9b59b6;
        }

        .timeline-date {
            font-weight: 600;
            color: #2c3e50;
            font-size: 11px;
        }

        .timeline-desc {
            color: #7f8c8d;
            font-size: 11px;
            margin-top: 2px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
            font-style: italic;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 20px 0;
        }

        .no-data .icon {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .footer {
            margin-top: 40px;
            padding: 20px;
            border-top: 2px solid #ecf0f1;
            text-align: center;
            color: #7f8c8d;
            font-size: 10px;
        }

        .footer p {
            margin: 5px 0;
        }

        .page-break {
            page-break-before: always;
        }

        @media print {
            body {
                font-size: 11px;
            }

            .header {
                background: #9b59b6 !important;
                -webkit-print-color-adjust: exact;
            }

            .event-card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Salud Comunitaria</h1>
        <div class="subtitle">Historial Médico Completo</div>
    </div>

    <div class="content">
        <div class="section">
            <div class="section-header">Información del Paciente</div>

            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Nombre Completo</div>
                    <div class="info-value">{{ $medicalHistory->patient->first_name }} {{ $medicalHistory->patient->last_name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Correo Electrónico</div>
                    <div class="info-value">{{ $medicalHistory->patient->email ?: 'No especificado' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Cédula</div>
                    <div class="info-value">{{ $medicalHistory->patient->dni }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Edad</div>
                    <div class="info-value">{{ $medicalHistory->patient->age ? $medicalHistory->patient->age . ' años' : 'No especificada' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Fecha de Creación del Historial</div>
                    <div class="info-value">{{ $medicalHistory->created_at->format('d/m/Y H:i:s') }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-header">Eventos del Historial Médico</div>

            @if($medicalHistory->events->count() > 0)
                @foreach($medicalHistory->events->sortByDesc('date') as $event)
                <div class="event-card">
                    <div class="event-header">
                        <div class="event-date">
                            {{ $event->date ? $event->date->format('d/m/Y') : 'Fecha no especificada' }}
                        </div>
                        <div class="event-type">{{ $event->type ?: 'Evento General' }}</div>
                    </div>

                    <div class="event-content">
                        @if($event->summary)
                        <div class="event-summary">{{ $event->summary }}</div>
                        @endif

                        @if($event->notes)
                        <div class="event-notes">{{ $event->notes }}</div>
                        @endif
                    </div>

                    @if($event->related_diseases && count($event->related_diseases) > 0)
                    <div class="diseases-list">
                        <h5>Enfermedades Relacionadas</h5>
                        <div class="diseases-tags">
                            @foreach($event->related_diseases as $disease)
                                <span class="disease-tag">{{ $disease }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="event-meta">
                        <div class="event-doctor">
                            @if($event->doctor)
                                Dr. {{ $event->doctor->first_name }} {{ $event->doctor->last_name }}
                            @else
                                Médico no especificado
                            @endif
                        </div>
                        <div class="event-created">
                            Registrado: {{ $event->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="timeline">
                    <h4 style="margin-bottom: 15px; color: #2c3e50; font-size: 14px;">Cronología de Eventos</h4>
                    @foreach($medicalHistory->events->sortBy('date') as $event)
                    <div class="timeline-item">
                        <div class="timeline-date">{{ $event->date ? $event->date->format('d/m/Y') : 'Fecha no especificada' }}</div>
                        <div class="timeline-desc">{{ $event->summary ?: $event->type ?: 'Evento registrado' }}</div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="no-data">
                    <div class="icon">📋</div>
                    <p>No hay eventos registrados en el historial médico del paciente.</p>
                    <p style="font-size: 11px; margin-top: 10px;">Los eventos aparecerán aquí cuando se registren consultas, tratamientos o diagnósticos.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="footer">
        <p><strong>Reporte generado el:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
        <p><strong>Sistema de Salud Comunitaria</strong> - Historial médico confidencial</p>
        <p style="font-size: 9px; margin-top: 10px; color: #bdc3c7;">
            Este documento contiene información médica protegida por las leyes de privacidad de datos.
        </p>
    </div>
</body>

</html>