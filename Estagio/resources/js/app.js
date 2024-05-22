import './bootstrap';

function sortQuartos() {
    const table = document.getElementById('quartos-table').tBodies[0];
    const rows = Array.from(table.rows);

    rows.sort((a, b) => {
        const statusA = a.querySelector('.status').innerText.toLowerCase();
        const statusB = b.querySelector('.status').innerText.toLowerCase();
        return statusA === 'ocupado' ? -1 : statusB === 'ocupado' ? 1 : 0;
    });

    rows.forEach(row => table.appendChild(row));
}
