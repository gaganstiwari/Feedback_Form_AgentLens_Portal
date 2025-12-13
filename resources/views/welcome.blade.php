@foreach ($data as $item)
<tr>
    <td>{{ $item['request_id'] }}</td>

    <td>
        <button class="btn btn-primary"
                onclick="generateLink('{{ $item['request_id'] }}')">
            Generate Signed URL
        </button>

        <div id="link-{{ $item['request_id'] }}" class="mt-2"></div>
    </td>
</tr>
@endforeach


<script>
function generateLink(requestid) {
    fetch(`/send-link/${requestid}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById(`link-${requestid}`).innerHTML = `
                <a href="${data.signed_url}" target="_blank">${data.signed_url}</a>
                <br>
                <small style="color: green;">Expires in: ${data.expires_in}</small>
            `;
        })
        .catch(error => {
            console.error(error);
            alert("Error generating signed URL");
        });
}
</script>
