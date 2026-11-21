/**
 * Lab 11: User Validation Web Application Server
 * Built with native Node.js (HTTP + SQLite) — Zero external dependencies.
 * Also supports PHP testing and serving full application flow.
 */

const http = require('node:http');
const fs = require('node:fs');
const path = require('node:path');
const querystring = require('node:querystring');
const { DatabaseSync } = require('node:sqlite');

const PORT = process.env.PORT || 8000;
const DB_PATH = path.join(__dirname, 'lab11.db');

// In-memory active sessions
const sessions = new Map();

// Initialize SQLite database
const db = new DatabaseSync(DB_PATH);

// Setup schema and seed sample credentials
db.exec(`
  CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    full_name TEXT NOT NULL,
    email TEXT NOT NULL,
    role TEXT DEFAULT 'User',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  );
`);

// Seed default users if empty
const countQuery = db.prepare('SELECT COUNT(*) as count FROM users');
const rowCount = countQuery.get().count;

if (rowCount === 0) {
  const insertStmt = db.prepare(`
    INSERT INTO users (username, password, full_name, email, role)
    VALUES (?, ?, ?, ?, ?)
  `);

  insertStmt.run('admin', 'admin123', 'System Administrator', 'admin@example.com', 'Administrator');
  insertStmt.run('ayush', 'password123', 'Ayush Kumar', 'ayushmessi2006@gmail.com', 'Developer');
  insertStmt.run('student', 'student@2026', 'Engineering Student', 'student@university.edu', 'Student');
  console.log('✅ SQLite Database initialized with sample users (admin, ayush, student).');
}

// Helper: parse cookies
function parseCookies(req) {
  const list = {};
  const rc = req.headers.cookie;
  if (rc) {
    rc.split(';').forEach((cookie) => {
      const parts = cookie.split('=');
      list[parts.shift().trim()] = decodeURI(parts.join('='));
    });
  }
  return list;
}

// Helper: render PHP templates for node runner
function renderSuccess(user) {
  let template = fs.readFileSync(path.join(__dirname, 'success.php'), 'utf8');
  template = template.replace(/<\?php[\s\S]*?\?>/g, (match) => {
    if (match.includes('$full_name')) return user.full_name;
    if (match.includes('$username')) return user.username;
    if (match.includes('$email')) return user.email;
    if (match.includes('$role')) return user.role;
    if (match.includes('$user_id')) return user.id;
    if (match.includes('$login_time')) return user.login_time;
    return '';
  });
  return template;
}

function renderFailure(errorMsg, username) {
  let template = fs.readFileSync(path.join(__dirname, 'failure.php'), 'utf8');
  template = template.replace(/<\?php[\s\S]*?\?>/g, (match) => {
    if (match.includes('$error_msg')) return errorMsg;
    if (match.includes('$username')) return username;
    return '';
  });
  return template;
}

const mimeTypes = {
  '.html': 'text/html',
  '.css': 'text/css',
  '.js': 'application/javascript',
  '.json': 'application/json',
  '.png': 'image/png',
  '.jpg': 'image/jpeg',
  '.svg': 'image/svg+xml',
};

const server = http.createServer((req, res) => {
  const parsedUrl = new URL(req.url, `http://${req.headers.host}`);
  const pathname = parsedUrl.pathname;
  const cookies = parseCookies(req);
  const sessionId = cookies.sessionId;
  const currentUser = sessionId ? sessions.get(sessionId) : null;

  // Route: POST /login or POST /login.php
  if (req.method === 'POST' && (pathname === '/login' || pathname === '/login.php')) {
    let body = '';
    req.on('data', chunk => { body += chunk.toString(); });
    req.on('end', () => {
      const params = querystring.parse(body);
      const username = (params.username || '').trim();
      const password = (params.password || '').trim();

      if (!username || !password) {
        res.writeHead(302, { Location: `/failure.php?error=${encodeURIComponent('Both Login Name and Password are required.')}` });
        return res.end();
      }

      // Query database for matching username & password
      const userStmt = db.prepare('SELECT id, username, password, full_name, email, role, created_at FROM users WHERE username = ?');
      const user = userStmt.get(username);

      if (user && user.password === password) {
        // Successful authentication
        const newSessionId = 'sess_' + Math.random().toString(36).substring(2) + Date.now();
        const sessionData = {
          ...user,
          login_time: new Date().toLocaleString('en-IN', { timeZone: 'Asia/Kolkata' })
        };
        sessions.set(newSessionId, sessionData);

        res.writeHead(302, {
          'Set-Cookie': `sessionId=${newSessionId}; HttpOnly; Path=/`,
          Location: '/success.php'
        });
        return res.end();
      } else {
        // Failure
        res.writeHead(302, {
          Location: `/failure.php?username=${encodeURIComponent(username)}&error=${encodeURIComponent('Invalid Login Name or Password. Please verify your credentials.')}`
        });
        return res.end();
      }
    });
    return;
  }

  // Route: GET /success.php
  if (pathname === '/success.php') {
    if (!currentUser) {
      res.writeHead(302, { Location: '/index.html?error=' + encodeURIComponent('Access Denied: Please log in first.') });
      return res.end();
    }
    const html = renderSuccess(currentUser);
    res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
    return res.end(html);
  }

  // Route: GET /failure.php
  if (pathname === '/failure.php') {
    const errorMsg = parsedUrl.searchParams.get('error') || 'Invalid Login Name or Password.';
    const username = parsedUrl.searchParams.get('username') || '';
    const html = renderFailure(errorMsg, username);
    res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
    return res.end(html);
  }

  // Route: GET /logout.php or /logout
  if (pathname === '/logout.php' || pathname === '/logout') {
    if (sessionId) {
      sessions.delete(sessionId);
    }
    res.writeHead(302, {
      'Set-Cookie': 'sessionId=; HttpOnly; Path=/; Max-Age=0',
      Location: '/index.html?message=' + encodeURIComponent('You have been successfully logged out.')
    });
    return res.end();
  }

  // Static files handling
  let filePath = path.join(__dirname, pathname === '/' ? 'index.html' : pathname);
  const ext = path.extname(filePath);

  if (fs.existsSync(filePath) && fs.statSync(filePath).isFile()) {
    const contentType = mimeTypes[ext] || 'text/plain';
    res.writeHead(200, { 'Content-Type': contentType });
    fs.createReadStream(filePath).pipe(res);
  } else {
    res.writeHead(404, { 'Content-Type': 'text/plain' });
    res.end('404 Not Found');
  }
});

server.listen(PORT, () => {
  console.log(`🚀 Lab 11 Server running at: http://localhost:${PORT}`);
  console.log(`📋 Login Portal: http://localhost:${PORT}/index.html`);
});

