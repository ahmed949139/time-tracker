<!DOCTYPE html>
<html>
<head>
    <title>Time Logs Report</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; font-size: 12px; }
    </style>
</head>
<body>
    <h2>Time Logs Report</h2>
    <table>
        <thead>
            <tr>
                <th>Project</th>
                <th>Client</th>
                <th>Client ID</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Hours</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td>{{ $log->project->title }}</td>
                <td>{{ $log->project->client->name }}</td>
                <td>{{ $log->project->client->id }}</td>
                <td>{{ $log->start_time }}</td>
                <td>{{ $log->end_time }}</td>
                <td>{{ $log->hours }}</td>
                <td>{{ $log->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>