const express = require('express')
const app = express()
const http = require('http')
const fetch = (...args) => import('node-fetch').then(({
	default: fetch
}) => fetch(...args));
const server = http.createServer(app)
const {
	Server
} = require("socket.io");

const io = new Server(server, {
	cors: {
		origin: "*",
		methods: ["GET", "POST"],
		credentials: true
	},
	cookie: {
		name: "my-ci-scoket-cookie",
		httpOnly: true,
		sameSite: "strict",
		maxAge: 86400
	}
});
app.get('/', (req, res) => {
	res.send('Express.js @' + require('express/package.json').version);
});

io.on('connection', (socket) => {
	console.log(`> A user ${socket.id} connected`)

	/* Pengaduan */
	// Handle event dari client-frontend
	socket.on('frontend-pengaduan-kirim', (data) => {
		console.log(`> User ${socket.id} mengirim pengaduan`)

		// Trigger event pengaduan ke client-backend
		io.emit('backend-pengaduan-terima', data)
	})

	/* CSRF */
	socket.on('minta-csrf', async (data) => {
		console.log(`> User ${socket.id} meminta csrf token`)

		let params = new URLSearchParams()
		params.append('key', data.key)
		fetch(data.url, {
				method: 'POST',
				body: params,
				headers: {
					'Cookie': `ciarthur_csrf_cookie=${data.cookie}; ciarthur_session=${data.session}`,
					'Authorization': `Bearer ${data.token}`
				}
			})
			.then(res => res.json())
			.then(res => {
				console.log(res)
				io.emit('terima-csrf', res)
			})
			.catch(err => {
				console.log(err.response.data)
			})

	})

	/* Mahasiswa */
	socket.on('backend-crud-mahasiswa', (data) => {
		console.log(`> Admin ${socket.id} melakukan crud mahasiswa`)

		io.emit('backend-reload_dt-mahasiswa', data)
	})

	/* User */
	socket.on('auth-crud-user', (data) => {
		console.log(`> Admin ${socket.id} melakukan crud user`)

		io.emit('auth-reload_dt-user', data)
	})

	socket.on('auth-activate-user', (data) => {
		console.log(`> Admin ${socket.id} melakukan activate user`)

		io.emit('auth-reload_dt-user', data)
	})

	socket.on('auth-deactivate-user', (data) => {
		console.log(`> Admin ${socket.id} melakukan deactivate user`)

		io.emit('auth-reload_dt-user', data)
	})

	/* Role */
	socket.on('auth-crud-role', (data) => {
		console.log(`> Admin ${socket.id} melakukan crud role`)

		io.emit('auth-reload_dt-role', data)
	})

	socket.on("connect_error", (err) => {
		console.log(`> connect_error due to ${err.message}`);
	});

	socket.on('disconnect', () => {
		console.log(`> User ${socket.id} disconnected`);
	});
});

server.listen(3021, () => {
	console.log('> listening on http://localhost:3021');
});
