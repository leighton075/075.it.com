document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch('assets/data/campingdata.json');
        const data = await response.json();

        const personList = document.getElementById('person-list');
        const tableBody = document.getElementById('table-body');
        const header = document.querySelector('#main header h2');

        const renderItems = (person) => {
            tableBody.innerHTML = '';
            person.items.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>${item.amount}</td>
                `;
                tableBody.appendChild(row);
            });
            header.textContent = `Camping List - ${person.name}`;
        };

        data.people.forEach(person => {
            // Populate the sidebar list
            const li = document.createElement('li');
            li.innerHTML = `
                <img src="${person.image}" alt="${person.name}">
                <span>${person.name}</span>
            `;
            li.addEventListener('click', () => {
                renderItems(person);
            });
            personList.appendChild(li);
        });

        // Initially render the first person's items
        if (data.people.length > 0) {
            renderItems(data.people[0]);
        }
    } catch (error) {
        console.error('Error fetching camping data:', error);
    }
});