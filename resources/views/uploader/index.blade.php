<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEARTS360 | Data Uploader</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #00A3FF;
            --dark: #0F172A;
            --light: #F8FAFC;
            --glass: rgba(255, 255, 255, 0.9);
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at top right, #00A3FF22, transparent), 
                        radial-gradient(circle at bottom left, #00A3FF11, transparent),
                        #F1F5F9;
            color: var(--dark);
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
        }

        .container {
            width: 100%;
            max-width: 900px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            animation: fadeInDown 0.8s ease-out;
        }

        .header h1 {
            font-size: 2.5rem;
            margin: 0;
            background: linear-gradient(135deg, var(--dark), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header p {
            color: #64748B;
            font-size: 1.1rem;
        }

        .upload-card {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.5);
            margin-bottom: 40px;
            animation: fadeInUp 0.8s ease-out;
        }

        .drop-zone {
            border: 2px dashed #CBD5E1;
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            background: rgba(241, 245, 249, 0.5);
        }

        .drop-zone:hover {
            border-color: var(--primary);
            background: rgba(0, 163, 255, 0.05);
        }

        .btn-upload {
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 163, 255, 0.3);
        }

        .btn-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 163, 255, 0.4);
        }

        .recent-table {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.03);
            animation: fadeInUp 1s ease-out;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background: #F8FAFC;
            padding: 16px 24px;
            font-weight: 600;
            color: #64748B;
            border-bottom: 1px solid #E2E8F0;
        }

        td {
            padding: 16px 24px;
            border-bottom: 1px solid #F1F5F9;
        }

        .badge {
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-success { background: #DCFCE7; color: #166534; }
        
        .alert {
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-weight: 500;
        }
        
        .alert-success { background: #DCFCE7; color: #166534; border: 1px solid #BBF7D0; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>HEARTS360 Gateway</h1>
            <p>Upload CSV Patient Data to Dashboards</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="upload-card">
            <form action="{{ route('upload.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="drop-zone" onclick="document.getElementById('fileInput').click()">
                    <svg style="width:48px;height:48px;color:var(--primary);margin-bottom:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p><strong>Click to upload</strong> or drag and drop</p>
                    <p style="font-size:0.9rem;color:#94A3B8">CSV Files only (max. 10MB)</p>
                    <input type="file" id="fileInput" name="csv_file" style="display:none" onchange="this.form.submit()">
                </div>
                <!-- <button type="submit" class="btn-upload">Process Data</button> -->
            </form>
        </div>

        <div class="header" style="margin-bottom: 20px; text-align: left;">
            <h2 style="font-size:1.5rem">Recent Sync Activity</h2>
        </div>

        <div class="recent-table">
            <table>
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Encounter ID</th>
                        <th>Date</th>
                        <th>TTV (S/D)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentData as $row)
                    <tr>
                        <td><strong>{{ $row->full_name }}</strong></td>
                        <td><code>{{ $row->encounter_id }}</code></td>
                        <td>{{ date('d M Y', strtotime($row->encounter_date)) }}</td>
                        <td>{{ $row->systole }}/{{ $row->diastole }}</td>
                        <td><span class="badge badge-success">Integrated</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding: 40px; color: #94A3B8">No recent data found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
