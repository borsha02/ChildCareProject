<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absent Children Report - {{ $date }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            padding: 40px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #ef4444;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 28px;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .header .date {
            font-size: 16px;
            color: #6b7280;
            font-weight: 500;
        }

        .summary {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 15px 20px;
            margin-bottom: 30px;
            border-radius: 4px;
        }

        .summary p {
            font-size: 16px;
            color: #991b1b;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        thead {
            background: #f9fafb;
        }

        th {
            padding: 12px;
            text-align: left;
            font-size: 13px;
            font-weight: 700;
            color: #1f2937;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e5e7eb;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
            color: #374151;
        }

        tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        tbody tr:last-child td {
            border-bottom: 2px solid #e5e7eb;
        }

        .child-name {
            font-weight: 600;
            color: #1f2937;
        }

        .child-id {
            font-size: 12px;
            color: #6b7280;
            display: block;
            margin-top: 2px;
        }

        .parent-phone {
            color: #3b82f6;
            font-weight: 500;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #6b7280;
            font-size: 16px;
            background: #f9fafb;
            border-radius: 8px;
        }

        @media print {
            body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Absent Children Report</h1>
        <div class="date">{{ \Carbon\Carbon::parse($date)->format('l, F d, Y') }}</div>
    </div>

    <div class="summary">
        <p>Total Absent Children: {{ $absentChildren->count() }}</p>
    </div>

    @if($absentChildren->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 30%;">Child Name</th>
                    <th style="width: 15%;">Class</th>
                    <th style="width: 30%;">Parent Name</th>
                    <th style="width: 20%;">Parent Phone</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absentChildren as $index => $child)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="child-name">{{ $child->first_name }} {{ $child->last_name }}</span>
                            <span class="child-id">ID: CH{{ str_pad($child->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td>{{ $child->class }}</td>
                        <td>{{ $child->parent ? $child->parent->name : 'N/A' }}</td>
                        <td class="parent-phone">{{ $child->parent ? $child->parent->phone : 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>No absent children on this date.</p>
        </div>
    @endif

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>Little Stars Childcare Management System</p>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
