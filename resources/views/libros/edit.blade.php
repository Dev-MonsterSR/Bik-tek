<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Libro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background:rgb(181, 183, 185);
        }
        .edit-card {
            max-width: 700px;
            margin: 40px auto;
            border-radius: 18px;
            border: none;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            animation: fadeInUp 0.8s cubic-bezier(.39,.575,.565,1) both;
            background: #fff;
        }
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(40px);}
            100% { opacity: 1; transform: none;}
        }
        .header-gradient {
            background: linear-gradient(90deg, #0B5ED7 60%, #0B1C2B 100%);
            color: #fff;
            border-radius: 18px 18px 0 0;
            padding: 24px 32px 16px 32px;
            margin-bottom: 0;
            box-shadow: 0 2px 8px rgba(11,94,215,0.08);
        }
        .form-label {
            font-weight: 500;
            color: #0B1C2B;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0B5ED7;
            box-shadow: 0 0 0 0.15rem rgba(11,94,215,0.15);
        }
        .img-preview {
            border: 1px solid #e3e3e3;
            background: #fff;
            padding: 6px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: box-shadow 0.3s;
        }
        .img-preview img {
            transition: transform 0.3s;
        }
        .img-preview img:hover {
            transform: scale(1.08) rotate(-2deg);
        }
        .btn-primary {
            background: linear-gradient(90deg, #0B5ED7 60%, #0B1C2B 100%);
            border: none;
            transition: background 0.3s, box-shadow 0.3s;
            box-shadow: 0 2px 8px rgba(11,94,215,0.08);
        }
        .btn-primary:hover, .btn-primary:focus {
            background: linear-gradient(90deg, #0B1C2B 60%, #0B5ED7 100%);
            box-shadow: 0 4px 16px rgba(11,94,215,0.18);
        }
        .btn-outline-secondary {
            border-color: #0B5ED7;
            color: #0B5ED7;
        }
        .btn-outline-secondary:hover {
            background: #0B5ED7;
            color: #fff;
        }
        @media (min-width: 768px) {
            .edit-form-row {
                display: flex;
                gap: 24px;
            }
            .edit-form-col {
                flex: 1 1 0;
            }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="edit-card shadow">
            <div class="header-gradient d-flex align-items-center gap-2">
                <i class="bi bi-pencil-square fs-3"></i>
                <h2 class="mb-0 fs-3 fw-bold">Editar Libro</h2>
            </div>
            <form method="POST" action="{{ route('libros.update', $libro->id_libro) }}" enctype="multipart/form-data" class="p-4">
                @csrf
                @method('PUT')
                <div class="edit-form-row mb-3">
                    <div class="edit-form-col mb-3 mb-md-0">
                        <label for="codigo" class="form-label">Código</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" value="{{ $libro->codigo }}" required>
                    </div>
                    <div class="edit-form-col">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" value="{{ $libro->titulo }}" required>
                    </div>
                </div>
                <div class="edit-form-row mb-3">
                    <div class="edit-form-col mb-3 mb-md-0">
                        <label for="autor" class="form-label">Autor</label>
                        <input type="text" class="form-control" id="autor" name="autor" value="{{ $libro->autor }}">
                    </div>
                    <div class="edit-form-col">
                        <label for="editorial" class="form-label">Editorial</label>
                        <input type="text" class="form-control" id="editorial" name="editorial" value="{{ $libro->editorial }}">
                    </div>
                </div>
                <div class="edit-form-row mb-3">
                    <div class="edit-form-col mb-3 mb-md-0">
                        <label for="anio_publicacion" class="form-label">Año de publicación</label>
                        <input type="number" class="form-control" id="anio_publicacion" name="anio_publicacion" value="{{ $libro->anio_publicacion }}">
                    </div>
                    <div class="edit-form-col">
                        <label for="categoria_id" class="form-label">Categoría</label>
                        <select class="form-select" id="categoria_id" name="categoria_id">
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id_categoria }}" {{ $libro->categoria_id == $categoria->id_categoria ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="edit-form-row mb-3">
                    <div class="edit-form-col mb-3 mb-md-0">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" value="{{ $libro->cantidad }}" min="1" required>
                    </div>
                    <div class="edit-form-col">
                        <label for="disponibles" class="form-label">Disponibles</label>
                        <input type="number" class="form-control" id="disponibles" name="disponibles" value="{{ $libro->disponibles }}" min="0" required>
                    </div>
                </div>
                <div class="edit-form-row mb-3">
                    <div class="edit-form-col mb-3 mb-md-0">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="disponible" {{ $libro->estado == 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="prestado" {{ $libro->estado == 'prestado' ? 'selected' : '' }}>Prestado</option>
                            <option value="dañado" {{ $libro->estado == 'dañado' ? 'selected' : '' }}>Dañado</option>
                        </select>
                    </div>
                    <div class="edit-form-col">
                        <label for="portada" class="form-label">Portada</label>
                        @if($libro->portada)
                            <div class="mb-2 img-preview text-center">
                                <img src="{{ asset($libro->portada) }}" alt="Portada actual" width="90" class="rounded shadow-sm">
                            </div>
                        @endif
                        <input type="file" class="form-control" id="portada" name="portada" accept="image/*">
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm"><i class="bi bi-save me-2"></i>Actualizar</button>
                    <a href="{{ route('admin.panel') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
