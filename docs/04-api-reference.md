# 04 API Reference

Base URL: `http://localhost:8010/api/v1`

Header untuk endpoint privat:

```text
Authorization: Bearer TOKEN
Content-Type: application/json
Accept: application/json
```

## Auth

### POST /register

Publik. Limit 30/menit.

```json
{"name": "Budi", "email": "budi@mail.com", "password": "password123"}
```

- `201`: user dibuat
- `422`: email duplikat / password < 8

### POST /login

Publik. Limit 5/menit.

```json
{"email": "budi@mail.com", "password": "password123"}
```

Respons:

```json
{
  "ok": true,
  "message": "Login successful",
  "data": {
    "user": {"id": 1, "name": "Budi", "email": "budi@mail.com", "role": "user"},
    "token": "1|..."
  }
}
```

- `422`: email / password salah

### GET /me

Wajib token.

- `200`: profil user
- `401`: tanpa token

### POST /logout

Wajib token. Hapus semua token user.

- `200`: logout berhasil
- `401`: tanpa token

## Products

Semua wajib token.

### GET /products

Query: `search`, `per_page` (default 20).

```bash
curl "http://localhost:8010/api/v1/products?search=kopi&per_page=10" \
  -H "Authorization: Bearer TOKEN"
```

### POST /products

```json
{"name": "Kopi", "price": 15000, "stock": 10}
```

- `201`: dibuat
- `422`: validasi gagal

### GET /products/{id}

- `200`: detail
- `404`: tidak ada

### PUT /products/{id}

Update sebagian boleh. Contoh:

```json
{"price": 20000}
```

### DELETE /products/{id}

- `200`: dihapus
- `404`: tidak ada

## Kode status

| Kode | Arti | Contoh |
|---|---|---|
| 200 | berhasil | GET, PUT, DELETE |
| 201 | dibuat | POST register, POST product |
| 401 | belum login | tanpa / salah token |
| 403 | dilarang | role tidak cocok |
| 404 | tidak ada | ID salah |
| 422 | input salah | validasi gagal |
| 429 | kebanyakan | login > 5x/menit |
