<html>
    <head>

    </head>
    <body>
        <h1>Form Tambah User</h1>
        <form action="{{ route('user.simpan') }}" method="POST">
            {{ csrf_field() }}

            <label>Username</label>
            <input type="text" name="username" placeholder="Masukan Username">
            <br>
            <label>Nama</label>
            <input type="text" name="nama" placeholder="Masukan Nama">
            <br>
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukan Password">
            <br>
            <label>Level ID</label>
            <input type="number" name="level_id" placeholder="Masukan ID Level">
            <br><br>
            <input type="submit" class="btn btn-success" value="Simpan">
        </form>
    </body>
</html>