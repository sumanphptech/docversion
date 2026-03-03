<!DOCTYPE html>
<html>
<head>
    <title>Create  Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <!-- Include the header -->
    @include('layouts.header')

    <h1>Create Document</h1>
    <form id="documentForm">
        <label>Title:</label><br>
        <input type="text" name="title" required><br><br>

        <label>Slug:</label><br>
        <input type="text" name="slug" required><br><br>

        <label>Content:</label><br>
        <textarea name="content" rows="5" cols="50" required></textarea><br><br>

        <button type="submit">Submit</button>
    </form>

    <p id="response"></p>

    <script>
        const form = document.getElementById('documentForm');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = {
                title: form.title.value,
                slug: form.slug.value,
                content: form.content.value
            };

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


            const response = await fetch('/api/documents', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token 
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            document.getElementById('response').innerText = result.message; // JSON.stringify(result, null, 2);
        });
    </script>
</body>
</html>