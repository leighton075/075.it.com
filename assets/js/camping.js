document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch('assets/data/campingdata.json');
        const data = await response.json();

        const personList = document.getElementById('person-list');
        const tableBody = document.getElementById('benchmark-table-body');

        data.people.forEach(person => {
            // Populate the sidebar list
            const li = document.createElement('li');
            li.innerHTML = `
                <img src="${person.image}" alt="${person.name}">
                <span>${person.name}</span>
            `;
            li.addEventListener('click', () => {
                alert(`Items for ${person.name}: ${person.items.map(item => `${item.name} (${item.amount})`).join(', ')}`);
            });
            personList.appendChild(li);

            // Populate the table
            person.items.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${person.name}</td>
                    <td>${item.name}</td>
                    <td>${item.amount}</td>
                `;
                tableBody.appendChild(row);
            });
        });
    } catch (error) {
        console.error('Error fetching camping data:', error);
    }
});