

@extends('index')

@section('content')
<div class="container mt-4">
	
	  <div class="form-container">
				     <p>Register !</p>
        </div>
        
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
    		<label for="role">Role:</label>
   			 <select id="role" name="role" class="form-control" required>
                @if(Auth::user()->role == 'admin')
        	<option value="admin">Admin</option>
            @endif
            @if(Auth::user()->role == 'pengelola')
        	<option value="pengelola">Pengelola</option>
            @endif
    		</select>
		</div>
		<div class="form-group">
            <label for="nip">Nip:</label>
            <input  type="text" id="nip" name="nip" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm Password:</label>
            <input  type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        </div>
        <div class="container-login100-form-btn">
						<button class="btn btn-primary" type="submit" name="submit">
							Register
						</button>
					</div>
    </form>
			</div>
		</div>
	</div>

			</div>
		</div>
	</div>
	@endsection
	
