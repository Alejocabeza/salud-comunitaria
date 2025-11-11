<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Cita Médica - ID: {{ $appointment->id }}</title>
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
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
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

        .appointment-id {
            background: #f39c12;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 600;
            font-size: 14px;
            margin-top: 10px;
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
            border-left: 4px solid #27ae60;
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
            width: 35%;
            padding: 10px 15px 10px 0;
            font-weight: 600;
            color: #34495e;
            background: #f8f9fa;
            border-bottom: 1px solid #ecf0f1;
            vertical-align: top;
        }

        .info-value {
            display: table-cell;
            width: 65%;
            padding: 10px 0;
            border-bottom: 1px solid #ecf0f1;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            margin-top: 5px;
        }

        .status-pending {
            background: #f39c12;
            color: white;
        }

        .status-accepted {
            background: #27ae60;
            color: white;
        }

        .status-completed {
            background: #3498db;
            color: white;
        }

        .status-rejected {
            background: #e74c3c;
            color: white;
        }

        .status-cancelled {
            background: #95a5a6;
            color: white;
        }

        .notes-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            border-left: 4px solid #3498db;
        }

        .notes-section h4 {
            margin: 0 0 10px 0;
            color: #2c3e50;
            font-size: 13px;
            font-weight: 600;
        }

        .notes-content {
            color: #34495e;
            line-height: 1.5;
            font-style: italic;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            padding: 10px 0;
            border-left: 2px solid #ecf0f1;
        }

        .timeline-item:before {
            content: '';
            position: absolute;
            left: -6px;
            top: 15px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #3498db;
        }

        .timeline-item.completed:before {
            background: #27ae60;
        }

        .timeline-item.cancelled:before {
            background: #e74c3c;
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
                background: #27ae60 !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Salud Comunitaria</h1>
        <div class="subtitle">Reporte Detallado de Cita Médica</div>
        <div class="appointment-id">Cita #{{ $appointment->id }}</div>
    </div>

    <div class="content">
        <div class="section">
            <div class="section-header">Información General de la Cita</div>

            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Estado de la Cita</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ $appointment->status }}">
                            {{ $appointment->status_label }}
                        </span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Fecha de Solicitud</div>
                    <div class="info-value">
                        {{ $appointment->requested_date ? $appointment->requested_date->format('d/m/Y') : 'No especificada' }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Fecha Programada</div>
                    <div class="info-value">
                        @if ($appointment->scheduled_date)
                            {{ $appointment->scheduled_date->format('d/m/Y \a \l\a\s H:i') }}
                        @else
                            No programada aún
                        @endif
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Motivo de la Consulta</div>
                    <div class="info-value">{{ $appointment->reason ?: 'No especificado' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Fecha de Creación</div>
                    <div class="info-value">{{ $appointment->created_at->format('d/m/Y H:i:s') }}</div>
                </div>
                @if ($appointment->accepted_at)
                    <div class="info-row">
                        <div class="info-label">Fecha de Aceptación</div>
                        <div class="info-value">{{ $appointment->accepted_at->format('d/m/Y H:i:s') }}</div>
                    </div>
                @endif
                @if ($appointment->completed_at)
                    <div class="info-row">
                        <div class="info-label">Fecha de Finalización</div>
                        <div class="info-value">{{ $appointment->completed_at->format('d/m/Y H:i:s') }}</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="section">
            <div class="section-header">Información del Paciente</div>

            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Nombre Completo</div>
                    <div class="info-value">{{ $appointment->patient->first_name }}
                        {{ $appointment->patient->last_name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Correo Electrónico</div>
                    <div class="info-value">{{ $appointment->patient->email ?: 'No especificado' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Teléfono</div>
                    <div class="info-value">{{ $appointment->patient->phone ?: 'No especificado' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Cédula</div>
                    <div class="info-value">{{ $appointment->patient->dni }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Edad</div>
                    <div class="info-value">
                        {{ $appointment->patient->age ? $appointment->patient->age . ' años' : 'No especificada' }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tipo de Sangre</div>
                    <div class="info-value">{{ $appointment->patient->blood_type ?: 'No especificado' }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-header">Información del Médico</div>

            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Nombre Completo</div>
                    <div class="info-value">{{ $appointment->doctor->first_name }}
                        {{ $appointment->doctor->last_name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Correo Electrónico</div>
                    <div class="info-value">{{ $appointment->doctor->email ?: 'No especificado' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Especialidad</div>
                    <div class="info-value">{{ $appointment->doctor->specialty ?: 'No especificada' }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-header">Centro Ambulatorio</div>

            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Nombre del Centro</div>
                    <div class="info-value">{{ $appointment->outpatientCenter->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Dirección</div>
                    <div class="info-value">{{ $appointment->outpatientCenter->address ?: 'No especificada' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Teléfono</div>
                    <div class="info-value">{{ $appointment->outpatientCenter->phone ?: 'No especificado' }}</div>
                </div>
            </div>
        </div>

        @if ($appointment->patient_notes || $appointment->doctor_notes)
            <div class="section">
                <div class="section-header">Notas y Observaciones</div>

                @if ($appointment->patient_notes)
                    <div class="notes-section">
                        <h4>Notas del Paciente</h4>
                        <div class="notes-content">{{ $appointment->patient_notes }}</div>
                    </div>
                @endif

                @if ($appointment->doctor_notes)
                    <div class="notes-section">
                        <h4>Notas del Médico</h4>
                        <div class="notes-content">{{ $appointment->doctor_notes }}</div>
                    </div>
                @endif
            </div>
        @endif

        <div class="section">
            <div class="section-header">Línea de Tiempo de la Cita</div>

            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-date">{{ $appointment->created_at->format('d/m/Y H:i') }}</div>
                    <div class="timeline-desc">Cita solicitada por el paciente</div>
                </div>

                @if ($appointment->accepted_at)
                    <div class="timeline-item completed">
                        <div class="timeline-date">{{ $appointment->accepted_at->format('d/m/Y H:i') }}</div>
                        <div class="timeline-desc">Cita aceptada y programada por el médico</div>
                    </div>
                @endif

                @if ($appointment->completed_at)
                    <div class="timeline-item completed">
                        <div class="timeline-date">{{ $appointment->completed_at->format('d/m/Y H:i') }}</div>
                        <div class="timeline-desc">Consulta médica completada</div>
                    </div>
                @elseif($appointment->status === 'cancelled' || $appointment->status === 'rejected')
                    <div class="timeline-item cancelled">
                        <div class="timeline-date">{{ $appointment->updated_at->format('d/m/Y H:i') }}</div>
                        <div class="timeline-desc">
                            Cita {{ $appointment->status === 'cancelled' ? 'cancelada' : 'rechazada' }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="footer">
        <p><strong>Reporte generado el:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
        <p><strong>Sistema de Salud Comunitaria</strong> - Reporte confidencial para uso médico</p>
    </div>
</body>

</html>
