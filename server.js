const express = require('express');
const { google } = require('googleapis');
const cors = require('cors');
const fs = require('fs');
const path = require('path');
const app = express();
const port = 3000;

app.use(cors()); // Enable CORS for all routes

// Serve static files from the root directory
app.use(express.static(path.join(__dirname)));

// Load the service account key JSON file
const serviceAccount = JSON.parse(fs.readFileSync('three-quarters-445522-dd2b6e3d0f80.json'));

// Configure a JWT auth client
const auth = new google.auth.JWT(
  serviceAccount.client_email,
  null,
  serviceAccount.private_key,
  ['https://www.googleapis.com/auth/spreadsheets.readonly']
);

// Google Sheets API setup
const sheets = google.sheets({ version: 'v4', auth });

app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'index.html'));
});

app.get('/api/benchmarks', async (req, res) => {
  try {
    const response = await sheets.spreadsheets.values.get({
      spreadsheetId: '1UiKf8ts9BE5pna5fZvHqjDcod8twmsExL6VNhBNl1B8',
      range: 'Sheet1!A1:G10', // Adjust the range as needed
    });

    const rows = response.data.values;
    if (rows.length) {
      const data = { people: [] };
      rows.forEach((row, index) => {
        if (index === 0) return; // Skip header row
        const [name, image, year, quarter, squat, benchPress, deadlift] = row;
        let person = data.people.find(p => p.name === name);
        if (!person) {
          person = { name, image, benchmarks: [] };
          data.people.push(person);
        }
        person.benchmarks.push({
          year,
          quarter,
          squat,
          benchPress,
          deadlift
        });
      });
      res.json(data);
    } else {
      res.status(404).send('No data found.');
    }
  } catch (err) {
    console.error('Error fetching data from Google Sheets:', err);
    // Read from backup JSON file
    const backupData = JSON.parse(fs.readFileSync(path.join(__dirname, 'backup.json')));
    res.json(backupData);
  }
});

app.listen(port, () => {
  console.log(`Server running at http://localhost:${port}`);
});
