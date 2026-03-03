<!DOCTYPE html>
<html>
<head>
    <title>All Documents</title>
</head>
<body>

    @include('layouts.header')

    <h1>All Versions</h1>

    <p>Title <span id="title"></span></p>
    <p>Slug <span id="slug"></span></p>

    <table border="1" cellpadding="8" cellspacing="0" id="documentsTable">
        <thead>
            <tr>
                <th>Select</th>
                <th>Version</th>
                <th>Content</th>
                <th>Created at</th>
            </tr>
        </thead>
        <tbody>
            <!-- rows will be injected by JS -->
        </tbody>
    </table>

    <button id="publishBtn">Publish</button>
    <p id="response"></p>

    <script>
        async function loadDocuments() {

            const slug = '{{ $slug }}';

            const response = await fetch(`/api/documents/${slug}/versions`);
            const data = await response.json();

            const tbody = document.querySelector('#documentsTable tbody');
            tbody.innerHTML = ''; // clear existing rows

            console.log('okkk');
            console.log(data);


            document.getElementById('title').innerHTML = data.title;
            document.getElementById('slug').innerHTML = data.slug;

            data.versions.forEach(row => {
                const tr = document.createElement('tr');

                tr.className = row.id === data.version_id ? 'published-row' : 'draft-row';

                // Format the created_at date as MM-DD-YYYY
                const createdDate = new Date(row.created_at);
                const formattedDate = `${String(createdDate.getMonth() + 1).padStart(2, '0')}-${String(createdDate.getDate()).padStart(2, '0')}-${createdDate.getFullYear()}`;


                tr.innerHTML = `
                    <td>
                        <input type="radio" name="versionSelect" value="${row.id}"${row.id === data.version_id ? 'checked' : ''}>
                    </td>
                    <td>${row.version_number}</td>
                    <td>${row.content}</td>
                    <td>${formattedDate}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Publish Button Click
        document.addEventListener('click', async function(e) {

            if (e.target.id === 'publishBtn') {

                const selected = document.querySelector('input[name="versionSelect"]:checked');

                if (!selected) {
                    alert('Please select a version');
                    return;
                }

                const versionId = selected.value;
                const slug = '{{ $slug }}';

                const response = await fetch(`/api/documents/${slug}/publish`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        version_id: versionId
                    })
                });

                const result = await response.json();

                document.getElementById('response').innerText = result.message;

                // loadDocuments(); // refresh table
            }
        });

        loadDocuments();
    </script>

</body>
</html>