document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch('assets/data/gymdata.json');
        const data = await response.json();

        const personList = document.getElementById('person-list');
        const tableBody = document.getElementById('table-body');
        const header = document.querySelector('#main header h2');

        const renderBenchmarks = (person) => {
            tableBody.innerHTML = '';
            person.benchmarks.forEach(benchmark => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${benchmark.quarter}</td>
                    <td>${benchmark.squat}</td>
                    <td>${benchmark.benchPress}</td>
                    <td>${benchmark.deadlift}</td>
                `;
                tableBody.appendChild(row);
            });
            header.textContent = `Gym Benchmarks - ${person.name}`;
        };

        data.people.forEach(person => {
            const li = document.createElement('li');
            li.innerHTML = `
                <img src="${person.image}" alt="${person.name}">
                <span>${person.name}</span>
            `;
            li.addEventListener('click', () => {
                renderBenchmarks(person);
            });
            personList.appendChild(li);
        });

        // Initially render the first person's benchmarks
        if (data.people.length > 0) {
            renderBenchmarks(data.people[0]);
        }     
    } catch (error) {
        console.error('Error fetching gym data:', error);
    }
});