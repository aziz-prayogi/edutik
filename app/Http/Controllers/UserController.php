<?php

namespace App\Http\Controllers;

use App\Models\cr;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function register(request $request) {
        $request->validate([
            //format : 'name_input' => 'rule_validasi'
            'username' => 'required|min:3',
            // email:dns -> memastikan domain email valid
            'email' => 'required | email:dns',
            'password' => 'required',
        ], [
            //custom pesan
            //format : 'name_input.validasi' => 'pesan error'
            'username.required' => 'First name wajib diisi',
            'username.min' => 'First name minimal 3 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'email diisi dengan data valid',
            'password.required' => 'Password wajib diisi',
        ]);

        $createData = user::create([
            // 'column' => $request -> name_input
            'username' => $request->username ,
            'email' => $request->email,
            //enkripsi data : mengubah menjadi data acak, tidak ada yang bisa tau isi datanya : hash::make()
            'password' => Hash::make($request->password),
            //role diisi langsung sebagai user agar ridak bisa menjadi admin/staff
            'role' => 'user',
        ]);

        if ($createData){
            //redirect : mengirim ulang ke halaman tertentu
            //route() : mengambil route berdasarkan nama
            return redirect()->route('user.index')->with('success', 'berhasil membuat akun Silahkan login');
        } else {
            //back() : mengembalikan ke halaman sebelumnya
            return redirect()->back()->with('error', 'gagal membuat akun Silahkan coba lagi');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $Users = User::all();
        return view('admin.user.index', compact('Users'));
    }

    public function datatables()
    {
        // 1. Ambil data pengguna. Gunakan query() untuk efisiensi
        $users = User::query();

        return DataTables::of($users)
            // Tambahkan kolom penomor (No. urut)
            ->addIndexColumn()


            // Kolom kustom 'action' (sesuai permintaan DataTables)
            ->addColumn('action', function(User $user) {
                // Definisikan tombol aksi di sini
                $btnEdit = '<a href="' . route('admin.users.edit', $user->id) . '" class="btn btn-primary btn-sm me-2">Edit</a>';

                // Form Hapus (menggunakan method DELETE)
                $btnDelete = '<form action="'. route('admin.users.delete', $user->id) .'" method="POST" style="display:inline;">' .
                                    csrf_field() . // Token CSRF
                                    method_field('DELETE') .'
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin Hapus Pengguna: ' . $user->username . '?\')">Hapus</button>
                                </form>';

                // Mengembalikan HTML untuk kolom 'action'
                return '<div class="d-flex">' . $btnEdit . $btnDelete . '</div>';
            })

            // Tentukan kolom mana yang mengembalikan HTML mentah (kolom 'action')
            ->rawColumns(['action'])

            ->make(true);
    }

    public function loginAuth(Request $request) {
        $request->validate([
            'email' => 'required', // Mengambil input yang bisa berupa username atau email
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $data = $request->only('email', 'password');
        //Auth::attempt() : verifikasi kecocokan email-pw atau username-pw
        if (Auth::attempt($data)) {

            $request->session()->regenerate();
            Auth::user()->refresh();
            if (Auth::user()->role == 'admin') {

                return redirect()->route('admin.dashboard')->with('success', 'berhasil login!');
            } elseif (Auth::user()->role == 'staff') {
                return redirect()->route('staff.reports.index')->with('success', 'berhasil login!');
            } else {
                return redirect()->route('user.index')->with('success', 'berhasil login!');
            }
        } else {
            return redirect()->back()->with('error', 'gagal pastikan email dan password sesuai');
        }
    }
    public function editProfile()
    {
        $user = Auth::user();
        return view('user.profile_edit', compact('user'));
    }

    // Memproses Update Profile (Foto & Password opsional)
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        // 1. Update Username
        $user->username = $request->username;

        // 2. Cek apakah ada file foto yang diupload
        if ($request->hasFile('profile_picture')) {
            // Hapus foto lama jika ada (agar storage tidak penuh)
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Simpan foto baru
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'Profil berhasil diperbarui!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home')->with('logout', 'anda sudah logout silahkan login kembali');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
         $request->validate( [
            'username' =>'required',
            'email' => 'required|email:dns|unique:users,email',
            'password' => 'required|min:8',
        ], [
            'username.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email diisi dengan format yang benar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
        ]);
        $createData = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff',
        ]);
        if ($createData){
            return redirect()->route('admin.users.index')->with('success', 'staff berhasil ditambahkan');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan user');
        }
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $user = User::find($id);
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'name' =>'required',
            'email' => 'required|email:dns|unique:users,email,'.$id,
            'password' => 'nullable',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
        ]);
        $updateData = User::where('id',$id)->update([
            'name' => $request->name,
            'email' => $request->email,
            //jika password diisi maka diupdate, jika tidak diisi maka tidak diupdate
            'password' => Hash::make($request->password),
            // 'password' => $request->password ? Hash::make($request->password) : $request->old_password,
        ]);
        if ($updateData) {
            return redirect()->route('admin.users.index')->with('success','staff berhasil diedit');
        } else {
            return redirect()->back()->with('error','staff gagal ditambahkan');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $user = User::find($id);

    if ($user) {
        $user->delete(); // soft delete
        return redirect()->route('admin.users.index')->with('success', 'Berhasil menghapus data (soft delete)');
    } else {
        return redirect()->route('admin.users.index')->with('error', 'User tidak ditemukan');
    }
    }

    public function exportExcel()
    {
        $fileName = 'data-pengguna.xlsx';
        return Excel::download(new UsersExport, $fileName);
    }

    public function trash() {
        $userTrash = User::onlyTrashed()->get();
        return view('admin.user.trash', compact('userTrash'));
    }

    public function restore($id) {
        $user = User::onlyTrashed()->find($id);
        $user->restore();
        return redirect()->route('admin.users.index')->with('success', 'data berhasil dikembalikkan');
    }

    public function deletePermanent($id) {
        $user = User::onlyTrashed()->find($id);
        $user->forceDelete();
        return redirect()->back()->with('success', 'berhasil hapus data secara permanen');
    }
}

// dd([
//             'role_di_database' => Auth::user()->role,
//             'apakah_sama_dengan_staff' => (Auth::user()->role == 'staff'),
//             'panjang_string' => strlen(Auth::user()->role), // Cek jika ada spasi tersembunyi
//             'semua_data_user' => Auth::user()->toArray()
//         ]);
