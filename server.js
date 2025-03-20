const express = require('express');
const cors = require('cors');
const fs = require('fs');
const path = require('path');
const app = express();
const port = 3000;

app.use(cors()); // Enable CORS for all routes

// Serve static files from the root directory
app.use(express.static(path.join(__dirname)));

app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'index.html'));
});

app.get('/api/benchmarks', (req, res) => {
  try {
    // Read from backup JSON file
    const backupData = JSON.parse(fs.readFileSync(path.join(__dirname, 'backup.json')));
    res.json(backupData);
  } catch (err) {
    console.error('Error reading backup data:', err);
    res.status(500).send('Error reading backup data');
  }
});

app.listen(port, () => {
  console.log(`Server running at http://localhost:${port}`);
});
