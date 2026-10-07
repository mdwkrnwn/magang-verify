# Digital Signature

## Konsep
Signature dilakukan langsung pada platform menggunakan canvas yang mendukung mouse/touch/pen.

Tidak ada alur print-scan-upload.

## Urutan
Mahasiswa sign -> Mitra sign -> Dosen sign.

## Invalidation
Jika data berubah setelah mahasiswa sign:
`data berubah -> signature mahasiswa invalid -> wajib sign ulang`.

Setelah Mitra sign:
`minggu locked`.

## Security
Setiap signature request wajib:
- authenticated;
- authorized;
- CSRF protected;
- memverifikasi relasi actor dengan penempatan;
- memverifikasi urutan workflow;
- transactional.

Jangan percaya `user_id`, `role`, atau status dari hidden input.

## Audit
Simpan signer, role, waktu, minggu, payload signature, dan referensi versi data bila digunakan.

Signature harus merepresentasikan data yang benar-benar ditandatangani.
