<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Register</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo e(asset('assets/vendor/fontawesome-free/css/all.min.css')); ?>" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo e(asset('assets/css/sb-admin-2.min.css')); ?>" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            
                            <!-- LEFT SIDE WITH LOGO -->
                            <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center bg-white">
                                <div class="text-center">
                                    <svg width="120" height="120" viewBox="0 0 64 64" aria-labelledby="campuscoordLogo" role="img">
                                        <title id="campuscoordLogo">CampusCoord logo</title>
                                        <defs>
                                            <linearGradient id="cc-grad" x1="0" y1="0" x2="1" y2="1">
                                                <stop offset="0%"  stop-color="#2dd4bf"/>
                                                <stop offset="100%" stop-color="#4f46e5"/>
                                            </linearGradient>
                                            <filter id="soft" x="-20%" y="-20%" width="140%" height="140%">
                                                <feDropShadow dx="0" dy="2" stdDeviation="2" flood-opacity=".18"/>
                                            </filter>
                                        </defs>

                                        <!-- Pin base -->
                                        <path d="M32 6c10.5 0 19 8.2 19 18.3 0 11.1-9.9 19.5-16 28.9-1 1.6-2.9 1.6-3.9 0C24.9 43.9 13 35.3 13 24.3 13 14.2 21.5 6 32 6z"
                                            fill="url(#cc-grad)" filter="url(#soft)"/>

                                        <!-- Inner “C” rings -->
                                        <circle cx="32" cy="24" r="11.5" fill="none" stroke="white" stroke-width="4" opacity=".9"/>
                                        <path d="M39 24a7 7 0 1 1-7-7" fill="none" stroke="white" stroke-width="4" stroke-linecap="round" opacity=".95"/>

                                        <!-- Campus dot -->
                                        <circle cx="32" cy="24" r="3.2" fill="white" opacity=".95"/>
                                    </svg>

                                    <h2 class="mt-3 font-weight-bold">
                                        <span style="background: linear-gradient(135deg,#2dd4bf,#4f46e5);
                                        -webkit-background-clip: text; color: transparent;">Campus</span>Coord
                                    </h2>
                                </div>
                            </div>

                            <!-- RIGHT SIDE (REGISTRATION FORM) -->
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                                    </div>

                                    
                                    <?php if(session('success')): ?>
                                        <div class="alert alert-success">
                                            <?php echo e(session('success')); ?>

                                        </div>
                                    <?php endif; ?>

                                    
                                    <form class="user" action="<?php echo e(url('/register')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>

                                        <div class="form-group">
                                            <input type="text" name="name" value="<?php echo e(old('name')); ?>"
                                                class="form-control form-control-user"
                                                placeholder="Full Name">
                                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <small class="text-danger"><?php echo e($message); ?></small>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        <div class="form-group">
                                            <input type="email" name="email" value="<?php echo e(old('email')); ?>"
                                                class="form-control form-control-user"
                                                placeholder="Email Address">
                                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <small class="text-danger"><?php echo e($message); ?></small>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="password" name="password"
                                                    class="form-control form-control-user"
                                                    placeholder="Password">
                                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <small class="text-danger"><?php echo e($message); ?></small>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="password" name="password_confirmation"
                                                    class="form-control form-control-user"
                                                    placeholder="Repeat Password">
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Register Account
                                        </button>
                                        <hr>
                                    </form>

                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="<?php echo e(url('/forgot-password')); ?>">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="<?php echo e(url('/login')); ?>">Already have an account? Login!</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo e(asset('assets/vendor/jquery/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo e(asset('assets/vendor/jquery-easing/jquery.easing.min.js')); ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo e(asset('assets/js/sb-admin-2.min.js')); ?>"></script>

</body>

</html>
<?php /**PATH C:\Users\HP Elitebook\Group2Laravel\BSCS-Group_2\resources\views/auth/register.blade.php ENDPATH**/ ?>