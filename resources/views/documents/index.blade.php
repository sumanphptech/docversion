<!DOCTYPE html>
<html>
<head>
    <title>All Documents</title>
</head>
<body>

    @include('layouts.header')

    <h1>All Documents</h1>

    <table border="1" cellpadding="8" cellspacing="0" id="documentsTable">
        <thead>
            <tr>
                <th>Title</th>
                <th>Slug</th>
                <th>Latest Version</th>
                <th>Content</th>
            </tr>
        </thead>
        <tbody>
            <!-- rows will be injected by JS -->
        </tbody>
    </table>

    <script>
        async function loadDocuments() {
            const response = await fetch('/api/documents');
            const data = await response.json();

            const tbody = document.querySelector('#documentsTable tbody');
            tbody.innerHTML = ''; // clear existing rows

            data.forEach(doc => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${doc.title}</td>
                    <td>${doc.slug}</td>
                    <td>${doc.latest_version ?? '-'}</td>
                    <td>${doc.content ?? '-'}</td>
                    <td>
                        <a href="/documents/${doc.slug}/edit">Edit</a>
                        <a href="/documents/${doc.slug}/versions">Versions</a>                    
                    </td>

                `;
                tbody.appendChild(tr);
            });
        }

        loadDocuments();
    </script>

</body>
</html>