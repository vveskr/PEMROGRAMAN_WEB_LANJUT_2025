@extends('layouts.template')
 
 @section('content')
 @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

 <div class="card">
     <div class="card-header">
         <h3 class="card-title">Profile User</h3>
     </div>
     <div class="card-body">
         <div class="row">
             <div class="col-md-4 text-center">
                 <img src="{{ $user->user_profile_picture ? asset('storage/'.$user->user_profile_picture) : asset('img/default-profile.png') }}" 
                     class="img-circle elevation-2" alt="User Image" style="width: 200px; height: 200px; object-fit: cover;">
                 
                 <form action="{{ url('/user/update_picture') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                     @csrf
                     <div class="form-group">
                         <div class="input-group">
                             <div class="custom-file">
                                 <input type="file" class="custom-file-input" id="user_profile_picture" name="user_profile_picture" accept="image/*">
                                 <label class="custom-file-label" for="user_profile_picture">Pilih Foto</label>
                             </div>
                         </div>
                         @error('user_profile_picture')
                             <small class="text-danger">{{ $message }}</small>
                         @enderror
                     </div>
                     <button type="submit" class="btn btn-primary">Upload Foto</button>
                 </form>
             </div>
             <div class="col-md-8">
                 <h4>Data User</h4>
                 <table class="table">
                     <tr>
                         <th width="30%">Username</th>
                         <td>: {{ $user->username }}</td>
                     </tr>
                     <tr>
                         <th>Nama</th>
                         <td>: {{ $user->nama }}</td>
                     </tr>
                     <tr>
                         <th>Level</th>
                         <td>: {{ $user->level->level_nama ?? '-' }}</td>
                     </tr>
                 </table>
             </div>
         </div>
     </div>
 </div>
 @endsection
 
 @push('scripts')
 <script>
     $(document).ready(function() {
         $('#user_profile_picture').on('change', function() {
             var fileName = $(this).val().split('\\').pop();
             $(this).next('.custom-file-label').html(fileName);
             
             if (this.files && this.files[0]) {
                 var reader = new FileReader();
                 reader.onload = function(e) {
                     $('img.img-circle').attr('src', e.target.result);
                 }
                 reader.readAsDataURL(this.files[0]);
             }
         });
     });
 </script>
 @endpush