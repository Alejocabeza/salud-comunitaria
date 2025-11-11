<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Paciente - {{ $patient->first_name }} {{ $patient->last_name }}</title>
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
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
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
            border-left: 4px solid #3498db;
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
            padding: 8px 15px 8px 0;
            font-weight: 600;
            color: #34495e;
            background: #f8f9fa;
            border-bottom: 1px solid #ecf0f1;
        }

        .info-value {
            display: table-cell;
            width: 70%;
            padding: 8px 0;
            border-bottom: 1px solid #ecf0f1;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background: #27ae60;
            color: white;
        }

        .status-inactive {
            background: #e74c3c;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 11px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        table thead {
            background: #3498db;
            color: white;
        }

        table th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 10px;
        }

        table td {
            padding: 10px 15px;
            border-bottom: 1px solid #ecf0f1;
        }

        table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        table tbody tr:hover {
            background: #e8f4fd;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #7f8c8d;
            font-style: italic;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 15px 0;
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
                background: #3498db !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Salud Comunitaria</h1>
        <div class="subtitle">Reporte Médico del Paciente</div>
    </div>

    <div class="content">
        <div class="section">
            <div class="section-header">Información Personal del Paciente</div>

            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Nombre Completo</div>
                    <div class="info-value">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Correo Electrónico</div>
                    <div class="info-value">{{ $patient->email ?: 'No especificado' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Teléfono</div>
                    <div class="info-value">{{ $patient->phone ?: 'No especificado' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Dirección</div>
                    <div class="info-value">{{ $patient->address ?: 'No especificada' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Cédula</div>
                    <div class="info-value">{{ $patient->dni }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Peso</div>
                    <div class="info-value">{{ $patient->weight ? $patient->weight . ' kg' : 'No especificado' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Edad</div>
                    <div class="info-value">{{ $patient->age ? $patient->age . ' años' : 'No especificada' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tipo de Sangre</div>
                    <div class="info-value">{{ $patient->blood_type ?: 'No especificado' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Estado</div>
                    <div class="info-value">
                        <span class="status-badge {{ $patient->is_active ? 'status-active' : 'status-inactive' }}">
                            {{ $patient->is_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-header">Historial Médico</div>
            @if ($patient->medicalHistories->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Fecha de Creación</th>
                            <th>Descripción</th>
                            <th>Notas Adicionales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($patient->medicalHistories as $history)
                            <tr>
                                <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $history->description ?: 'Sin descripción' }}</td>
                                <td>{{ $history->notes ?: 'Sin notas' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">
                    <p>No hay registros en el historial médico del paciente.</p>
                </div>
            @endif
        </div>

        <div class="section">
            <div class="section-header">Citas Médicas Programadas</div>
            @if ($patient->appointments->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Fecha Solicitada</th>
                            <th>Fecha Programada</th>
                            <th>Médico Asignado</th>
                            <th>Estado</th>
                            <th>Motivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($patient->appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->requested_date ? $appointment->requested_date->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td>{{ $appointment->scheduled_date ? $appointment->scheduled_date->format('d/m/Y H:i') : 'No programada' }}
                                </td>
                                <td>{{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</td>
                                <td>
                                    <span class="status-badge"
                                        style="
                                        background: {{ match ($appointment->status) {
                                            'pending' => '#f39c12',
                                            'accepted' => '#27ae60',
                                            'completed' => '#3498db',
                                            'rejected' => '#e74c3c',
                                            'cancelled' => '#95a5a6',
                                            default => '#95a5a6',
                                        } }};
                                        color: white;">
                                        {{ $appointment->status_label }}
                                    </span>
                                </td>
                                <td>{{ $appointment->reason ?: 'Sin especificar' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">
                    <p>No hay citas médicas registradas para este paciente.</p>
                </div>
            @endif
        </div>

        <div class="section">
            <div class="section-header">Enfermedades Diagnosticadas</div>
            @if ($patient->diseases->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Nombre de la Enfermedad</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($patient->diseases as $disease)
                            <tr>
                                <td>{{ $disease->name }}</td>
                                <td>{{ $disease->description ?: 'Sin descripción adicional' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">
                    <p>No hay enfermedades registradas para este paciente.</p>
                </div>
            @endif
        </div>

        <div class="section">
            <div class="section-header">Lesiones Registradas</div>
            @if ($patient->lesions->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Tipo de Lesión</th>
                            <th>Descripción Detallada</th>
                            <th>Fecha de Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($patient->lesions as $lesion)
                            <tr>
                                <td>{{ $lesion->type ?: 'No especificado' }}</td>
                                <td>{{ $lesion->description ?: 'Sin descripción' }}</td>
                                <td>{{ $lesion->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">
                    <p>No hay lesiones registradas para este paciente.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="footer">
        <p><strong>Reporte generado el:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
        <p><strong>Sistema de Salud Comunitaria</strong> - Reporte confidencial para uso médico</p>
    </div>
</body>

</html>
