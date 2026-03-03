<!DOCTYPE html>
<html>
<head>
    <title>Edit Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

@include('layouts.header')

<h1>Edit Document</h1>

<form id="editForm">
    <label>Title:</label><br>
    <input type="text" name="title" required><br><br>

    <label>Slug:</label><br>
    <input type="text" name="slug" required readonly><br><br>

   
    <label>Content (latest version will be updated):</label><br>
    <textarea name="content" rows="5" cols="50" required></textarea><br><br>

    <button type="submit">Update</button>
</form>

<p id="response"></p>

<script>
    const slug = '{{ $slug }}';
    const form = document.getElementById('editForm');

    async function loadDocument() {
       
        const response = await fetch(`/api/documents/${slug}`);
        const doc = await response.json();

        form.title.value = doc.title;
        form.slug.value = doc.slug;
        form.content.value = doc.versions.length > 0 ? doc.versions[0].content : '';
    }

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

    loadDocument();
</script>

</body>
</html>