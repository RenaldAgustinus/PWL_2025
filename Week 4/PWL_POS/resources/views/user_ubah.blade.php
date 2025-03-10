<html>
    <head>

    </head>
    <body>
        <h1>Form Ubah Data User</h1>
        <br><br>
        <form action="{{ route('user.ubah_simpan', ['id' => $data->user_id]) }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <label>Username</label>
            <input type="text" name="username" placeholder="Masukan Username" value="{{ $data->username }}">
            <br>
            <label>Nama</label>
            <input type="text" name="nama" placeholder="Masukan Nama" value="{{ $data->nama }}">
            <br>
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukan Password" value="{{ $data->password }}">
            <br>
            <label>Level ID</label>
            <input type="number" name="level_id" placeholder="Masukan ID Level" value="{{ $data->level_id }}">
            <br><br>
            <input type="submit" class="btn btn-success" value="Ubah">
        </form>
    </body>
</html>