<!-- resources/views/layouts/header.blade.php -->
<header style="background:#333; color:#fff; padding:10px;">
    <nav>
        <ul style="list-style:none; display:flex; gap:15px; margin:0; padding:0;">
            <li><a href="{{ url('/') }}" style="color:#fff; text-decoration:none;">Home</a></li>
            <li><a href="{{ route('documents.create') }}" style="color:#fff; text-decoration:none;">Create Document</a></li>
        </ul>
    </nav>
</header>