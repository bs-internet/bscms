<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Şifremi Unuttum | BSCMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/admin/css/auth.css">
</head>

<body class="centered-page">
    <div class="wrapper">
        <div style="margin-bottom: 1.5rem; font-size: 2rem; color: var(--primary);">
            <i class="fa-solid fa-lock"></i>
        </div>
        <h1>Şifrenizi mi unuttunuz?</h1>
        <p>E-posta adresinizi girin, size şifre sıfırlama bağlantısı gönderelim.</p>

        <form>
            <input type="email" placeholder="admin@example.com" required>
            <button type="submit">Sıfırlama Bağlantısı Gönder</button>
        </form>

        <a href="/admin/login" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Giriş'e Dön
        </a>
    </div>
</body>

</html>