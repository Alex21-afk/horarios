<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Turnos</title>
    
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-gradient-custom">

<nav class="navbar navbar-expand-lg navbar-custom shadow-sm mb-4">
    <div class="container">
        <div class="d-flex align-items-center">
            <img src="img/logo.png" alt="logo" class="logo-img me-3">
            <span class="navbar-brand mb-0 fw-bold text-white"><i class="bi bi-calendar-check me-2"></i>Sistema de Turnos</span>
        </div>
        <div class="ms-auto">
            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                <i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y') ?>
            </span>
        </div>
    </div>
</nav>
<div class="container pb-5">