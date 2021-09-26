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
				body: params
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

	socket.on('disconnect', () => {
		console.log(`> User ${socket.id} disconnected`);
	});
});

server.listen(2021, () => {
	console.log('> listening on http://localhost:2021');
});
