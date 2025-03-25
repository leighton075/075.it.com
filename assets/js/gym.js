document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch('assets/data/gymdata.json');
        const data = await response.json();

        const personList = document.getElementById('person-list');
        const benchmarkTableBody = document.getElementById('benchmark-table-body');

        const renderBenchmarks = (benchmarks) => {
            benchmarkTableBody.innerHTML = '';
            benchmarks.forEach(benchmark => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${new Date().getFullYear()}</td>
                    <td>${benchmark.quarter}</td>
                    <td>${benchmark.squat}</td>
                    <td>${benchmark.benchPress}</td>
                    <td>${benchmark.deadlift}</td>
                `;
                benchmarkTableBody.appendChild(tr);
            });
        };

        data.people.forEach(person => {
            // Populate the sidebar list
            const li = document.createElement('li');
            li.innerHTML = `
                <img src="${person.image}" alt="${person.name}">
                <span>${person.name}</span>
            `;
            li.addEventListener('click', () => {
                renderBenchmarks(person.benchmarks);
            });
            personList.appendChild(li);
        });

        // Initially render the first person's benchmarks
        if (data.people.length > 0) {
            renderBenchmarks(data.people[0].benchmarks);
        }
    } catch (error) {
        console.error('Error fetching gym data:', error);
    }
});