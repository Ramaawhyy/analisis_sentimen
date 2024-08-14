<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login</title>
  
  <link rel="stylesheet" href="template/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="template/vendors/css/vendor.bundle.base.css'">
  <link rel="stylesheet" href="template/css/style.css"> <!-- Updated the CSS file reference -->
  
  <link rel="shortcut icon" href="template/images/logo.png" />
</head>
<body>
  <div class="container-scroller d-flex">
    <div class="container-fluid page-body-wrapper full-page-wrapper d-flex">
      <div class="content-wrapper d-flex align-items-center auth auth-img-bg">
        <div class="row flex-grow">
          <div class="col-lg-6 d-flex align-items-center justify-content-center">
            <div class="auth-form-transparent text-left p-3">
              <div class="brand-logo d-flex align-items-center">
                <img src="{{ asset('template/images/logo.png') }}" alt="logo" style="width: 50px; height: auto; margin-right: 20px;">
                <h4 style="font-weight: bold; font-size: 25px;">Dinas Pariwisata DIY</h4>
              </div>

               <h6>Selamat Datang!</h6>
              <form class="pt-3" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                  <label for="exampleInputEmail">Email</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-account-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="email" class="form-control form-control-lg border-left-0" id="exampleInputEmail" name="email" placeholder="Email" value="{{ old('email') }}">
                  </div>
                  @if ($errors->has('email'))
                    <div class="alert alert-danger mt-2">
                      {{ $errors->first('email') }}
                    </div>
                  @endif
                </div>

                <div class="form-group">
                  <label for="exampleInputPassword">Password</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-lock-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="password" class="form-control form-control-lg border-left-0" id="exampleInputPassword" name="password" placeholder="Password">
                  </div>
                  @if ($errors->has('password'))
                    <div class="alert alert-danger mt-2">
                      {{ $errors->first('password') }}
                    </div>
                  @endif
                </div>

                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input" name="remember">
                      Keep me signed in
                    </label>
                  </div>
                  
                </div>

                <div class="my-3 d-flex justify-content-center">
                  <button type="submit" class="btn btn-primary btn-lg font-weight-medium auth-form-btn" style="width: 100%; background-color: #007AFF;">LOGIN</button>
                </div>

				

                
              </form>
            </div>
          </div>
          <div class="col-lg-6 login-half-bg d-flex flex-row">
            <p class="text-white font-weight-medium text-center flex-grow align-self-end"></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="template/vendors/js/vendor.bundle.base.js"></script>
  <script src="template/js/jquery.cookie.js" type="text/javascript"></script>
  <script src="template/js/off-canvas.js"></script>
  <script src="template/js/hoverable-collapse.js"></script>
  <script src="template/js/template.js"></script>
</body>
</html>
