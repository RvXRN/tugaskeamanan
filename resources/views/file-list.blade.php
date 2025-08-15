<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File List</title>
</head>
<body>
    <h1>Directory Listing</h1>

    @if(count($files) > 0)
        <ul>
            @foreach ($files as $file)
                <li>
                    <a href="{{ asset('storage/files/' . basename($file)) }}" target="_blank">{{ basename($file) }}</a>
                </li>
            @endforeach
        </ul>
    @else
        <p>No files found.</p>
    @endif
</body>
</html>
