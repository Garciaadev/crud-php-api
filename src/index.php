<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Productos</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        h1 { color: #333; margin-bottom: 20px; }
        h2 { color: #555; margin: 20px 0 10px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        th { background: #4a90e2; color: white; padding: 12px; text-align: left; }
        td { padding: 10px 12px; border-bottom: 1px solid #eee; }
        tr:hover { background: #f9f9f9; }
        .btn { padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; }
        .btn-edit { background: #f0ad4e; color: white; }
        .btn-delete { background: #d9534f; color: white; }
        .btn-add { background: #5cb85c; color: white; padding: 10px 20px; font-size: 15px; margin-bottom: 15px; }
        .form-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 500px; }
        input, textarea { width: 100%; padding: 8px; margin: 6px 0 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .btn-submit { background: #4a90e2; color: white; padding: 10px 20px; font-size: 15px; width: 100%; }
        .hidden { display: none; }
        .msg { padding: 10px; border-radius: 4px; margin: 10px 0; }
        .msg-ok { background: #dff0d8; color: #3c763d; }
        .msg-err { background: #f2dede; color: #a94442; }
    </style>
</head>
<body>
    <h1>🛒 CRUD Productos — API PHP</h1>

    <div id="msg" class="msg hidden"></div>

    <h2>Lista de productos</h2>
    <button class="btn btn-add" onclick="toggleForm()">+ Nuevo producto</button>

    <div id="formContainer" class="form-container hidden" style="margin-bottom:20px;">
        <h2 id="formTitle">Nuevo producto</h2>
        <input type="hidden" id="editId">
        <label>Nombre *</label>
        <input type="text" id="nombre" placeholder="Nombre del producto">
        <label>Descripción</label>
        <textarea id="descripcion" rows="3" placeholder="Descripción opcional"></textarea>
        <label>Precio *</label>
        <input type="number" id="precio" placeholder="0.00" step="0.01">
        <label>Stock</label>
        <input type="number" id="stock" placeholder="0">
        <button class="btn btn-submit" onclick="guardar()">Guardar</button>
    </div>

    <table id="tabla">
        <thead>
            <tr>
                <th>ID</th><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Stock</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tbody"></tbody>
    </table>

    <script>
        const API = 'api.php';

        async function cargar() {
            const res = await fetch(API);
            const data = await res.json();
            const tbody = document.getElementById('tbody');
            tbody.innerHTML = data.map(p => `
                <tr>
                    <td>${p.id}</td>
                    <td>${p.nombre}</td>
                    <td>${p.descripcion || '-'}</td>
                    <td>${parseFloat(p.precio).toFixed(2)} €</td>
                    <td>${p.stock}</td>
                    <td>
                        <button class="btn btn-edit" onclick="editar(${p.id},'${p.nombre}','${p.descripcion}',${p.precio},${p.stock})">Editar</button>
                        <button class="btn btn-delete" onclick="eliminar(${p.id})">Eliminar</button>
                    </td>
                </tr>
            `).join('');
        }

        function toggleForm(reset = true) {
            const f = document.getElementById('formContainer');
            f.classList.toggle('hidden');
            if (reset) {
                document.getElementById('editId').value = '';
                document.getElementById('nombre').value = '';
                document.getElementById('descripcion').value = '';
                document.getElementById('precio').value = '';
                document.getElementById('stock').value = '';
                document.getElementById('formTitle').textContent = 'Nuevo producto';
            }
        }

        function editar(id, nombre, descripcion, precio, stock) {
            document.getElementById('editId').value = id;
            document.getElementById('nombre').value = nombre;
            document.getElementById('descripcion').value = descripcion;
            document.getElementById('precio').value = precio;
            document.getElementById('stock').value = stock;
            document.getElementById('formTitle').textContent = 'Editar producto';
            document.getElementById('formContainer').classList.remove('hidden');
        }

        async function guardar() {
            const id = document.getElementById('editId').value;
            const body = {
                nombre: document.getElementById('nombre').value,
                descripcion: document.getElementById('descripcion').value,
                precio: parseFloat(document.getElementById('precio').value),
                stock: parseInt(document.getElementById('stock').value) || 0
            };
            const url = id ? `${API}?id=${id}` : API;
            const method = id ? 'PUT' : 'POST';
            const res = await fetch(url, { method, headers: {'Content-Type':'application/json'}, body: JSON.stringify(body) });
            const data = await res.json();
            mostrarMsg(data.message || data.error, res.ok);
            if (res.ok) { toggleForm(); cargar(); }
        }

        async function eliminar(id) {
            if (!confirm('¿Eliminar este producto?')) return;
            const res = await fetch(`${API}?id=${id}`, { method: 'DELETE' });
            const data = await res.json();
            mostrarMsg(data.message || data.error, res.ok);
            cargar();
        }

        function mostrarMsg(txt, ok) {
            const m = document.getElementById('msg');
            m.textContent = txt;
            m.className = 'msg ' + (ok ? 'msg-ok' : 'msg-err');
            setTimeout(() => m.classList.add('hidden'), 3000);
        }

        cargar();
    </script>
</body>
</html>
