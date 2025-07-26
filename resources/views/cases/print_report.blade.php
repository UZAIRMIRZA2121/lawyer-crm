<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Case Report - {{ $case->case_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px 30px;
            color: #222;
            line-height: 1.5;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #333;
            padding-bottom: 10px;
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            color: #0056b3;
        }

        .page-header .admin-email {
            font-size: 16px;
            font-weight: 500;
            color: #555;
        }

        h2.section-title {
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 22px;
            border-bottom: 2px solid #444;
            padding-bottom: 6px;
            color: #003366;
        }

        p {
            margin: 5px 0;
            font-size: 16px;
        }

        .client-details,
        .case-details {
            max-width: 720px;
            margin-bottom: 40px;
        }

        .client-info p,
        .case-info p {
            margin: 8px 0;
        }

        /* CNIC images */
        .cnic-images {
            display: flex;
            gap: 30px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .cnic-block {
            flex: 1 1 300px;
            text-align: center;
        }

        .cnic-block strong {
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
            color: #111;
        }

        .cnic-block img {
            max-width: 100%;
            max-height: 220px;
            border: 1px solid #ccc;
            border-radius: 6px;
            object-fit: contain;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            font-size: 15px;
            color: #222;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 10px 12px;
            text-align: left;
        }

        th {
            background-color: #f0f4f8;
            font-weight: 600;
        }

        tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        /* Responsive */
        @media print {
            body {
                margin: 10mm;
                color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .page-header {
                border-bottom-color: #000;
            }

            table,
            th,
            td {
                border-color: #000;
            }
        }
    </style>
</head>

<body onload="window.print()">

    {{-- Page Header with ASK Law and admin email if logged in as admin --}}
    <header class="page-header">
        <h1>ASK Law</h1>

        @auth
            @if (auth()->user()->role === 'admin')
                <div class="admin-email">
                    Email: {{ auth()->user()->email }}
                </div>
            @endif
        @endauth
    </header>

    {{-- Client Details --}}
    <section class="client-details">
        <h2 class="section-title">Client Details</h2>
        <div class="client-info">
            <p><strong>Name:</strong> {{ $case->client->name ?? 'N/A' }}</p>
            <p><strong>CNIC:</strong> {{ $case->client->cnic ?? 'N/A' }}</p>
            <p><strong>Contact No:</strong> {{ $case->client->contact_no ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $case->client->email ?? 'N/A' }}</p>
            <p><strong>Address:</strong> {{ $case->client->address ?? 'N/A' }}</p>
        </div>

        @if ($case->client->cnic_front || $case->client->cnic_back)
            <div class="cnic-images">
                @if ($case->client->cnic_front)
                    <div class="cnic-block">
                        <strong>CNIC Front</strong>
                        <img src="{{ asset('storage/' . $case->client->cnic_front) }}" alt="CNIC Front">
                    </div>
                @endif

                @if ($case->client->cnic_back)
                    <div class="cnic-block">
                        <strong>CNIC Back</strong>
                        <img src="{{ asset('storage/' . $case->client->cnic_back) }}" alt="CNIC Back">
                    </div>
                @endif
            </div>
        @endif
    </section>

    {{-- Case Details --}}
    <section class="case-details">
        <h2 class="section-title">Case Details</h2>
        <div class="case-info">
            <p><strong>Case Number:</strong> {{ $case->case_number }}</p>
            <p><strong>Case Title:</strong> {{ $case->case_title }}</p>
            <p><strong>Description:</strong> {!! $case->description ?? 'N/A' !!}</p>
            <p><strong>Status:</strong> {{ ucfirst($case->status) }}</p>
            <p><strong>Hearing Date:</strong>
                {{ $case->hearing_date ? \Carbon\Carbon::parse($case->hearing_date)->format('d M Y') : 'N/A' }}
            </p>
            <p><strong>Judge Name:</strong> {{ $case->judge_name ?? 'N/A' }}</p>
            <p><strong>Case Nature:</strong> {{ $case->case_nature ?? 'N/A' }}</p>
            {{-- Amount intentionally excluded --}}
        </div>
    </section>

    {{-- Against Clients --}}
    <section class="section">
        <h2 class="section-title">Against Clients</h2>
        @if ($case->againstClients->count())
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>CNIC</th>
                        <th>Address</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($case->againstClients as $index => $ac)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $ac->name }}</td>
                            <td>{{ $ac->cnic ?? 'N/A' }}</td>
                            <td>{{ $ac->address ?? 'N/A' }}</td>
                            <td>{{ $ac->phone ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No against clients found.</p>
        @endif
    </section>
    <section class="section">
        <h2 class="section-title">Notices Against Clients</h2>

        @php
            $notices = \App\Models\Notice::with('against_client')
                ->where('case_id', $case->id)
                ->orderBy('created_at', 'desc')
                ->get();
        @endphp

        @if ($notices->count())
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Against Client</th>
                        <th>Notice</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($notices as $index => $notice)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $notice->against_client->name ?? 'N/A' }}</td>
                            <td>{!! $notice->notice !!}</td>
                            <td>{{ ucfirst($notice->status) }}</td>
                            <td>{{ \Carbon\Carbon::parse($notice->created_at)->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No notices found against any client.</p>
        @endif
    </section>

    {{-- Hearings --}}
    <section class="section">
        <h2 class="section-title">Hearings</h2>
        @if ($case->hearings->count())
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Judge Name</th>
                        <th>Judge Remarks</th>
                        <th>My Remarks</th>
                        <th>Next Hearing</th>
                        <th>Priority</th>
                        <th>Nature</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($case->hearings as $index => $hearing)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $hearing->judge_name ?? 'N/A' }}</td>
                            <td>{{ $hearing->judge_remarks ?? 'N/A' }}</td>
                            <td>{{ $hearing->my_remarks ?? 'N/A' }}</td>
                            <td>
                                {{ $hearing->next_hearing ? \Carbon\Carbon::parse($hearing->next_hearing)->format('d M Y') : 'N/A' }}
                            </td>
                            <td>{{ ucfirst($hearing->priority) ?? 'N/A' }}</td>
                            <td>{{ $hearing->nature ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No hearings found.</p>
        @endif
    </section>

</body>

</html>
