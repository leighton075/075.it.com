const express = require('express');
const { google } = require('googleapis');
const cors = require('cors');
const fs = require('fs');
const app = express();
const port = 3000;

app.use(cors()); // Enable CORS for all routes

// Load the service account key JSON file
const serviceAccount = JSON.parse(fs.readFileSync('path/to/your-service-account-file.json'));

// Configure a JWT auth client
const auth = new google.auth.JWT(
  serviceAccount.client_email,
  null,
  serviceAccount.private_key,
  ['https://www.googleapis.com/auth/spreadsheets.readonly']
);

// Google Sheets API setup
const sheets = google.sheets({ version: 'v4', auth });

app.get('/api/benchmarks', async (req, res) => {
  try {
    const response = await sheets.spreadsheets.values.get({
      spreadsheetId: 'your-spreadsheet-id',
      range: 'Sheet1!A1:E10', // Adjust the range as needed
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
    res.status(500).send('Error fetching data');
  }
});

app.listen(port, () => {
  console.log(`Server running at http://localhost:${port}`);
});
