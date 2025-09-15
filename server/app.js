const express = require('express');
const bodyParser = require('body-parser');
const mysql = require('mysql');
const cors = require('cors'); 
const app = express();

app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

const basicAuth = (req, res, next) => {
    const authHeader = req.headers.authorization;
    if (!authHeader) {
        res.setHeader('WWW-Authenticate', 'Basic realm="WorkOrderCMS"');
        return res.status(401).send('Authentication required.');
    }

    const [type, credentials] = authHeader.split(' ');
    if (type !== 'Basic' || !credentials) {
        return res.status(401).send('Invalid authentication.');
    }

    const decoded = Buffer.from(credentials, 'base64').toString();
    const [username, password] = decoded.split(':');

    if (username === 'demo' && password === 'demo123') {
        return next();
    }

    return res.status(403).send('Forbidden');
};

app.use(basicAuth);

app.get('/', (req, res) => {
    res.send({ error: false, message: 'Node.js REST API for FSRE project with Basic Auth' });
});

const dbConn = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'workordercms_db'
});

dbConn.connect(err => {
    if (err) throw err;
    console.log('Connected to MySQL database.');
});


app.get('/managers', (req, res) => {
    dbConn.query(`
        SELECT managers.id, managers.name, locations.name AS location
        FROM managers 
        LEFT JOIN locations ON managers.location_id = locations.id
    `, (error, results) => {
        if (error) throw error;
        res.send({ error: false, data: results, message: 'All managers list.' });
    });
});

app.get('/manager/:id', (req, res) => {
    const id = req.params.id;
    dbConn.query(`
        SELECT managers.id, managers.name, locations.name AS location
        FROM managers 
        LEFT JOIN locations ON managers.location_id = locations.id
        WHERE managers.id = ?
    `, [id], (error, results) => {
        if (error) throw error;
        res.send({ error: false, data: results[0], message: 'Single manager.' });
    });
});

app.post('/manager', (req, res) => {
    const { name, location_id } = req.body;
    if (!name || !location_id) {
        return res.status(400).send({ error: true, message: 'Please provide name and location_id' });
    }
    dbConn.query('INSERT INTO managers (name, location_id) VALUES (?, ?)', [name, location_id], (error, results) => {
        if (error) throw error;
        res.send({ error: false, data: results, message: 'New manager has been created.' });
    });
});


app.put('/manager/:id', (req, res) => {
    const id = req.params.id;
    const { name, location_id } = req.body;
    if (!name || !location_id) {
        return res.status(400).send({ error: true, message: 'Please provide name and location_id' });
    }
    dbConn.query('UPDATE managers SET name = ?, location_id = ? WHERE id = ?', [name, location_id, id], (error, results) => {
        if (error) throw error;
        res.send({ error: false, data: results, message: 'Manager updated successfully.' });
    });
});


app.delete('/manager/:id', (req, res) => {
    const id = req.params.id;
    dbConn.query('DELETE FROM managers WHERE id = ?', [id], (error, results) => {
        if (error) throw error;
        res.send({ error: false, data: results, message: 'Manager deleted successfully.' });
    });
});

app.get('/dispatchers', (req, res) => {
    dbConn.query('SELECT * FROM dispatchers', (error, results) => {
        if (error) throw error;
        res.send({ error: false, data: results, message: 'All dispatchers list.' });
    });
});

app.get('/dispatcher/:id', (req, res) => {
    const id = req.params.id;
    dbConn.query('SELECT * FROM dispatchers WHERE id = ?', [id], (error, results) => {
        if (error) throw error;
        res.send({ error: false, data: results[0], message: 'Single dispatcher.' });
    });
});

app.post('/dispatcher', (req, res) => {
    const { name, phone } = req.body;
    if (!name || !phone) {
        return res.status(400).send({ error: true, message: 'Please provide name and phone' });
    }
    dbConn.query('INSERT INTO dispatchers (name, phone) VALUES (?, ?)', [name, phone], (error, results) => {
        if (error) throw error;
        res.send({ error: false, data: results, message: 'New dispatcher has been created.' });
    });
});

app.put('/dispatcher/:id', (req, res) => {
    const id = req.params.id;
    const { name, phone } = req.body;
    if (!name || !phone) {
        return res.status(400).send({ error: true, message: 'Please provide name and phone' });
    }
    dbConn.query('UPDATE dispatchers SET name = ?, phone = ? WHERE id = ?', [name, phone, id], (error, results) => {
        if (error) throw error;
        res.send({ error: false, data: results, message: 'Dispatcher updated successfully.' });
    });
});

app.delete('/dispatcher/:id', (req, res) => {
    const id = req.params.id;
    dbConn.query('DELETE FROM dispatchers WHERE id = ?', [id], (error, results) => {
        if (error) throw error;
        res.send({ error: false, data: results, message: 'Dispatcher deleted successfully.' });
    });
});

app.get('/locations', (req, res) => {
    dbConn.query('SELECT * FROM locations', (error, results) => {
        if (error) throw error;
        res.send({ error: false, data: results, message: 'All locations list.' });
    });
});

app.get('/location/:id', (req, res) => {
    const id = req.params.id;
    dbConn.query('SELECT * FROM locations WHERE id = ?', [id], (error, results) => {
        if (error) throw error;
        res.send({ error: false, data: results[0], message: 'Single location.' });
    });
});

app.post('/location', (req, res) => {
    const { name } = req.body;
    if (!name) {
        return res.status(400).send({ error: true, message: 'Please provide name' });
    }
    dbConn.query('INSERT INTO locations (name) VALUES (?)', [name], (error, results) => {
        if (error) throw error;
        res.send({ error: false, data: results, message: 'New location has been created.' });
    });
});

app.put('/location/:id', (req, res) => {
    const id = req.params.id;
    const { name } = req.body;
    if (!name) {
        return res.status(400).send({ error: true, message: 'Please provide name' });
    }
    dbConn.query('UPDATE locations SET name = ? WHERE id = ?', [name, id], (error, results) => {
        if (error) throw error;
        res.send({ error: false, data: results, message: 'Location updated successfully.' });
    });
});

app.delete('/location/:id', (req, res) => {
    const id = req.params.id;
    dbConn.query('DELETE FROM locations WHERE id = ?', [id], (error, results) => {
        if (error) throw error;
        res.send({ error: false, data: results, message: 'Location deleted successfully.' });
    });
});

app.listen(3000, () => {
    console.log('Node.js REST API running on port 3000 with Basic Auth');
});

module.exports = app;
